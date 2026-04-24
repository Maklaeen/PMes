@extends('layouts.admin')

@section('title', 'Work Orders')

@section('content')
@php
    $role = optional(auth()->user()->role)->role_name;
@endphp
<div class="bg-[#111114] border border-white/[0.05] rounded-[24px] p-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold text-white tracking-tight">Work Orders</h2>
            <p class="text-sm text-gray-500 mt-1">Track progress per process step.</p>
        </div>
        <a href="{{ route('production.schedules.index') }}" class="px-6 py-3 bg-white/[0.03] hover:bg-white/[0.08] text-white text-sm font-bold rounded-xl border border-white/[0.05] transition-all">
            View Schedules
        </a>
    </div>

    @if($errors->any())
        <div class="mb-6 p-4 rounded-2xl bg-red-500/10 border border-red-500/20 text-sm text-red-300">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full text-left border-separate border-spacing-y-3">
            <thead>
                <tr class="text-[11px] font-bold text-gray-600 uppercase tracking-widest">
                    <th class="px-6 py-4">WO</th>
                    <th class="px-6 py-4">Schedule</th>
                    <th class="px-6 py-4">Step</th>
                    <th class="px-6 py-4">Assigned</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Qty</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($workOrders as $wo)
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
                            <div class="text-sm font-semibold text-gray-200">
                                <a class="hover:text-white" href="{{ route('production.schedules.show', $wo->schedule) }}">Schedule #{{ $wo->production_schedule_id }}</a>
                            </div>
                            <div class="text-xs text-gray-600">{{ $wo->schedule?->product?->product_name ?? '—' }}</div>
                        </td>
                        <td class="px-6 py-5 border-y border-white/[0.05]">
                            <div class="text-sm font-semibold text-gray-200">{{ $wo->process_step }}</div>
                        </td>
                        <td class="px-6 py-5 border-y border-white/[0.05]">
                            <div class="text-xs text-gray-400">{{ $wo->assignedTo?->name ?? '—' }}</div>
                        </td>
                        <td class="px-6 py-5 border-y border-white/[0.05]">
                            <span class="text-[10px] font-bold uppercase tracking-widest px-2.5 py-1 rounded-lg border {{ $woStyles }}">
                                {{ $wo->status }}
                            </span>
                        </td>
                        <td class="px-6 py-5 border-y border-white/[0.05]">
                            <div class="text-xs text-gray-400">Planned: {{ number_format($wo->planned_qty) }}</div>
                            <div class="text-xs text-gray-500">Actual: {{ number_format($wo->actual_qty) }}</div>
                        </td>
                        <td class="px-6 py-5 rounded-r-2xl border-y border-r border-white/[0.05] text-right">
                            <div class="inline-flex items-center gap-2">
                                @if($role === 'operator' && !$wo->assigned_to_user_id && $wo->status === 'pending' && ($wo->schedule?->status === 'in_progress'))
                                    <form method="POST" action="{{ route('production.work_orders.claim', $wo) }}">
                                        @csrf
                                        <button class="px-4 py-2 text-xs font-bold rounded-xl bg-white/[0.03] hover:bg-white/[0.08] border border-white/[0.05] text-white transition-all">Claim</button>
                                    </form>
                                @endif
                                <a href="{{ route('production.work_orders.show', $wo) }}" class="px-4 py-2 text-xs font-bold rounded-xl bg-orange-600/20 hover:bg-orange-600/30 border border-orange-500/30 text-orange-200 transition-all">
                                    Open
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $workOrders->links() }}
    </div>
</div>
@endsection
