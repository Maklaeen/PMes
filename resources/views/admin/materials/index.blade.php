@extends('layouts.admin')

@section('title', 'Materials Management')

@section('content')
<div class="bg-[#111114] border border-white/[0.05] rounded-[24px] p-8">
	<div class="flex items-center justify-between mb-10">
		<div>
			<h2 class="text-2xl font-bold text-white tracking-tight">Materials</h2>
			<p class="text-sm text-gray-500 mt-1">Maintain raw materials, unit costs, and on-hand stock.</p>
		</div>
		<a href="{{ route('admin.materials.create') }}" class="px-6 py-3 bg-orange-600 hover:bg-orange-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-orange-900/20">
			Add Material
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
					<th class="px-6 py-4">Material</th>
					<th class="px-6 py-4">Unit</th>
					<th class="px-6 py-4">Unit Cost</th>
					<th class="px-6 py-4">On Hand</th>
					<th class="px-6 py-4">Reorder</th>
					<th class="px-6 py-4">Status</th>
					<th class="px-6 py-4 text-right">Actions</th>
				</tr>
			</thead>
			<tbody>
				@foreach($materials as $m)
					@php
						$statusStyles = $m->status === 'active'
							? 'text-emerald-300 bg-emerald-500/10 border-emerald-500/20'
							: 'text-gray-300 bg-white/5 border-white/10';
					@endphp
					<tr class="bg-white/[0.02] hover:bg-white/[0.05] transition-colors group">
						<td class="px-6 py-5 rounded-l-2xl border-y border-l border-white/[0.05]">
							<div class="text-sm font-bold text-white">{{ $m->material_name }}</div>
							<div class="text-xs text-gray-500">{{ $m->material_code }}</div>
						</td>
						<td class="px-6 py-5 border-y border-white/[0.05]">
							<div class="text-xs text-gray-300 font-semibold">{{ $m->unit ?? 'pcs' }}</div>
						</td>
						<td class="px-6 py-5 border-y border-white/[0.05]">
							<div class="text-xs text-gray-300 font-semibold">{{ number_format((float) $m->unit_cost, 2) }}</div>
						</td>
						<td class="px-6 py-5 border-y border-white/[0.05]">
							<div class="text-xs text-gray-300 font-semibold">{{ rtrim(rtrim(number_format((float) $m->stock_quantity, 4), '0'), '.') }}</div>
						</td>
						<td class="px-6 py-5 border-y border-white/[0.05]">
							<div class="text-xs text-gray-400">{{ rtrim(rtrim(number_format((float) $m->reorder_level, 4), '0'), '.') }}</div>
						</td>
						<td class="px-6 py-5 border-y border-white/[0.05]">
							<span class="text-[10px] font-bold uppercase tracking-widest px-2.5 py-1 rounded-lg border {{ $statusStyles }}">
								{{ $m->status }}
							</span>
						</td>
						<td class="px-6 py-5 rounded-r-2xl border-y border-r border-white/[0.05] text-right">
							<div class="inline-flex items-center gap-2">
								<a href="{{ route('admin.materials.edit', $m) }}" class="px-4 py-2 text-xs font-bold rounded-xl bg-white/[0.03] hover:bg-white/[0.08] border border-white/[0.05] text-white transition-all">Edit</a>
								<form method="POST" action="{{ route('admin.materials.destroy', $m) }}" onsubmit="return confirm('Delete this material?');" class="inline">
									@csrf
									@method('DELETE')
									<button type="submit" class="px-4 py-2 text-xs font-bold rounded-xl bg-red-600/20 hover:bg-red-600/30 border border-red-500/30 text-red-300 transition-all">Delete</button>
								</form>
							</div>
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>

	<div class="mt-6">
		{{ $materials->links() }}
	</div>
</div>
@endsection
