<?php

namespace App\Http\Controllers;

use App\Models\BillOfMaterial;
use App\Models\ProductionSchedule;
use App\Models\Product;
use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductionScheduleController extends Controller
{
    public function index()
    {
        $schedules = ProductionSchedule::query()
            ->with(['product'])
            ->withCount(['workOrders', 'qualityChecks'])
            ->orderByDesc('schedule_date')
            ->orderByDesc('id')
            ->paginate(15);

        return view('production.schedules.index', compact('schedules'));
    }

    public function create()
    {
        $products = Product::query()
            ->where('status', 'active')
            ->orderBy('product_name')
            ->get();

        return view('production.schedules.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'planned_quantity' => ['required', 'integer', 'min:1'],
            'schedule_date' => ['required', 'date'],
            'expected_end_at' => ['nullable', 'date'],
        ]);

        $schedule = ProductionSchedule::create([
            ...$validated,
            'status' => 'planned',
            'created_by_user_id' => auth()->id(),
        ]);

        return redirect()->route('production.schedules.show', $schedule);
    }

    public function show(ProductionSchedule $schedule)
    {
        $schedule->load([
            'product.billOfMaterials.material',
            'createdBy',
            'workOrders.assignedTo',
            'qualityChecks.inspectedBy',
            'cost',
        ]);

        $mrp = $this->calculateMrp($schedule);

        return view('production.schedules.show', compact('schedule', 'mrp'));
    }

    public function start(ProductionSchedule $schedule)
    {
        if ($schedule->status !== 'planned') {
            return back()->withErrors(['status' => 'Only planned schedules can be started.']);
        }

        $schedule->update([
            'status' => 'in_progress',
            'started_at' => now(),
        ]);

        return back();
    }

    public function cancel(ProductionSchedule $schedule)
    {
        if ($schedule->status === 'completed') {
            return back()->withErrors(['status' => 'Completed schedules cannot be cancelled.']);
        }

        DB::transaction(function () use ($schedule) {
            $schedule->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
            ]);

            // Cancel any remaining work orders under this schedule.
            $schedule->workOrders()
                ->whereIn('status', ['pending', 'ongoing'])
                ->update([
                    'status' => 'cancelled',
                ]);

            // Stamp finished_at only for those that were already started.
            $schedule->workOrders()
                ->where('status', 'cancelled')
                ->whereNotNull('started_at')
                ->whereNull('finished_at')
                ->update([
                    'finished_at' => now(),
                ]);
        });

        return back();
    }

    public function complete(ProductionSchedule $schedule)
    {
        if ($schedule->status !== 'in_progress') {
            return back()->withErrors(['status' => 'Only in-progress schedules can be completed.']);
        }

        $remaining = $schedule->workOrders()->where('status', '!=', 'done')->count();
        if ($remaining > 0) {
            return back()->withErrors(['work_orders' => 'All work orders must be DONE before completing the schedule.']);
        }

        $latestQc = $schedule->qualityChecks()->latest('inspected_at')->first();
        if (!$latestQc || $latestQc->result !== 'passed') {
            return back()->withErrors(['qc' => 'A PASSED quality check is required before completing the schedule.']);
        }

        $schedule->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return back();
    }

    public function generateWorkOrders(ProductionSchedule $schedule)
    {
        if ($schedule->workOrders()->exists()) {
            return back()->withErrors(['work_orders' => 'Work orders already exist for this schedule.']);
        }

        $defaultSteps = ['Printing', 'Pressing', 'Packing'];

        DB::transaction(function () use ($schedule, $defaultSteps) {
            foreach ($defaultSteps as $index => $step) {
                WorkOrder::create([
                    'production_schedule_id' => $schedule->id,
                    'work_order_no' => $this->makeWorkOrderNo($schedule->id, $index + 1),
                    'process_step' => $step,
                    'planned_qty' => $schedule->planned_quantity,
                    'status' => 'pending',
                ]);
            }
        });

        return back();
    }

    private function makeWorkOrderNo(int $scheduleId, int $sequence): string
    {
        return 'WO-' . $scheduleId . '-' . $sequence . '-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(4));
    }

    /**
     * @return array<int, array{material: \App\Models\Material, required: float, available: float, shortage: float, unit: string, unit_cost: float, required_cost: float}>
     */
    private function calculateMrp(ProductionSchedule $schedule): array
    {
        $items = [];

        /** @var \Illuminate\Database\Eloquent\Collection<int, BillOfMaterial> $bom */
        $bom = $schedule->product->billOfMaterials;

        foreach ($bom as $line) {
            $material = $line->material;
            if (!$material) {
                continue;
            }

            $required = (float) $line->quantity_required * (float) $schedule->planned_quantity;
            $available = (float) $material->stock_quantity;
            $shortage = max($required - $available, 0);

            $unitCost = (float) $material->unit_cost;
            $requiredCost = $required * $unitCost;

            $items[] = [
                'material' => $material,
                'required' => $required,
                'available' => $available,
                'shortage' => $shortage,
                'unit' => (string) ($line->unit ?: $material->unit ?: 'pcs'),
                'unit_cost' => $unitCost,
                'required_cost' => $requiredCost,
            ];
        }

        return $items;
    }
}
