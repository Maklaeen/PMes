@extends('layouts.admin')

@section('title', 'Edit Material')

@section('content')
<div class="max-w-3xl">
    <div class="bg-[#111114] border border-white/[0.05] rounded-[24px] p-8">
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-white tracking-tight">Edit Material</h2>
            <p class="text-sm text-gray-500 mt-1">Update material details, costs, and stock levels.</p>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 rounded-2xl bg-red-500/10 border border-red-500/20 text-sm text-red-300">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.materials.update', $material) }}" class="space-y-6">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Material Code</label>
                    <input type="text" name="material_code" value="{{ old('material_code', $material->material_code) }}" class="w-full bg-black/20 border border-white/[0.08] rounded-2xl px-4 py-3 text-sm text-white focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Status</label>
                    <select name="status" class="w-full bg-black/20 border border-white/[0.08] rounded-2xl px-4 py-3 text-sm text-white focus:outline-none">
                        <option value="active" @selected(old('status', $material->status) === 'active')>Active</option>
                        <option value="inactive" @selected(old('status', $material->status) === 'inactive')>Inactive</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Material Name</label>
                <input type="text" name="material_name" value="{{ old('material_name', $material->material_name) }}" class="w-full bg-black/20 border border-white/[0.08] rounded-2xl px-4 py-3 text-sm text-white focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Description (optional)</label>
                <textarea name="description" rows="3" class="w-full bg-black/20 border border-white/[0.08] rounded-2xl px-4 py-3 text-sm text-white focus:outline-none">{{ old('description', $material->description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Unit</label>
                    <input type="text" name="unit" value="{{ old('unit', $material->unit) }}" class="w-full bg-black/20 border border-white/[0.08] rounded-2xl px-4 py-3 text-sm text-white focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Unit Cost</label>
                    <input type="number" min="0" step="0.01" name="unit_cost" value="{{ old('unit_cost', $material->unit_cost) }}" class="w-full bg-black/20 border border-white/[0.08] rounded-2xl px-4 py-3 text-sm text-white focus:outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">On-hand Stock</label>
                    <input type="number" min="0" step="0.0001" name="stock_quantity" value="{{ old('stock_quantity', $material->stock_quantity) }}" class="w-full bg-black/20 border border-white/[0.08] rounded-2xl px-4 py-3 text-sm text-white focus:outline-none">
                    <div class="mt-2 text-xs text-gray-600">Tip: For stock adjustments with traceability, use Inventory → Material Movements.</div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Reorder Level</label>
                    <input type="number" min="0" step="0.0001" name="reorder_level" value="{{ old('reorder_level', $material->reorder_level) }}" class="w-full bg-black/20 border border-white/[0.08] rounded-2xl px-4 py-3 text-sm text-white focus:outline-none">
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4">
                <a href="{{ route('admin.materials.index') }}" class="px-6 py-3 bg-white/[0.03] hover:bg-white/[0.08] text-white text-sm font-bold rounded-xl border border-white/[0.05] transition-all">Back</a>
                <button type="submit" class="px-6 py-3 bg-orange-600 hover:bg-orange-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-orange-900/20">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
