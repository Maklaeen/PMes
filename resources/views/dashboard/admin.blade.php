@extends('layouts.admin')

@section('content')
<div class="grid grid-cols-4 gap-4 mb-6">
    <div class="bg-blue-500 text-white rounded-lg p-6 shadow">
        <div class="text-2xl font-bold">{{ $stats['users'] }}</div>
        <div>Users</div>
        <a href="#" class="text-sm underline">View details</a>
    </div>
    <div class="bg-green-500 text-white rounded-lg p-6 shadow">
        <div class="text-2xl font-bold">{{ $stats['products'] }}</div>
        <div>Products</div>
        <a href="#" class="text-sm underline">View details</a>
    </div>
    <div class="bg-blue-400 text-white rounded-lg p-6 shadow">
        <div class="text-2xl font-bold">{{ $stats['materials'] }}</div>
        <div>Materials</div>
        <a href="#" class="text-sm underline">View details</a>
    </div>
    <div class="bg-yellow-400 text-white rounded-lg p-6 shadow">
        <div class="text-2xl font-bold">-</div>
        <div>Work Orders</div>
        <a href="#" class="text-sm underline">View details</a>
    </div>
</div>

<div class="grid grid-cols-2 gap-4">
    <div class="bg-white rounded-lg shadow p-4">
        <div class="font-semibold mb-3">Quick Links</div>
        <ul class="space-y-2 text-sm">
            <li><a href="#" class="text-blue-600 hover:underline">→ Manage Users</a></li>
            <li><a href="#" class="text-blue-600 hover:underline">→ Manage Products</a></li>
            <li><a href="#" class="text-blue-600 hover:underline">→ Manage Materials</a></li>
            <li><a href="#" class="text-blue-600 hover:underline">→ Bill of Materials (BOM)</a></li>
            <li><a href="#" class="text-blue-600 hover:underline">→ Audit Logs</a></li>
        </ul>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <div class="font-semibold mb-3">System Info</div>
        <div class="text-sm text-gray-600 space-y-1">
            <div>Logged in as: <span class="font-medium text-gray-800">{{ auth()->user()->name }}</span></div>
            <div>Role: <span class="font-medium text-gray-800">{{ ucfirst(optional(auth()->user()->role)->role_name ?? 'N/A') }}</span></div>
            <div>Date: <span class="font-medium text-gray-800">{{ now()->format('F d, Y') }}</span></div>
        </div>
    </div>
</div>
@endsection
