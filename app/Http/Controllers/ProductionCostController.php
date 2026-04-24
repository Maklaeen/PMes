<?php

namespace App\Http\Controllers;

use App\Models\ProductionCost;
use App\Models\ProductionSchedule;
use Illuminate\Http\Request;

class ProductionCostController extends Controller
{
    public function index()
    {
        $schedules = ProductionSchedule::query()
            ->where('status', 'in_progress')
            ->with(['product', 'cost'])
            ->orderByDesc('started_at')
            ->orderByDesc('schedule_date')
            ->orderByDesc('id')
            ->paginate(15);

        return view('costing.index', compact('schedules'));
    }

    public function compute(Request $request, ProductionSchedule $schedule)
    {
        $validated = $request->validate([
            'labor_cost' => ['nullable', 'numeric', 'min:0'],
        ]);

        $materialCost = 0;

        $schedule->loadMissing('product.billOfMaterials.material');
        foreach ($schedule->product->billOfMaterials as $line) {
            $material = $line->material;
            if (!$material) {
                continue;
            }

            $required = (float) $line->quantity_required * (float) $schedule->planned_quantity;
            $materialCost += $required * (float) $material->unit_cost;
        }

        $laborCost = (float) ($validated['labor_cost'] ?? 0);
        $total = $materialCost + $laborCost;
        $cpu = $schedule->planned_quantity > 0 ? ($total / (float) $schedule->planned_quantity) : 0;

        ProductionCost::updateOrCreate(
            ['production_schedule_id' => $schedule->id],
            [
                'material_cost' => round($materialCost, 2),
                'labor_cost' => round($laborCost, 2),
                'total_cost' => round($total, 2),
                'cost_per_unit' => round($cpu, 4),
                'computed_by_user_id' => auth()->id(),
                'computed_at' => now(),
            ]
        );

        return back();
    }
}
