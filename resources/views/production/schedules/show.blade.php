@extends('layouts.admin')

@section('title', 'Schedule #' . $schedule->id)

@section('content')
@php
    $role = optional(auth()->user()->role)->role_name;
    $canManageSchedule = in_array($role, ['superadmin', 'admin', 'planner'], true);
    $canCancelSchedule = in_array($role, ['superadmin', 'admin'], true);
    $canQc = in_array($role, ['superadmin', 'admin', 'qc'], true);
    $canCost = in_array($role, ['superadmin', 'admin', 'planner'], true);

    $qcReady = $schedule->status === 'in_progress'
        && $schedule->workOrders->count() > 0
        && $schedule->workOrders->every(fn ($wo) => $wo->status === 'done');

    $status = $schedule->status;
    $statusStyles = match($status) {
        'planned' => 'text-gray-300 bg-white/5 border-white/10',
        'in_progress' => 'text-orange-400 bg-orange-500/10 border-orange-500/20',
        'completed' => 'text-emerald-400 bg-emerald-500/10 border-emerald-500/20',
        'cancelled' => 'text-red-400 bg-red-500/10 border-red-500/20',
        default => 'text-gray-300 bg-white/5 border-white/10',
    };
@endphp

@if($errors->any())
    <div class="mb-6 p-4 rounded-2xl bg-red-500/10 border border-red-500/20 text-sm text-red-300">
        {{ $errors->first() }}
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 space-y-8">
        <div class="bg-[#111114] border border-white/[0.05] rounded-[24px] p-8">
            <div class="flex items-start justify-between gap-6">
                <div>
                    <div class="flex items-center gap-3">
                        <div class="text-2xl font-extrabold text-white tracking-tight">Schedule #{{ $schedule->id }}</div>
                        <span class="text-[10px] font-bold uppercase tracking-widest px-2.5 py-1 rounded-lg border {{ $statusStyles }}">
                            {{ str_replace('_', ' ', $schedule->status) }}
                        </span>
                    </div>
                    <div class="mt-3 text-sm text-gray-400">
                        <span class="font-semibold text-gray-200">{{ $schedule->product?->product_name ?? '—' }}</span>
                        <span class="text-gray-600">•</span>
                        {{ number_format($schedule->planned_quantity) }} {{ $schedule->product?->unit ?? 'pcs' }}
                        <span class="text-gray-600">•</span>
                        {{ $schedule->schedule_date->format('M d, Y') }}
                    </div>
                    <div class="mt-1 text-xs text-gray-600">
                        Created by: {{ $schedule->createdBy?->name ?? '—' }}
                    </div>
                    <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="p-4 rounded-2xl bg-white/[0.02] border border-white/[0.05]">
                            <div class="text-[11px] font-bold text-gray-600 uppercase tracking-widest">Started At</div>
                            <div class="mt-2 text-sm font-bold text-white">
                                {{ $schedule->started_at ? $schedule->started_at->format('M d, Y H:i') : '—' }}
                            </div>
                        </div>
                        <div class="p-4 rounded-2xl bg-white/[0.02] border border-white/[0.05]">
                            <div class="text-[11px] font-bold text-gray-600 uppercase tracking-widest">Expected End</div>
                            <div class="mt-2 text-sm font-bold text-white">
                                {{ $schedule->expected_end_at ? $schedule->expected_end_at->format('M d, Y H:i') : '—' }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap items-center justify-end gap-2">
                    @if($canManageSchedule)
                        @if(!$schedule->workOrders->count())
                            <form method="POST" action="{{ route('production.schedules.generate_work_orders', $schedule) }}">
                                @csrf
                                <button class="px-4 py-2 text-xs font-bold rounded-xl bg-white/[0.03] hover:bg-white/[0.08] border border-white/[0.05] text-white transition-all">
                                    Generate Work Orders
                                </button>
                            </form>
                        @endif

                        @if($schedule->status === 'planned')
                            <form method="POST" action="{{ route('production.schedules.start', $schedule) }}">
                                @csrf
                                <button class="px-4 py-2 text-xs font-bold rounded-xl bg-orange-600 hover:bg-orange-700 text-white transition-all shadow-lg shadow-orange-900/20">
                                    Start
                                </button>
                            </form>
                        @endif

                        @if($schedule->status === 'in_progress')
                            <form method="POST" action="{{ route('production.schedules.complete', $schedule) }}">
                                @csrf
                                <button class="px-4 py-2 text-xs font-bold rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white transition-all">
                                    Complete
                                </button>
                            </form>
                        @endif

                        @if($canCancelSchedule && in_array($schedule->status, ['planned','in_progress'], true))
                            <form method="POST" action="{{ route('production.schedules.cancel', $schedule) }}">
                                @csrf
                                <button class="px-4 py-2 text-xs font-bold rounded-xl bg-red-600/20 hover:bg-red-600/30 border border-red-500/30 text-red-300 transition-all">
                                    Cancel
                                </button>
                            </form>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-[#111114] border border-white/[0.05] rounded-[24px] p-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-white">MRP (Required vs Available)</h3>
                    <p class="text-sm text-gray-500 mt-1">Based on BOM × planned quantity.</p>
                </div>
            </div>

            @if(empty($mrp))
                <div class="text-sm text-gray-500">No BOM lines found for this product.</div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-separate border-spacing-y-3">
                        <thead>
                            <tr class="text-[11px] font-bold text-gray-600 uppercase tracking-widest">
                                <th class="px-6 py-4">Material</th>
                                <th class="px-6 py-4">Required</th>
                                <th class="px-6 py-4">Available</th>
                                <th class="px-6 py-4">Shortage</th>
                                <th class="px-6 py-4">Unit Cost</th>
                                <th class="px-6 py-4 text-right">Req. Cost</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mrp as $row)
                                @php
                                    $isShort = $row['shortage'] > 0;
                                @endphp
                                <tr class="bg-white/[0.02] hover:bg-white/[0.05] transition-colors group">
                                    <td class="px-6 py-5 rounded-l-2xl border-y border-l border-white/[0.05]">
                                        <div class="text-sm font-bold text-white">{{ $row['material']->material_name }}</div>
                                        <div class="text-xs text-gray-600">{{ $row['material']->material_code }}</div>
                                    </td>
                                    <td class="px-6 py-5 border-y border-white/[0.05]">
                                        <span class="text-sm font-semibold text-gray-200">{{ rtrim(rtrim(number_format($row['required'], 4), '0'), '.') }}</span>
                                        <span class="text-xs text-gray-600">{{ $row['unit'] }}</span>
                                    </td>
                                    <td class="px-6 py-5 border-y border-white/[0.05]">
                                        <span class="text-sm font-semibold text-gray-200">{{ rtrim(rtrim(number_format($row['available'], 4), '0'), '.') }}</span>
                                    </td>
                                    <td class="px-6 py-5 border-y border-white/[0.05]">
                                        <span class="text-[10px] font-bold uppercase tracking-widest px-2.5 py-1 rounded-lg border {{ $isShort ? 'text-red-300 bg-red-500/10 border-red-500/20' : 'text-emerald-300 bg-emerald-500/10 border-emerald-500/20' }}">
                                            {{ rtrim(rtrim(number_format($row['shortage'], 4), '0'), '.') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 border-y border-white/[0.05]">
                                        <span class="text-xs text-gray-400">{{ number_format($row['unit_cost'], 2) }}</span>
                                    </td>
                                    <td class="px-6 py-5 rounded-r-2xl border-y border-r border-white/[0.05] text-right">
                                        <span class="text-sm font-bold text-white">{{ number_format($row['required_cost'], 2) }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div class="bg-[#111114] border border-white/[0.05] rounded-[24px] p-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-white">Work Orders</h3>
                    <p class="text-sm text-gray-500 mt-1">Process steps for this schedule.</p>
                </div>
                <a href="{{ route('production.work_orders.index') }}" class="text-xs font-bold text-orange-500 hover:text-orange-400 uppercase tracking-widest">
                    View All Work Orders
                </a>
            </div>

            @if(!$schedule->workOrders->count())
                <div class="text-sm text-gray-500">No work orders yet.</div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-separate border-spacing-y-3">
                        <thead>
                            <tr class="text-[11px] font-bold text-gray-600 uppercase tracking-widest">
                                <th class="px-6 py-4">WO No</th>
                                <th class="px-6 py-4">Step</th>
                                <th class="px-6 py-4">Assigned</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($schedule->workOrders as $wo)
                                @php
                                    $woStyles = match($wo->status) {
                                        'pending' => 'text-gray-300 bg-white/5 border-white/10',
                                        'ongoing' => 'text-orange-400 bg-orange-500/10 border-orange-500/20',
                                        'done' => 'text-emerald-400 bg-emerald-500/10 border-emerald-500/20',
                                        'cancelled' => 'text-red-300 bg-red-500/10 border-red-500/20',
                                        default => 'text-gray-300 bg-white/5 border-white/10',
                                    };
                                @endphp
                                <tr class="bg-white/[0.02] hover:bg-white/[0.05] transition-colors group">
                                    <td class="px-6 py-5 rounded-l-2xl border-y border-l border-white/[0.05]">
                                        <div class="text-sm font-bold text-white">{{ $wo->work_order_no }}</div>
                                    </td>
                                    <td class="px-6 py-5 border-y border-white/[0.05]">
                                        <div class="text-sm font-semibold text-gray-200">{{ $wo->process_step }}</div>
                                        <div class="text-xs text-gray-600">Planned: {{ number_format($wo->planned_qty) }}</div>
                                    </td>
                                    <td class="px-6 py-5 border-y border-white/[0.05]">
                                        <div class="text-xs text-gray-400">{{ $wo->assignedTo?->name ?? '—' }}</div>
                                    </td>
                                    <td class="px-6 py-5 border-y border-white/[0.05]">
                                        <span class="text-[10px] font-bold uppercase tracking-widest px-2.5 py-1 rounded-lg border {{ $woStyles }}">
                                            {{ $wo->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 rounded-r-2xl border-y border-r border-white/[0.05] text-right">
                                        <a href="{{ route('production.work_orders.show', $wo) }}" class="px-4 py-2 text-xs font-bold rounded-xl bg-white/[0.03] hover:bg-white/[0.08] border border-white/[0.05] text-white transition-all">
                                            Open
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <div class="space-y-8">
        @if($canQc)
            <div class="bg-[#111114] border border-white/[0.05] rounded-[24px] p-8">
                <h3 class="text-lg font-bold text-white">Quality Control</h3>
                <p class="text-sm text-gray-500 mt-1">Record inspection result for this schedule.</p>
                <div class="text-xs text-gray-600 mt-2">Planned Qty: <span class="text-gray-300 font-semibold">{{ number_format($schedule->planned_quantity) }}</span></div>

                @if(!$qcReady)
                    <div class="mt-6 p-4 rounded-2xl bg-white/[0.02] border border-white/[0.05] text-sm text-gray-400">
                        QC is available only after all work orders are marked <span class="text-gray-200 font-semibold">DONE</span>.
                    </div>
                @else

                <form method="POST" action="{{ route('qc.inspections.store', $schedule) }}" class="mt-6 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Result</label>
                        <select name="result" class="w-full bg-black/20 border border-white/[0.08] rounded-2xl px-4 py-3 text-sm text-white focus:outline-none">
                            <option value="passed" @selected(old('result', 'passed') === 'passed')>Passed</option>
                            <option value="failed" @selected(old('result', 'passed') === 'failed')>Failed</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Qty Passed</label>
                            <input type="number" min="0" name="qty_passed" value="{{ old('qty_passed', $schedule->planned_quantity) }}" class="w-full bg-black/20 border border-white/[0.08] rounded-2xl px-4 py-3 text-sm text-white focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Qty Failed</label>
                            <input type="number" min="0" name="qty_failed" value="{{ old('qty_failed', 0) }}" class="w-full bg-black/20 border border-white/[0.08] rounded-2xl px-4 py-3 text-sm text-white focus:outline-none">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Remarks</label>
                        <textarea name="remarks" rows="3" class="w-full bg-black/20 border border-white/[0.08] rounded-2xl px-4 py-3 text-sm text-white focus:outline-none" placeholder="Defects / notes…">{{ old('remarks') }}</textarea>
                    </div>
                    <button class="w-full px-6 py-3 bg-orange-600 hover:bg-orange-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-orange-900/20">
                        Save QC Result
                    </button>
                </form>
                @endif

                @if($schedule->qualityChecks->count())
                    <div class="mt-8">
                        <div class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">History</div>
                        <div class="space-y-2">
                            @foreach($schedule->qualityChecks->sortByDesc('inspected_at')->take(5) as $qc)
                                @php
                                    $qcStyles = match($qc->result) {
                                        'passed' => 'text-emerald-300 bg-emerald-500/10 border-emerald-500/20',
                                        'failed' => 'text-red-300 bg-red-500/10 border-red-500/20',
                                        default => 'text-gray-300 bg-white/5 border-white/10',
                                    };
                                @endphp
                                <div class="p-4 rounded-2xl bg-white/[0.02] border border-white/[0.05]">
                                    <div class="flex items-center justify-between">
                                        <span class="text-[10px] font-bold uppercase tracking-widest px-2.5 py-1 rounded-lg border {{ $qcStyles }}">
                                            {{ strtoupper($qc->result) }}
                                        </span>
                                        <div class="text-xs text-gray-600">{{ optional($qc->inspected_at)->format('M d, Y H:i') }}</div>
                                    </div>
                                    <div class="text-xs text-gray-400 mt-1">Passed: {{ $qc->qty_passed }} • Failed: {{ $qc->qty_failed }}</div>
                                    @if($qc->remarks)
                                        <div class="text-xs text-gray-500 mt-2">{{ $qc->remarks }}</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endif

        @if($canCost)
            <div class="bg-[#111114] border border-white/[0.05] rounded-[24px] p-8">
                <h3 class="text-lg font-bold text-white">Production Costing</h3>
                <p class="text-sm text-gray-500 mt-1">Compute material cost from BOM and optional labor cost.</p>

                <form method="POST" action="{{ route('costing.compute', $schedule) }}" class="mt-6 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Labor Cost (optional)</label>
                        <input type="number" min="0" step="0.01" name="labor_cost" value="{{ old('labor_cost', $schedule->cost?->labor_cost ?? 0) }}" class="w-full bg-black/20 border border-white/[0.08] rounded-2xl px-4 py-3 text-sm text-white focus:outline-none">
                    </div>
                    <button class="w-full px-6 py-3 bg-white/[0.03] hover:bg-white/[0.08] text-white text-sm font-bold rounded-xl border border-white/[0.05] transition-all">
                        Compute / Update Cost
                    </button>
                </form>

                @if($schedule->cost)
                    <div class="mt-6 space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Material Cost</span>
                            <span class="text-white font-bold">{{ number_format($schedule->cost->material_cost, 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Labor Cost</span>
                            <span class="text-white font-bold">{{ number_format($schedule->cost->labor_cost, 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Total</span>
                            <span class="text-white font-bold">{{ number_format($schedule->cost->total_cost, 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm pt-2 border-t border-white/[0.05]">
                            <span class="text-gray-500">Cost / Unit</span>
                            <span class="text-orange-400 font-extrabold">{{ number_format($schedule->cost->cost_per_unit, 4) }}</span>
                        </div>
                        <div class="text-[11px] text-gray-600">Computed at {{ optional($schedule->cost->computed_at)->format('M d, Y H:i') }}</div>
                    </div>
                @endif
            </div>
        @endif

        @if(in_array($role, ['superadmin', 'admin', 'inventory'], true))
            <div class="bg-[#111114] border border-white/[0.05] rounded-[24px] p-8">
                <h3 class="text-lg font-bold text-white">Inventory</h3>
                <p class="text-sm text-gray-500 mt-1">Manage stock-in/stock-out movements.</p>
                <a href="{{ route('inventory.material_movements.index') }}" class="mt-6 inline-flex px-6 py-3 bg-white/[0.03] hover:bg-white/[0.08] text-white text-sm font-bold rounded-xl border border-white/[0.05] transition-all">
                    Open Material Movements
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
