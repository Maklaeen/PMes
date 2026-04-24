<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\MaterialMovement;
use App\Models\User;
use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkOrderController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $role = optional($user?->role)->role_name;

        $query = WorkOrder::query()->with(['schedule.product', 'assignedTo']);

        if ($role === 'operator') {
            $query->where('assigned_to_user_id', $user->id);
        }

        $workOrders = $query
            ->orderByDesc('id')
            ->paginate(20);

        return view('production.work_orders.index', compact('workOrders'));
    }

    public function show(WorkOrder $workOrder)
    {
        $workOrder->load([
            'schedule.product.billOfMaterials.material',
            'assignedTo',
            'materialMovements.material',
        ]);

        $operatorQuery = User::query()
            ->whereHas('role', fn ($q) => $q->where('role_name', 'operator'))
            ->withCount([
                'assignedWorkOrders as ongoing_work_orders_count' => fn ($q) => $q->where('status', 'ongoing'),
            ])
            ->orderBy('ongoing_work_orders_count')
            ->orderBy('name');

        $availableOperators = (clone $operatorQuery)
            ->having('ongoing_work_orders_count', '=', 0)
            ->get();

        if ($availableOperators->isEmpty()) {
            $availableOperators = $operatorQuery->get();
        }

        $materials = $workOrder->schedule->product->billOfMaterials
            ->map(fn ($bom) => $bom->material)
            ->filter()
            ->unique('id')
            ->values();

        return view('production.work_orders.show', compact('workOrder', 'materials', 'availableOperators'));
    }

    public function assign(Request $request, WorkOrder $workOrder)
    {
        $workOrder->loadMissing('schedule');
        if ($workOrder->schedule?->status !== 'in_progress') {
            return back()->withErrors(['schedule' => 'Operators can only be assigned when the schedule is IN PROGRESS.']);
        }

        if (in_array($workOrder->status, ['done', 'cancelled'], true)) {
            return back()->withErrors(['status' => 'This work order cannot be assigned in its current status.']);
        }

        $validated = $request->validate([
            'assigned_to_user_id' => ['nullable', 'exists:users,id'],
        ]);

        if (!empty($validated['assigned_to_user_id'])) {
            $target = User::with('role')->findOrFail($validated['assigned_to_user_id']);
            if (optional($target->role)->role_name !== 'operator') {
                return back()->withErrors(['assigned_to_user_id' => 'Selected user is not an operator.']);
            }
        }

        $workOrder->update([
            'assigned_to_user_id' => $validated['assigned_to_user_id'] ?? null,
        ]);

        return back();
    }

    public function claim(WorkOrder $workOrder)
    {
        $user = auth()->user();
        $role = optional($user?->role)->role_name;

        if ($role !== 'operator') {
            abort(403, 'Unauthorized');
        }

        $workOrder->loadMissing('schedule');
        if ($workOrder->schedule?->status !== 'in_progress') {
            return back()->withErrors(['schedule' => 'You can only claim work orders when the schedule is IN PROGRESS.']);
        }

        if ($workOrder->status !== 'pending') {
            return back()->withErrors(['status' => 'Only pending work orders can be claimed.']);
        }

        if ($workOrder->assigned_to_user_id) {
            return back()->withErrors(['assigned_to_user_id' => 'This work order is already assigned.']);
        }

        $busy = WorkOrder::query()
            ->where('assigned_to_user_id', $user->id)
            ->where('status', 'ongoing')
            ->exists();

        if ($busy) {
            return back()->withErrors(['assigned_to_user_id' => 'You already have an ongoing work order. Finish it before claiming another.']);
        }

        $workOrder->update([
            'assigned_to_user_id' => $user->id,
        ]);

        return back();
    }

    public function start(WorkOrder $workOrder)
    {
        $workOrder->loadMissing('schedule');
        if ($workOrder->schedule?->status !== 'in_progress') {
            return back()->withErrors(['schedule' => 'Work orders can only be started when the schedule is IN PROGRESS.']);
        }

        if ($workOrder->status !== 'pending') {
            return back()->withErrors(['status' => 'Only pending work orders can be started.']);
        }

        if (!$this->canOperateOnWorkOrder($workOrder)) {
            abort(403, 'Unauthorized');
        }

        $workOrder->update([
            'status' => 'ongoing',
            'started_at' => now(),
        ]);

        return back();
    }

    public function finish(Request $request, WorkOrder $workOrder)
    {
        $workOrder->loadMissing('schedule');
        if ($workOrder->schedule?->status !== 'in_progress') {
            return back()->withErrors(['schedule' => 'Work orders can only be finished when the schedule is IN PROGRESS.']);
        }

        if ($workOrder->status === 'cancelled') {
            return back()->withErrors(['status' => 'This work order is CANCELLED and cannot be finished.']);
        }

        $validated = $request->validate([
            'actual_qty' => ['required', 'integer', 'min:0'],
        ]);

        if ($workOrder->status === 'done') {
            $role = optional(auth()->user()?->role)->role_name;
            if (!in_array($role, ['superadmin', 'admin', 'planner'], true)) {
                return back()->withErrors(['status' => 'This work order is already DONE. Please contact an admin/planner to adjust actual quantity.']);
            }

            $workOrder->update([
                'actual_qty' => $validated['actual_qty'],
            ]);

            return back()->with('status', 'Actual quantity updated.');
        }

        if (!in_array($workOrder->status, ['pending', 'ongoing'], true)) {
            return back()->withErrors([
                'status' => "This work order cannot be finished because its status is '{$workOrder->status}'. Only PENDING/ONGOING can be finished.",
            ]);
        }

        if (!$this->canCompleteWorkOrder($workOrder)) {
            abort(403, 'Unauthorized');
        }

        $workOrder->update([
            'actual_qty' => $validated['actual_qty'],
            'status' => 'done',
            'finished_at' => now(),
        ]);

        return back();
    }

    public function storeMaterialUsage(Request $request, WorkOrder $workOrder)
    {
        $workOrder->loadMissing('schedule');
        if ($workOrder->schedule?->status !== 'in_progress') {
            return back()->withErrors(['schedule' => 'Materials can only be issued when the schedule is IN PROGRESS.']);
        }

        if (!$this->canOperateOnWorkOrder($workOrder)) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'material_id' => ['required', 'exists:materials,id'],
            'quantity' => ['required', 'numeric', 'gt:0'],
            'unit' => ['nullable', 'string', 'max:20'],
            'remarks' => ['nullable', 'string', 'max:255'],
        ]);

        $materialId = (int) $validated['material_id'];

        $isInBom = $workOrder->schedule->product
            ->billOfMaterials()
            ->where('material_id', $materialId)
            ->exists();

        if (!$isInBom) {
            return back()->withErrors(['material_id' => 'Selected material is not part of the BOM for this product.']);
        }

        DB::transaction(function () use ($validated, $workOrder) {
            /** @var Material $material */
            $material = Material::lockForUpdate()->findOrFail($validated['material_id']);

            $qty = (float) $validated['quantity'];
            if ((float) $material->stock_quantity < $qty) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'quantity' => 'Not enough stock on hand for this material.',
                ]);
            }

            MaterialMovement::create([
                'material_id' => $material->id,
                'movement_type' => 'out',
                'quantity' => $qty,
                'unit' => $validated['unit'] ?? $material->unit ?? 'pcs',
                'reference_type' => 'production_use',
                'production_schedule_id' => $workOrder->production_schedule_id,
                'work_order_id' => $workOrder->id,
                'created_by_user_id' => auth()->id(),
                'remarks' => $validated['remarks'] ?? null,
            ]);

            $material->update([
                'stock_quantity' => (float) $material->stock_quantity - $qty,
            ]);
        });

        return back();
    }

    private function canOperateOnWorkOrder(WorkOrder $workOrder): bool
    {
        $user = auth()->user();
        $role = optional($user?->role)->role_name;

        if (in_array($role, ['superadmin', 'admin'], true)) {
            return true;
        }

        return $role === 'operator' && (int) $workOrder->assigned_to_user_id === (int) $user->id;
    }

    private function canCompleteWorkOrder(WorkOrder $workOrder): bool
    {
        $user = auth()->user();
        $role = optional($user?->role)->role_name;

        if (in_array($role, ['superadmin', 'admin', 'planner'], true)) {
            return true;
        }

        return $role === 'operator' && (int) $workOrder->assigned_to_user_id === (int) $user->id;
    }
}
