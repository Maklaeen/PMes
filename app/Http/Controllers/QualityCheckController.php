<?php

namespace App\Http\Controllers;

use App\Models\ProductionSchedule;
use App\Models\QualityCheck;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class QualityCheckController extends Controller
{
    public function index()
    {
        $schedules = ProductionSchedule::query()
            ->where('status', 'in_progress')
            ->whereHas('workOrders')
            ->whereDoesntHave('workOrders', fn ($q) => $q->where('status', '!=', 'done'))
            ->with(['product'])
            ->with([
                'qualityChecks' => fn ($q) => $q->latest('inspected_at')->limit(1),
            ])
            ->orderByDesc('started_at')
            ->orderByDesc('schedule_date')
            ->orderByDesc('id')
            ->paginate(15);

        return view('qc.inspections.index', compact('schedules'));
    }

    public function store(Request $request, ProductionSchedule $schedule)
    {
        $schedule->loadMissing('workOrders');

        if ($schedule->status !== 'in_progress') {
            return back()->withErrors(['schedule' => 'QC can only be recorded while the schedule is IN PROGRESS (after production is done).']);
        }

        if ($schedule->workOrders->isEmpty()) {
            return back()->withErrors(['schedule' => 'QC requires work orders. Please generate work orders first.']);
        }

        $hasNotDone = $schedule->workOrders->contains(fn ($wo) => $wo->status !== 'done');
        if ($hasNotDone) {
            return back()->withErrors(['schedule' => 'QC can only be recorded after all work orders are DONE.']);
        }

        $validated = $request->validate([
            'result' => ['required', 'in:passed,failed'],
            'qty_passed' => ['required', 'integer', 'min:0'],
            'qty_failed' => ['required', 'integer', 'min:0'],
            'remarks' => [
                Rule::requiredIf(fn () => $request->input('result') === 'failed'),
                'nullable',
                'string',
                'max:255',
            ],
            'inspected_at' => ['nullable', 'date'],
        ]);

        $qtyPassed = (int) $validated['qty_passed'];
        $qtyFailed = (int) $validated['qty_failed'];
        $inspectedTotal = $qtyPassed + $qtyFailed;

        if ($inspectedTotal <= 0) {
            return back()->withErrors(['qty_passed' => 'Please enter at least 1 inspected quantity (passed or failed).']);
        }

        if ($validated['result'] === 'passed') {
            if ($qtyFailed > 0) {
                return back()->withErrors(['qty_failed' => 'Result is PASSED so Qty Failed must be 0.']);
            }
            if ($qtyPassed <= 0) {
                return back()->withErrors(['qty_passed' => 'Result is PASSED so Qty Passed must be greater than 0.']);
            }
        }

        if ($validated['result'] === 'failed' && $qtyFailed <= 0) {
            return back()->withErrors(['qty_failed' => 'Result is FAILED so Qty Failed must be greater than 0.']);
        }

        QualityCheck::create([
            'production_schedule_id' => $schedule->id,
            'inspected_by_user_id' => auth()->id(),
            ...$validated,
            'inspected_at' => $validated['inspected_at'] ?? now(),
        ]);

        return back();
    }
}
