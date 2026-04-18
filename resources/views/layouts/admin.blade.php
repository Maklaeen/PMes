<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-900 text-white flex-shrink-0">
            <!-- <div class="p-4 font-bold text-xl border-b border-gray-700">v1.09</div> -->
            <nav class="mt-4">
                <ul class="space-y-2">
                    <li><a href="/admin/dashboard" class="block px-4 py-2 hover:bg-gray-800 rounded">Dashboard</a></li>
                    <li><a href="#" class="block px-4 py-2 hover:bg-gray-800 rounded">Companies</a></li>
                    <li><a href="#" class="block px-4 py-2 hover:bg-gray-800 rounded">Leads</a></li>
                    <li><a href="#" class="block px-4 py-2 hover:bg-gray-800 rounded">Opportunities</a></li>
                    <li><a href="#" class="block px-4 py-2 hover:bg-gray-800 rounded">Quotes</a></li>
                    <li><a href="#" class="block px-4 py-2 hover:bg-gray-800 rounded">Orders</a></li>
                    <li><a href="#" class="block px-4 py-2 hover:bg-gray-800 rounded">Scheduling</a></li>
                    <li><a href="#" class="block px-4 py-2 hover:bg-gray-800 rounded">Delivery notes</a></li>
                    <li><a href="#" class="block px-4 py-2 hover:bg-gray-800 rounded">Invoices</a></li>
                    <li><a href="#" class="block px-4 py-2 hover:bg-gray-800 rounded">Product</a></li>
                    <li><a href="#" class="block px-4 py-2 hover:bg-gray-800 rounded">Purchase</a></li>
                    <li><a href="#" class="block px-4 py-2 hover:bg-gray-800 rounded">Quality</a></li>
                    <li><a href="#" class="block px-4 py-2 hover:bg-gray-800 rounded">Settings</a></li>
                    <li><a href="#" class="block px-4 py-2 hover:bg-gray-800 rounded">Accounting</a></li>
                    <li><a href="#" class="block px-4 py-2 hover:bg-gray-800 rounded">Human resources</a></li>
                </ul>
            </nav>
        </aside>
        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Topbar -->
            <header class="bg-white shadow flex items-center justify-between px-6 py-4">
                <div class="flex items-center space-x-2">
                    <button class="text-gray-500 focus:outline-none md:hidden">
                        <!-- Hamburger icon -->
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <span class="font-semibold text-lg">Dashboard</span>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700">Admin</span>
                    <img src="https://ui-avatars.com/api/?name=Admin" class="w-8 h-8 rounded-full" alt="Admin Avatar">
                </div>
            </header>
            <!-- Page Content -->
            <main class="p-6 flex-1">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>