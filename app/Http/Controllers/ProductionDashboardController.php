<?php

namespace App\Http\Controllers;

use App\Models\ProductionSchedule;

class ProductionDashboardController extends Controller
{
    public function index()
    {
        $schedules = ProductionSchedule::query()
            ->where('status', 'in_progress')
            ->with(['product'])
            ->withCount([
                'workOrders',
                'workOrders as work_orders_done_count' => fn ($q) => $q->where('status', 'done'),
            ])
            ->with([
                'qualityChecks' => fn ($q) => $q->latest('inspected_at')->limit(1),
            ])
            ->orderByDesc('schedule_date')
            ->orderByDesc('id')
            ->paginate(15);

        $inProgressCount = ProductionSchedule::query()
            ->where('status', 'in_progress')
            ->count();

        return view('production.dashboard', compact('schedules', 'inProgressCount'));
    }
}
