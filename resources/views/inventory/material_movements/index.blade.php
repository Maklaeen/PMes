@extends('layouts.admin')

@section('title', 'Material Movements')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="bg-[#111114] border border-white/[0.05] rounded-[24px] p-8">
        <h2 class="text-2xl font-bold text-white tracking-tight">Stock In / Out</h2>
        <p class="text-sm text-gray-500 mt-1">Record inventory movements and keep stock accurate.</p>

        @if($errors->any())
            <div class="mt-6 p-4 rounded-2xl bg-red-500/10 border border-red-500/20 text-sm text-red-300">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('inventory.material_movements.store') }}" class="mt-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Material</label>
                <select name="material_id" class="w-full bg-black/20 border border-white/[0.08] rounded-2xl px-4 py-3 text-sm text-white focus:outline-none">
                    <option value="">Select material…</option>
                    @foreach($materials as $m)
                        <option value="{{ $m->id }}">{{ $m->material_name }} ({{ $m->material_code }})</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Type</label>
                    <select name="movement_type" class="w-full bg-black/20 border border-white/[0.08] rounded-2xl px-4 py-3 text-sm text-white focus:outline-none">
                        <option value="in">IN</option>
                        <option value="out">OUT</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Quantity</label>
                    <input type="number" min="0.0001" step="0.0001" name="quantity" class="w-full bg-black/20 border border-white/[0.08] rounded-2xl px-4 py-3 text-sm text-white focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Reference Type</label>
                <input type="text" name="reference_type" class="w-full bg-black/20 border border-white/[0.08] rounded-2xl px-4 py-3 text-sm text-white focus:outline-none" placeholder="stock_in / adjustment / production_use">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Remarks</label>
                <input type="text" name="remarks" class="w-full bg-black/20 border border-white/[0.08] rounded-2xl px-4 py-3 text-sm text-white focus:outline-none" placeholder="Optional notes…">
            </div>

            <button class="w-full px-6 py-3 bg-orange-600 hover:bg-orange-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-orange-900/20">
                Save Movement
            </button>
        </form>

        <div class="mt-8">
            <a href="{{ route('production.schedules.index') }}" class="inline-flex px-6 py-3 bg-white/[0.03] hover:bg-white/[0.08] text-white text-sm font-bold rounded-xl border border-white/[0.05] transition-all">Back to Production</a>
        </div>
    </div>

    <div class="lg:col-span-2 bg-[#111114] border border-white/[0.05] rounded-[24px] p-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h3 class="text-lg font-bold text-white">Movement History</h3>
                <p class="text-sm text-gray-500 mt-1">Latest records (auto-updates stock quantity).</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-separate border-spacing-y-3">
                <thead>
                    <tr class="text-[11px] font-bold text-gray-600 uppercase tracking-widest">
                        <th class="px-6 py-4">Material</th>
                        <th class="px-6 py-4">Type</th>
                        <th class="px-6 py-4">Qty</th>
                        <th class="px-6 py-4">Ref</th>
                        <th class="px-6 py-4">User</th>
                        <th class="px-6 py-4 text-right">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($movements as $mv)
                        @php
                            $typeStyles = $mv->movement_type === 'in'
                                ? 'text-emerald-400 bg-emerald-500/10 border-emerald-500/20'
                                : 'text-red-300 bg-red-500/10 border-red-500/20';
                        @endphp
                        <tr class="bg-white/[0.02] hover:bg-white/[0.05] transition-colors group">
                            <td class="px-6 py-5 rounded-l-2xl border-y border-l border-white/[0.05]">
                                <div class="text-sm font-bold text-white">{{ $mv->material?->material_name ?? '—' }}</div>
                                <div class="text-xs text-gray-600">{{ $mv->material?->material_code ?? '' }}</div>
                            </td>
                            <td class="px-6 py-5 border-y border-white/[0.05]">
                                <span class="text-[10px] font-bold uppercase tracking-widest px-2.5 py-1 rounded-lg border {{ $typeStyles }}">
                                    {{ $mv->movement_type }}
                                </span>
                            </td>
                            <td class="px-6 py-5 border-y border-white/[0.05]">
                                <div class="text-sm font-semibold text-gray-200">{{ rtrim(rtrim(number_format($mv->quantity, 4), '0'), '.') }} {{ $mv->unit }}</div>
                            </td>
                            <td class="px-6 py-5 border-y border-white/[0.05]">
                                <div class="text-xs text-gray-400">{{ $mv->reference_type ?? '—' }}</div>
                                @if($mv->production_schedule_id)
                                    <div class="text-xs text-gray-600">Schedule #{{ $mv->production_schedule_id }}</div>
                                @endif
                                @if($mv->work_order_id)
                                    <div class="text-xs text-gray-600">WO #{{ $mv->work_order_id }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-5 border-y border-white/[0.05]">
                                <div class="text-xs text-gray-400">{{ $mv->createdBy?->name ?? '—' }}</div>
                            </td>
                            <td class="px-6 py-5 rounded-r-2xl border-y border-r border-white/[0.05] text-right">
                                <div class="text-xs text-gray-600">{{ $mv->created_at->format('M d, Y H:i') }}</div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $movements->links() }}
        </div>
    </div>
</div>
@endsection
