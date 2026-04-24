@extends('layouts.admin')

@section('title', 'Add BOM Line')

@section('content')
<div class="max-w-3xl">
    <div class="bg-[#111114] border border-white/[0.05] rounded-[24px] p-8">
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-white tracking-tight">Add BOM Line</h2>
            <p class="text-sm text-gray-500 mt-1">Link a product to a material and define required quantity per unit.</p>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 rounded-2xl bg-red-500/10 border border-red-500/20 text-sm text-red-300">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.bom.store') }}" class="space-y-6">
            @csrf

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Product</label>
                <select name="product_id" class="w-full bg-black/20 border border-white/[0.08] rounded-2xl px-4 py-3 text-sm text-white focus:outline-none">
                    <option value="">Select product…</option>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}" @selected((string) old('product_id') === (string) $p->id)>
                            {{ $p->product_name }} ({{ $p->product_code }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Material</label>
                <select name="material_id" class="w-full bg-black/20 border border-white/[0.08] rounded-2xl px-4 py-3 text-sm text-white focus:outline-none">
                    <option value="">Select material…</option>
                    @foreach($materials as $m)
                        <option value="{{ $m->id }}" @selected((string) old('material_id') === (string) $m->id)>
                            {{ $m->material_name }} ({{ $m->material_code }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Quantity Required (per 1 unit)</label>
                    <input type="number" step="0.0001" min="0.0001" name="quantity_required" value="{{ old('quantity_required') }}" class="w-full bg-black/20 border border-white/[0.08] rounded-2xl px-4 py-3 text-sm text-white focus:outline-none" placeholder="e.g. 1">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Unit (optional override)</label>
                    <input type="text" name="unit" value="{{ old('unit') }}" class="w-full bg-black/20 border border-white/[0.08] rounded-2xl px-4 py-3 text-sm text-white focus:outline-none" placeholder="pcs">
                    <div class="mt-2 text-xs text-gray-600">Leave blank to use the material unit.</div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4">
                <a href="{{ route('admin.bom.index') }}" class="px-6 py-3 bg-white/[0.03] hover:bg-white/[0.08] text-white text-sm font-bold rounded-xl border border-white/[0.05] transition-all">Cancel</a>
                <button type="submit" class="px-6 py-3 bg-orange-600 hover:bg-orange-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-orange-900/20">Save BOM Line</button>
            </div>
        </form>
    </div>
</div>
@endsection
