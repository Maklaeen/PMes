@extends('layouts.admin')

@section('title', 'Work Order')

@section('content')
@php
    $role = optional(auth()->user()->role)->role_name;
    $canPlan = in_array($role, ['superadmin', 'admin', 'planner'], true);
    $canOperate = in_array($role, ['superadmin', 'admin', 'operator'], true);
    $isAdminLike = in_array($role, ['superadmin', 'admin'], true);

    $isOperator = $role === 'operator';
    $isPlanner = $role === 'planner';
    $isMine = $isOperator && ((int) $workOrder->assigned_to_user_id === (int) auth()->id());
    $canOperateThisWorkOrder = $canOperate && (!$isOperator || $isMine);
    $canCompleteThisWorkOrder = $isAdminLike || $isPlanner || ($isOperator && $isMine);

    $canClaim = $role === 'operator'
        && !$workOrder->assigned_to_user_id
        && $workOrder->status === 'pending'
        && ($workOrder->schedule?->status === 'in_progress');

    $statusStyles = match($workOrder->status) {
        'pending' => 'text-gray-300 bg-white/5 border-white/10',
        'ongoing' => 'text-orange-400 bg-orange-500/10 border-orange-500/20',
        'done' => 'text-emerald-400 bg-emerald-500/10 border-emerald-500/20',
        'cancelled' => 'text-red-300 bg-red-500/10 border-red-500/20',
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
                        <div class="text-2xl font-extrabold text-white tracking-tight">{{ $workOrder->work_order_no }}</div>
                        <span class="text-[10px] font-bold uppercase tracking-widest px-2.5 py-1 rounded-lg border {{ $statusStyles }}">
                            {{ $workOrder->status }}
                        </span>
                    </div>
                    <div class="mt-3 text-sm text-gray-400">
                        Step: <span class="font-semibold text-gray-200">{{ $workOrder->process_step }}</span>
                        <span class="text-gray-600">•</span>
                        Schedule: <a class="text-orange-400 hover:text-orange-300" href="{{ route('production.schedules.show', $workOrder->schedule) }}">#{{ $workOrder->production_schedule_id }}</a>
                    </div>
                    <div class="mt-1 text-xs text-gray-600">
                        Product: {{ $workOrder->schedule?->product?->product_name ?? '—' }}
                    </div>
                </div>

                <div class="flex flex-wrap items-center justify-end gap-2">
                    @if($canOperate)
                        @if($canClaim)
                            <form method="POST" action="{{ route('production.work_orders.claim', $workOrder) }}">
                                @csrf
                                <button class="px-4 py-2 text-xs font-bold rounded-xl bg-white/[0.03] hover:bg-white/[0.08] border border-white/[0.05] text-white transition-all">Take Work Order</button>
                            </form>
                        @endif
                        @if($canOperateThisWorkOrder && $workOrder->status === 'pending')
                            <form method="POST" action="{{ route('production.work_orders.start', $workOrder) }}">
                                @csrf
                                <button class="px-4 py-2 text-xs font-bold rounded-xl bg-orange-600 hover:bg-orange-700 text-white transition-all shadow-lg shadow-orange-900/20">Start</button>
                            </form>
                        @endif
                    @endif
                </div>
            </div>

            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="p-5 rounded-2xl bg-white/[0.02] border border-white/[0.05]">
                    <div class="text-[11px] font-bold text-gray-600 uppercase tracking-widest">Planned</div>
                    <div class="mt-2 text-3xl font-extrabold text-white tracking-tight">{{ number_format($workOrder->planned_qty) }}</div>
                </div>
                <div class="p-5 rounded-2xl bg-white/[0.02] border border-white/[0.05]">
                    <div class="text-[11px] font-bold text-gray-600 uppercase tracking-widest">Actual</div>
                    <div class="mt-2 text-3xl font-extrabold text-white tracking-tight">{{ number_format($workOrder->actual_qty) }}</div>
                </div>
                <div class="p-5 rounded-2xl bg-white/[0.02] border border-white/[0.05]">
                    <div class="text-[11px] font-bold text-gray-600 uppercase tracking-widest">Assigned To</div>
                    <div class="mt-2 text-sm font-bold text-white">{{ $workOrder->assignedTo?->name ?? '—' }}</div>
                    <div class="text-xs text-gray-600">{{ $workOrder->assignedTo?->email ?? '' }}</div>
                </div>
            </div>

            @if($canPlan)
                <div class="mt-8 pt-8 border-t border-white/[0.05]">
                    <h3 class="text-sm font-bold text-white">Assign Operator</h3>
                    <form method="POST" action="{{ route('production.work_orders.assign', $workOrder) }}" class="mt-4 flex items-center gap-3">
                        @csrf
                        <select name="assigned_to_user_id" class="flex-1 bg-black/20 border border-white/[0.08] rounded-2xl px-4 py-3 text-sm text-white focus:outline-none">
                            <option value="">Unassigned</option>
                            @foreach($availableOperators as $op)
                                <option value="{{ $op->id }}" @selected((string) old('assigned_to_user_id', (string) $workOrder->assigned_to_user_id) === (string) $op->id)>
                                    {{ $op->name }} ({{ $op->email }}){{ (int) ($op->ongoing_work_orders_count ?? 0) > 0 ? ' — busy' : '' }}
                                </option>
                            @endforeach
                        </select>
                        <button class="px-6 py-3 bg-white/[0.03] hover:bg-white/[0.08] text-white text-sm font-bold rounded-xl border border-white/[0.05] transition-all">Save</button>
                    </form>
                    <div class="text-xs text-gray-600 mt-2">Tip: Operators can also click “Take Work Order” to self-assign.</div>
                </div>
            @endif
        </div>

        @if($canCompleteThisWorkOrder)
            <div class="bg-[#111114] border border-white/[0.05] rounded-[24px] p-8">
                <h3 class="text-lg font-bold text-white">Finish Work Order</h3>
                @if($workOrder->status === 'done' && !($isAdminLike || $isPlanner))
                    <p class="text-sm text-gray-500 mt-1">This work order is already DONE. Please contact an admin/planner to adjust actual quantity.</p>
                @else
                    <p class="text-sm text-gray-500 mt-1">
                        {{ $workOrder->status === 'done' ? 'Update actual quantity (admin/planner).' : 'Set actual quantity and mark as DONE.' }}
                    </p>

                    <form method="POST" action="{{ route('production.work_orders.finish', $workOrder) }}" class="mt-6 flex items-center gap-3">
                        @csrf
                        <input type="number" min="0" name="actual_qty" value="{{ old('actual_qty', $workOrder->actual_qty) }}" class="flex-1 bg-black/20 border border-white/[0.08] rounded-2xl px-4 py-3 text-sm text-white focus:outline-none" placeholder="Actual output qty">
                        <button class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl transition-all">
                            {{ $workOrder->status === 'done' ? 'Update Qty' : 'Mark Done' }}
                        </button>
                    </form>
                @endif
            </div>

            <div class="bg-[#111114] border border-white/[0.05] rounded-[24px] p-8">
                <h3 class="text-lg font-bold text-white">Material Usage</h3>
                <p class="text-sm text-gray-500 mt-1">Record actual material usage (creates stock-out movement).</p>

                <form method="POST" action="{{ route('production.work_orders.materials.store', $workOrder) }}" class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    @csrf
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Material</label>
                        <select name="material_id" class="w-full bg-black/20 border border-white/[0.08] rounded-2xl px-4 py-3 text-sm text-white focus:outline-none">
                            <option value="">Select material…</option>
                            @foreach($materials as $m)
                                <option value="{{ $m->id }}">{{ $m->material_name }} ({{ $m->material_code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Qty</label>
                        <input type="number" min="0.0001" step="0.0001" name="quantity" class="w-full bg-black/20 border border-white/[0.08] rounded-2xl px-4 py-3 text-sm text-white focus:outline-none">
                    </div>
                    <div>
                        <button class="w-full px-6 py-3 bg-orange-600 hover:bg-orange-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-orange-900/20">Add</button>
                    </div>
                </form>

                @if($workOrder->materialMovements->count())
                    <div class="mt-8">
                        <div class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">Usage Entries</div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-separate border-spacing-y-3">
                                <thead>
                                    <tr class="text-[11px] font-bold text-gray-600 uppercase tracking-widest">
                                        <th class="px-6 py-4">Material</th>
                                        <th class="px-6 py-4">Qty</th>
                                        <th class="px-6 py-4">Type</th>
                                        <th class="px-6 py-4 text-right">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($workOrder->materialMovements->sortByDesc('id') as $mv)
                                        <tr class="bg-white/[0.02] hover:bg-white/[0.05] transition-colors group">
                                            <td class="px-6 py-5 rounded-l-2xl border-y border-l border-white/[0.05]">
                                                <div class="text-sm font-bold text-white">{{ $mv->material?->material_name ?? '—' }}</div>
                                                <div class="text-xs text-gray-600">{{ $mv->material?->material_code ?? '' }}</div>
                                            </td>
                                            <td class="px-6 py-5 border-y border-white/[0.05]">
                                                <div class="text-sm font-semibold text-gray-200">{{ rtrim(rtrim(number_format($mv->quantity, 4), '0'), '.') }} {{ $mv->unit }}</div>
                                            </td>
                                            <td class="px-6 py-5 border-y border-white/[0.05]">
                                                <div class="text-xs text-gray-400">{{ $mv->movement_type }} • {{ $mv->reference_type }}</div>
                                            </td>
                                            <td class="px-6 py-5 rounded-r-2xl border-y border-r border-white/[0.05] text-right">
                                                <div class="text-xs text-gray-600">{{ $mv->created_at->format('M d, Y H:i') }}</div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>

    <div class="space-y-8">
        <div class="bg-[#111114] border border-white/[0.05] rounded-[24px] p-8">
            <h3 class="text-lg font-bold text-white">Schedule Snapshot</h3>
            <div class="mt-6 space-y-4 text-sm">
                <div class="flex items-center justify-between">
                    <span class="text-gray-500">Schedule Date</span>
                    <span class="text-white font-bold">{{ $workOrder->schedule->schedule_date->format('M d, Y') }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-500">Schedule Status</span>
                    <span class="text-white font-bold">{{ str_replace('_', ' ', $workOrder->schedule->status) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-500">Planned Qty</span>
                    <span class="text-white font-bold">{{ number_format($workOrder->schedule->planned_quantity) }}</span>
                </div>
            </div>
        </div>

        <div class="bg-[#111114] border border-white/[0.05] rounded-[24px] p-8">
            <h3 class="text-lg font-bold text-white">Navigation</h3>
            <div class="mt-6 space-y-2">
                <a href="{{ route('production.work_orders.index') }}" class="block px-6 py-3 bg-white/[0.03] hover:bg-white/[0.08] text-white text-sm font-bold rounded-xl border border-white/[0.05] transition-all">Back to Work Orders</a>
                <a href="{{ route('production.schedules.show', $workOrder->schedule) }}" class="block px-6 py-3 bg-white/[0.03] hover:bg-white/[0.08] text-white text-sm font-bold rounded-xl border border-white/[0.05] transition-all">Open Schedule</a>
            </div>
        </div>
    </div>
</div>
@endsection
