@extends('layouts.admin')

@section('title', 'Products Management')

@section('content')
<div class="bg-[#111114] border border-white/[0.05] rounded-[24px] p-8">
    <div class="flex items-center justify-between mb-10">
        <div>
            <h2 class="text-2xl font-bold text-white tracking-tight">Product Catalog</h2>
            <p class="text-sm text-gray-500 mt-1">Manage finished goods and SKU details.</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="px-6 py-3 bg-orange-600 hover:bg-orange-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-orange-900/20">
            Create Product
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($products as $product)
        <div class="bg-white/[0.02] border border-white/[0.05] rounded-3xl p-6 hover:bg-white/[0.04] transition-all group">
            <div class="flex items-start justify-between mb-6">
                <div class="w-14 h-14 bg-emerald-500/10 rounded-2xl flex items-center justify-center text-emerald-500 border border-emerald-500/10">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <div class="px-3 py-1 bg-emerald-500/5 border border-emerald-500/10 rounded-full">
                    <span class="text-[10px] font-bold text-emerald-500 uppercase tracking-widest">In Stock</span>
                </div>
            </div>
            <h3 class="text-lg font-bold text-white mb-1 group-hover:text-orange-500 transition-colors">{{ $product->product_name }}</h3>
            <p class="text-sm text-gray-500 mb-6 line-clamp-2">{{ $product->description }}</p>
            <div class="flex items-center justify-between pt-6 border-t border-white/[0.05]">
                <div class="text-xs font-bold text-gray-400 uppercase tracking-widest">Code: {{ $product->product_code }}</div>
                <a href="{{ route('admin.products.edit', $product) }}" class="text-orange-500 hover:text-orange-400 transition-colors" aria-label="Edit product">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 flex flex-col items-center justify-center text-center">
            <div class="w-20 h-20 bg-gray-900 rounded-3xl flex items-center justify-center text-gray-700 mb-6">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">No Products Found</h3>
            <p class="text-gray-500 max-w-sm">You haven't added any products to the system yet. Start by creating your first SKU.</p>
        </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $products->links() }}
    </div>
</div>
@endsection
