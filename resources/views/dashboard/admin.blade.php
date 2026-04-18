@extends('layouts.admin')

@section('content')
<div class="grid grid-cols-4 gap-4 mb-6">
    <div class="bg-blue-500 text-white rounded-lg p-6 shadow">
        <div class="text-2xl font-bold">-</div>
        <div>Clients</div>
        <a href="#" class="text-sm underline">View details</a>
    </div>
    <div class="bg-green-500 text-white rounded-lg p-6 shadow">
        <div class="text-2xl font-bold">-</div>
        <div>Suppliers</div>
        <a href="#" class="text-sm underline">View details</a>
    </div>
    <div class="bg-blue-400 text-white rounded-lg p-6 shadow">
        <div class="text-2xl font-bold">-</div>
        <div>Quote</div>
        <a href="#" class="text-sm underline">View details</a>
    </div>
    <div class="bg-yellow-400 text-white rounded-lg p-6 shadow">
        <div class="text-2xl font-bold">-</div>
        <div>Orders</div>
        <a href="#" class="text-sm underline">View details</a>
    </div>
</div>

<div class="bg-white rounded-lg shadow p-4 mb-6">
    <div class="flex items-center mb-2">
        <span class="text-yellow-600 mr-2">&#9888;</span>
        <span class="font-semibold">Order to be delivered (sample data)</span>
    </div>
    <div class="flex flex-wrap gap-2">
        <span class="bg-yellow-400 text-white px-3 py-1 rounded">OR-2939<br><span class="text-xs">2024-06-10</span></span>
        <span class="bg-yellow-400 text-white px-3 py-1 rounded">OR-2052<br><span class="text-xs">2024-06-10</span></span>
        <span class="bg-yellow-400 text-white px-3 py-1 rounded">OR-7177<br><span class="text-xs">2024-06-10</span></span>
        <span class="bg-red-500 text-white px-3 py-1 rounded">INT-502<br><span class="text-xs">2024-05-31</span></span>
        <span class="bg-red-500 text-white px-3 py-1 rounded">OR-7281<br><span class="text-xs">2024-05-29</span></span>
        <!-- Add more as needed -->
    </div>
</div>

<div class="grid grid-cols-2 gap-4">
    <div class="bg-white rounded-lg shadow p-4">
        <div class="font-semibold mb-2">Latest Orders (sample data)</div>
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left border-b">
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Status</th>
                    <th>Total price</th>
                    <th>Created at</th>
                    <th>Assigned</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><span class="bg-blue-600 text-white px-2 py-1 rounded">INT-STOCK-07-06-24</span></td>
                    <td>Internal order</td>
                    <td><span class="bg-green-200 text-green-800 px-2 py-1 rounded">Open</span></td>
                    <td>410.939 EUR</td>
                    <td>1 day ago</td>
                    <td><span class="bg-green-400 text-white px-2 py-1 rounded">Ad</span></td>
                </tr>
                <tr>
                    <td><span class="bg-blue-600 text-white px-2 py-1 rounded">OR-3009</span></td>
                    <td>Sadie Dietrich</td>
                    <td><span class="bg-green-200 text-green-800 px-2 py-1 rounded">Open</span></td>
                    <td>24086.864 EUR</td>
                    <td>1 day ago</td>
                    <td><span class="bg-green-400 text-white px-2 py-1 rounded">Ad</span></td>
                </tr>
                <!-- Add more rows as needed -->
            </tbody>
        </table>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <div class="font-semibold mb-2">Monthly Recap Report</div>
        <!-- Placeholder for chart -->
        <div class="h-48 flex items-center justify-center text-gray-400">[Chart Here]</div>
        <div class="flex justify-between mt-4 text-xs text-gray-600">
            <div>
                <div class="font-bold">398120844.60 EUR</div>
                <div>Total Order Forecasted</div>
            </div>
            <div>
                <div class="font-bold">403947.39 EUR</div>
                <div>Total Order Delivered</div>
            </div>
            <div>
                <div class="font-bold">407619.45 EUR</div>
                <div>Total Invoiced</div>
            </div>
            <div>
                <div class="font-bold">407619.45 / 18000 (2264.55%)</div>
                <div>Goal Completions</div>
            </div>
        </div>
    </div>
</div>
@endsection