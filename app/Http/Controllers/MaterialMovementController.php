<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\MaterialMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaterialMovementController extends Controller
{
    public function index()
    {
        $materials = Material::query()
            ->where('status', 'active')
            ->orderBy('material_name')
            ->get();

        $movements = MaterialMovement::query()
            ->with(['material', 'createdBy', 'schedule.product', 'workOrder'])
            ->orderByDesc('id')
            ->paginate(25);

        return view('inventory.material_movements.index', compact('materials', 'movements'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'material_id' => ['required', 'exists:materials,id'],
            'movement_type' => ['required', 'in:in,out'],
            'quantity' => ['required', 'numeric', 'gt:0'],
            'unit' => ['nullable', 'string', 'max:20'],
            'reference_type' => ['nullable', 'string', 'max:30'],
            'remarks' => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($validated) {
            /** @var Material $material */
            $material = Material::lockForUpdate()->findOrFail($validated['material_id']);

            $qty = (float) $validated['quantity'];
            $movementType = $validated['movement_type'];

            if ($movementType === 'out' && (float) $material->stock_quantity < $qty) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'quantity' => 'Not enough stock on hand for this OUT movement.',
                ]);
            }

            MaterialMovement::create([
                'material_id' => $material->id,
                'movement_type' => $movementType,
                'quantity' => $qty,
                'unit' => $validated['unit'] ?? $material->unit ?? 'pcs',
                'reference_type' => $validated['reference_type'] ?? ($movementType === 'in' ? 'stock_in' : 'adjustment'),
                'created_by_user_id' => auth()->id(),
                'remarks' => $validated['remarks'] ?? null,
            ]);

            $newStock = (float) $material->stock_quantity + ($movementType === 'in' ? $qty : -$qty);

            $material->update([
                'stock_quantity' => $newStock,
            ]);
        });

        return back();
    }
}
