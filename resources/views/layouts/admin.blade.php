<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') – PMES</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style type="text/tailwindcss">
        @layer base {
            body { 
                font-family: 'Plus Jakarta Sans', sans-serif; 
                @apply bg-[#0c0c0e] text-gray-200;
            }
        }
        @layer components {
            .sidebar-link { 
                @apply flex items-center gap-3 px-4 py-3 rounded-xl text-[13px] font-semibold text-gray-500 hover:text-white hover:bg-white/5 transition-all duration-200; 
            }
            .sidebar-link.active { 
                @apply text-orange-500 bg-orange-500/10 relative;
            }
            .sidebar-link.active::before {
                content: '';
                @apply absolute left-0 top-1/2 -translate-y-1/2 w-1 h-5 bg-orange-500 rounded-r-full;
            }
            .custom-scrollbar::-webkit-scrollbar {
                width: 4px;
            }
            .custom-scrollbar::-webkit-scrollbar-track {
                background: transparent;
            }
            .custom-scrollbar::-webkit-scrollbar-thumb {
                @apply bg-gray-800 rounded-full;
            }
        }
    </style>
</head>
<body class="text-gray-200">
@php
    $roleName = optional(auth()->user()->role)->role_name;
    $dashboardRoute = match ($roleName) {
        'planner' => 'planner.dashboard',
        'inventory' => 'inventory.dashboard',
        'operator' => 'operator.dashboard',
        'qc' => 'qc.dashboard',
        default => 'admin.dashboard',
    };

    $isMasterDataRole = in_array($roleName, ['superadmin', 'admin'], true);
    $canSeeAuditLogs = $roleName === 'superadmin';
    $canSeeSchedules = in_array($roleName, ['superadmin', 'admin', 'planner', 'inventory', 'qc'], true);
    $canSeeWorkOrders = in_array($roleName, ['superadmin', 'admin', 'planner', 'operator'], true);
    $canSeeCosting = in_array($roleName, ['superadmin', 'admin', 'planner'], true);
    $canSeeInventory = in_array($roleName, ['superadmin', 'admin', 'inventory'], true);
    $canSeeQc = in_array($roleName, ['superadmin', 'admin', 'qc'], true);
    $canSeeProductionProgress = in_array($roleName, ['superadmin', 'admin', 'planner'], true);
@endphp
<div class="flex h-screen overflow-hidden">

    {{-- Sidebar --}}
    <aside class="w-72 bg-[#0c0c0e] border-r border-white/[0.05] flex flex-col flex-shrink-0">
        {{-- Logo --}}
        <div class="px-8 py-10 flex items-center gap-4">
            <img src="{{ asset('build/LOGO.jpeg') }}" alt="PMES Logo" class="w-11 h-11 object-contain">
            <div>
                <div class="font-extrabold text-lg text-white tracking-tight leading-tight">PMES</div>
                <div class="text-[10px] font-bold text-gray-600 uppercase tracking-[0.2em] mt-1">MES v1.0</div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-4 space-y-1 overflow-y-auto custom-scrollbar pb-10">
            <div class="px-4 mb-4 mt-2">
                <p class="text-[11px] font-bold text-gray-700 uppercase tracking-[0.25em]">Main</p>
            </div>
            <a href="{{ route($dashboardRoute) }}" class="sidebar-link {{ request()->routeIs($dashboardRoute) ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                Dashboard
            </a>

            @if($isMasterDataRole)
                <div class="px-4 mb-4 mt-10">
                    <p class="text-[11px] font-bold text-gray-700 uppercase tracking-[0.25em]">Master Data</p>
                </div>
                <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Users
                </a>
                <a href="{{ route('admin.products.index') }}" class="sidebar-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    Products
                </a>
                <a href="{{ route('admin.materials.index') }}" class="sidebar-link {{ request()->routeIs('admin.materials.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.382-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                    Materials
                </a>
                <a href="{{ route('admin.bom.index') }}" class="sidebar-link {{ request()->routeIs('admin.bom.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    Bill of Materials
                </a>
            @endif

            <div class="px-4 mb-4 mt-10">
                <p class="text-[11px] font-bold text-gray-700 uppercase tracking-[0.25em]">Production</p>
            </div>
            @if($canSeeProductionProgress)
                <a href="{{ route('production.dashboard') }}" class="sidebar-link {{ request()->routeIs('production.dashboard') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3v18h18M7 14l3-3 3 3 6-6"/></svg>
                    Progress Dashboard
                </a>
            @endif
            @if($canSeeSchedules)
                <a href="{{ route('production.schedules.index') }}" class="sidebar-link {{ request()->routeIs('production.schedules.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16m-7 6h7"/></svg>
                    Production Schedules
                </a>
            @endif
            @if($canSeeWorkOrders)
                <a href="{{ route('production.work_orders.index') }}" class="sidebar-link {{ request()->routeIs('production.work_orders.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Work Orders
                </a>
            @endif
            @if($canSeeInventory)
                <a href="{{ route('inventory.material_movements.index') }}" class="sidebar-link {{ request()->routeIs('inventory.material_movements.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h18M3 17h18"/></svg>
                    Material Movements
                </a>
            @endif
            @if($canSeeCosting)
                <a href="{{ route('costing.index') }}" class="sidebar-link {{ request()->routeIs('costing.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                    Production Costing
                </a>
            @endif
            @if($canSeeQc)
                <a href="{{ route('qc.inspections.index') }}" class="sidebar-link {{ request()->routeIs('qc.inspections.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Quality Control
                </a>
            @endif

            <div class="px-4 mb-4 mt-10">
                <p class="text-[11px] font-bold text-gray-700 uppercase tracking-[0.25em]">System</p>
            </div>
            <a href="{{ route('profile.edit') }}" class="sidebar-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                My Profile
            </a>
            @if($canSeeAuditLogs)
                <a href="{{ route('admin.audit_logs') }}" class="sidebar-link {{ request()->routeIs('admin.audit_logs') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Audit Logs
                </a>
            @endif
        </nav>

        {{-- Sidebar Footer --}}
        <div class="p-6 border-t border-white/[0.05] bg-[#0c0c0e]">
            <div class="flex items-center gap-4 p-3 rounded-2xl bg-white/[0.03] border border-white/[0.05] group">
                <div class="w-11 h-11 bg-blue-600 rounded-xl flex items-center justify-center font-bold text-white shadow-lg shadow-blue-900/20">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-[13px] font-bold text-white truncate">{{ auth()->user()->name }}</div>
                    <div class="text-[10px] font-bold text-gray-600 truncate uppercase tracking-wider mt-0.5">{{ ucfirst(optional(auth()->user()->role)->role_name ?? 'Superadmin') }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="opacity-0 group-hover:opacity-100 transition-opacity">
                    @csrf
                    <button type="submit" class="p-2 text-gray-500 hover:text-red-400 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Main --}}
    <div class="flex-1 flex flex-col min-w-0 bg-[#0c0c0e]">
        {{-- Topbar --}}
        <header class="h-20 border-b border-gray-800/50 px-8 flex items-center justify-between flex-shrink-0">
            <h1 class="text-2xl font-bold text-white tracking-tight">@yield('title', 'Dashboard')</h1>
            <div class="flex items-center gap-4">
                <div class="hidden md:flex items-center gap-2 px-3 py-1.5 bg-orange-500/10 border border-orange-500/20 rounded-full">
                    <div class="w-1.5 h-1.5 bg-orange-500 rounded-full animate-pulse"></div>
                    <span class="text-[11px] font-bold text-orange-500 uppercase tracking-wider">
                        {{ optional(auth()->user()->role)->role_name ?? 'Superadmin' }}
                    </span>
                </div>
                <div class="text-[11px] font-medium text-gray-500 bg-gray-900 px-3 py-1.5 rounded-full border border-gray-800">
                    {{ now()->format('M d, Y') }}
                </div>
            </div>
        </header>

        {{-- Content --}}
        <main class="flex-1 p-8 overflow-y-auto custom-scrollbar">
            <div class="max-w-[1400px] mx-auto">
                @yield('content')
            </div>
        </main>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    (function () {
        if (!window.flatpickr) return;

        document.querySelectorAll('input.js-datepicker').forEach(function (el) {
            window.flatpickr(el, {
                dateFormat: 'Y-m-d',
                allowInput: false,
            });
        });

        document.querySelectorAll('input.js-datetimepicker').forEach(function (el) {
            window.flatpickr(el, {
                enableTime: true,
                time_24hr: true,
                dateFormat: 'Y-m-d H:i',
                allowInput: false,
            });
        });
    })();
</script>
</body>
</html>
