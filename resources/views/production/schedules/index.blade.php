@extends('layouts.admin')

@section('title', 'Production Schedules')

@section('content')
@php
    $role = optional(auth()->user()->role)->role_name;
    $canManage = in_array($role, ['superadmin', 'admin', 'planner'], true);
@endphp

<div class="bg-[#111114] border border-white/[0.05] rounded-[24px] p-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold text-white tracking-tight">Production Schedules</h2>
            <p class="text-sm text-gray-500 mt-1">Plan, start, and track production batches.</p>
        </div>
        @if($canManage)
            <a href="{{ route('production.schedules.create') }}" class="px-6 py-3 bg-orange-600 hover:bg-orange-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-orange-900/20">
                Create Schedule
            </a>
        @endif
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
                    <th class="px-6 py-4">Schedule</th>
                    <th class="px-6 py-4">Product</th>
                    <th class="px-6 py-4">Planned Qty</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">WO</th>
                    <th class="px-6 py-4">QC</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($schedules as $schedule)
                    @php
                        $status = $schedule->status;
                        $statusStyles = match($status) {
                            'planned' => 'text-gray-300 bg-white/5 border-white/10',
                            'in_progress' => 'text-orange-400 bg-orange-500/10 border-orange-500/20',
                            'completed' => 'text-emerald-400 bg-emerald-500/10 border-emerald-500/20',
                            'cancelled' => 'text-red-400 bg-red-500/10 border-red-500/20',
                            default => 'text-gray-300 bg-white/5 border-white/10',
                        };
                    @endphp
                    <tr class="bg-white/[0.02] hover:bg-white/[0.05] transition-colors group">
                        <td class="px-6 py-5 rounded-l-2xl border-y border-l border-white/[0.05]">
                            <div class="text-sm font-bold text-white">#{{ $schedule->id }}</div>
                            <div class="text-xs text-gray-500">{{ $schedule->schedule_date->format('M d, Y') }}</div>
                        </td>
                        <td class="px-6 py-5 border-y border-white/[0.05]">
                            <div class="text-sm font-semibold text-gray-200">{{ $schedule->product?->product_name ?? '—' }}</div>
                            <div class="text-xs text-gray-500">{{ $schedule->product?->product_code ?? '' }}</div>
                        </td>
                        <td class="px-6 py-5 border-y border-white/[0.05]">
                            <span class="text-sm font-bold text-white">{{ number_format($schedule->planned_quantity) }}</span>
                            <span class="text-xs text-gray-500">{{ $schedule->product?->unit ?? 'pcs' }}</span>
                        </td>
                        <td class="px-6 py-5 border-y border-white/[0.05]">
                            <span class="text-[10px] font-bold uppercase tracking-widest px-2.5 py-1 rounded-lg border {{ $statusStyles }}">
                                {{ str_replace('_', ' ', $status) }}
                            </span>
                        </td>
                        <td class="px-6 py-5 border-y border-white/[0.05]">
                            <span class="text-xs text-gray-400">{{ $schedule->work_orders_count }}</span>
                        </td>
                        <td class="px-6 py-5 border-y border-white/[0.05]">
                            <span class="text-xs text-gray-400">{{ $schedule->quality_checks_count }}</span>
                        </td>
                        <td class="px-6 py-5 rounded-r-2xl border-y border-r border-white/[0.05] text-right">
                            <a href="{{ route('production.schedules.show', $schedule) }}" class="px-4 py-2 text-xs font-bold rounded-xl bg-white/[0.03] hover:bg-white/[0.08] border border-white/[0.05] text-white transition-all">
                                View
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $schedules->links() }}
    </div>
</div>
@endsection
