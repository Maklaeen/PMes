@extends('layouts.admin')

@section('title', 'Production Costing')

@section('content')
<div class="bg-[#111114] border border-white/[0.05] rounded-[24px] p-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold text-white tracking-tight">Production Costing</h2>
            <p class="text-sm text-gray-500 mt-1">In-progress schedules and latest computed cost.</p>
        </div>
        <a href="{{ route('production.schedules.index') }}" class="px-6 py-3 bg-white/[0.03] hover:bg-white/[0.08] text-white text-sm font-bold rounded-xl border border-white/[0.05] transition-all">View Schedules</a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-separate border-spacing-y-3">
            <thead>
                <tr class="text-[11px] font-bold text-gray-600 uppercase tracking-widest">
                    <th class="px-6 py-4">Schedule</th>
                    <th class="px-6 py-4">Product</th>
                    <th class="px-6 py-4">Started</th>
                    <th class="px-6 py-4">Cost / Unit</th>
                    <th class="px-6 py-4">Computed At</th>
                    <th class="px-6 py-4 text-right">Open</th>
                </tr>
            </thead>
            <tbody>
                @foreach($schedules as $schedule)
                    @php
                        $cost = $schedule->cost;
                    @endphp
                    <tr class="bg-white/[0.02] hover:bg-white/[0.05] transition-colors group">
                        <td class="px-6 py-5 rounded-l-2xl border-y border-l border-white/[0.05]">
                            <div class="text-sm font-bold text-white">#{{ $schedule->id }}</div>
                            <div class="text-xs text-gray-500">{{ $schedule->schedule_date->format('M d, Y') }}</div>
                        </td>
                        <td class="px-6 py-5 border-y border-white/[0.05]">
                            <div class="text-sm font-semibold text-gray-200">{{ $schedule->product?->product_name ?? '—' }}</div>
                            <div class="text-xs text-gray-600">{{ $schedule->product?->product_code ?? '' }}</div>
                        </td>
                        <td class="px-6 py-5 border-y border-white/[0.05]">
                            <div class="text-xs text-gray-300 font-semibold">
                                {{ $schedule->started_at ? $schedule->started_at->format('M d, Y H:i') : '—' }}
                            </div>
                        </td>
                        <td class="px-6 py-5 border-y border-white/[0.05]">
                            <div class="text-sm font-extrabold {{ $cost ? 'text-orange-400' : 'text-gray-500' }}">
                                {{ $cost ? number_format($cost->cost_per_unit, 4) : '—' }}
                            </div>
                        </td>
                        <td class="px-6 py-5 border-y border-white/[0.05]">
                            <div class="text-xs text-gray-500">
                                {{ $cost?->computed_at ? $cost->computed_at->format('M d, Y H:i') : '—' }}
                            </div>
                        </td>
                        <td class="px-6 py-5 rounded-r-2xl border-y border-r border-white/[0.05] text-right">
                            <a href="{{ route('production.schedules.show', $schedule) }}" class="px-4 py-2 text-xs font-bold rounded-xl bg-white/[0.03] hover:bg-white/[0.08] border border-white/[0.05] text-white transition-all">Open</a>
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
