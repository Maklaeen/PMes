@extends('layouts.admin')

@section('title', 'Create Production Schedule')

@section('content')
<div class="max-w-3xl">
    <div class="bg-[#111114] border border-white/[0.05] rounded-[24px] p-8">
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-white tracking-tight">Create Schedule</h2>
            <p class="text-sm text-gray-500 mt-1">Plan a production batch for a product and target date.</p>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 rounded-2xl bg-red-500/10 border border-red-500/20 text-sm text-red-300">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('production.schedules.store') }}" class="space-y-6">
            @csrf

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Product</label>
                <select name="product_id" class="w-full bg-black/20 border border-white/[0.08] rounded-2xl px-4 py-3 text-sm text-white focus:outline-none">
                    <option value="">Select product…</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" @selected(old('product_id') == $product->id)>
                            {{ $product->product_name }} ({{ $product->product_code }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Planned Quantity</label>
                    <input type="number" min="1" name="planned_quantity" value="{{ old('planned_quantity') }}" class="w-full bg-black/20 border border-white/[0.08] rounded-2xl px-4 py-3 text-sm text-white focus:outline-none" placeholder="e.g. 100">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Schedule Date</label>
                    <input type="text" name="schedule_date" value="{{ old('schedule_date', now()->toDateString()) }}" class="js-datepicker w-full bg-black/20 border border-white/[0.08] rounded-2xl px-4 py-3 text-sm text-white focus:outline-none" placeholder="Select date…" autocomplete="off">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Expected End (optional)</label>
                <input type="text" name="expected_end_at" value="{{ old('expected_end_at', now()->addDay()->format('Y-m-d H:i')) }}" class="js-datetimepicker w-full bg-black/20 border border-white/[0.08] rounded-2xl px-4 py-3 text-sm text-white focus:outline-none" placeholder="Select date & time…" autocomplete="off">
                <div class="mt-2 text-xs text-gray-600">Used for tracking target completion time on the progress dashboard.</div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4">
                <a href="{{ route('production.schedules.index') }}" class="px-6 py-3 bg-white/[0.03] hover:bg-white/[0.08] text-white text-sm font-bold rounded-xl border border-white/[0.05] transition-all">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-3 bg-orange-600 hover:bg-orange-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-orange-900/20">
                    Save Schedule
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
