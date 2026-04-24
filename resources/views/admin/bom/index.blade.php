@extends('layouts.admin')

@section('title', 'Bill of Materials (BOM)')

@section('content')
<div class="bg-[#111114] border border-white/[0.05] rounded-[24px] p-8">
	<div class="flex items-center justify-between mb-10">
		<div>
			<h2 class="text-2xl font-bold text-white tracking-tight">Bill of Materials</h2>
			<p class="text-sm text-gray-500 mt-1">Define required materials per 1 unit of finished product.</p>
		</div>
		<a href="{{ route('admin.bom.create') }}" class="px-6 py-3 bg-orange-600 hover:bg-orange-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-orange-900/20">
			Add BOM Line
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
					<th class="px-6 py-4">Product</th>
					<th class="px-6 py-4">Material</th>
					<th class="px-6 py-4">Qty / Unit</th>
					<th class="px-6 py-4">Unit</th>
					<th class="px-6 py-4 text-right">Actions</th>
				</tr>
			</thead>
			<tbody>
				@forelse($bomLines as $line)
					<tr class="bg-white/[0.02] hover:bg-white/[0.05] transition-colors group">
						<td class="px-6 py-5 rounded-l-2xl border-y border-l border-white/[0.05]">
							<div class="text-sm font-bold text-white">{{ $line->product?->product_name ?? '—' }}</div>
							<div class="text-xs text-gray-500">{{ $line->product?->product_code ?? '' }}</div>
						</td>
						<td class="px-6 py-5 border-y border-white/[0.05]">
							<div class="text-sm font-semibold text-gray-200">{{ $line->material?->material_name ?? '—' }}</div>
							<div class="text-xs text-gray-600">{{ $line->material?->material_code ?? '' }}</div>
						</td>
						<td class="px-6 py-5 border-y border-white/[0.05]">
							<div class="text-xs text-gray-300 font-semibold">{{ rtrim(rtrim(number_format((float) $line->quantity_required, 4), '0'), '.') }}</div>
						</td>
						<td class="px-6 py-5 border-y border-white/[0.05]">
							<div class="text-xs text-gray-400">{{ $line->unit ?: ($line->material?->unit ?? 'pcs') }}</div>
						</td>
						<td class="px-6 py-5 rounded-r-2xl border-y border-r border-white/[0.05] text-right">
							<div class="inline-flex items-center gap-2">
								<a href="{{ route('admin.bom.edit', $line) }}" class="px-4 py-2 text-xs font-bold rounded-xl bg-white/[0.03] hover:bg-white/[0.08] border border-white/[0.05] text-white transition-all">Edit</a>
								<form method="POST" action="{{ route('admin.bom.destroy', $line) }}" onsubmit="return confirm('Delete this BOM line?');" class="inline">
									@csrf
									@method('DELETE')
									<button type="submit" class="px-4 py-2 text-xs font-bold rounded-xl bg-red-600/20 hover:bg-red-600/30 border border-red-500/30 text-red-300 transition-all">Delete</button>
								</form>
							</div>
						</td>
					</tr>
				@empty
					<tr>
						<td colspan="5" class="px-6 py-10 text-sm text-gray-500">No BOM lines found.</td>
					</tr>
				@endforelse
			</tbody>
		</table>
	</div>

	<div class="mt-6">
		{{ $bomLines->links() }}
	</div>
</div>
@endsection
