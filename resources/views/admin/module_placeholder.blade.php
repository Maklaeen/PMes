@extends('layouts.admin')

@section('title', $title ?? 'Module')

@section('content')
<div class="bg-[#111114] border border-white/[0.05] rounded-[24px] p-12 text-center">
    <div class="w-24 h-24 bg-orange-500/10 rounded-3xl flex items-center justify-center text-orange-500 border border-orange-500/10 mx-auto mb-8 shadow-2xl shadow-orange-900/10">
        <svg class="w-12 h-12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
    </div>
    <h2 class="text-3xl font-extrabold text-white tracking-tight mb-4">{{ $title ?? 'Module Under Construction' }}</h2>
    <p class="text-gray-500 max-w-lg mx-auto mb-10 leading-relaxed text-lg">
        We're currently building the specialized interface for this module. This section will feature advanced tracking and management tools specific to your MES workflow.
    </p>
    <div class="flex items-center justify-center gap-4">
        <a href="{{ route('admin.dashboard') }}" class="px-8 py-3.5 bg-white/[0.03] hover:bg-white/[0.08] text-white text-sm font-bold rounded-2xl border border-white/[0.05] transition-all">
            Back to Dashboard
        </a>
        <div class="px-6 py-3 bg-orange-600/10 border border-orange-500/20 rounded-2xl">
            <span class="text-xs font-bold text-orange-500 uppercase tracking-widest animate-pulse">Coming Soon</span>
        </div>
    </div>
</div>
@endsection
