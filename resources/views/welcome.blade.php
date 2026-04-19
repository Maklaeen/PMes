<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InkForge Solutions – Manufacturing Execution System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-950 text-white">

    {{-- Navbar --}}
    <nav class="border-b border-gray-800 px-6 py-4 flex items-center justify-between max-w-7xl mx-auto">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center font-bold text-sm">IF</div>
            <span class="font-bold text-lg">InkForge Solutions</span>
        </div>
        <div class="flex items-center gap-3">
            @auth
                <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-300 hover:text-white transition">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="text-sm text-gray-300 hover:text-white transition">Log in</a>
                <a href="{{ route('register') }}" class="text-sm bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg transition font-medium">Get Started</a>
            @endauth
        </div>
    </nav>

    {{-- Hero --}}
    <section class="max-w-7xl mx-auto px-6 py-24 text-center">
        <div class="inline-flex items-center gap-2 bg-orange-500/10 border border-orange-500/20 text-orange-400 text-xs font-medium px-3 py-1.5 rounded-full mb-6">
            🖨️ Custom T-Shirt Printing MES
        </div>
        <h1 class="text-5xl md:text-6xl font-extrabold leading-tight mb-6">
            Manage Your Print Shop<br>
            <span class="text-orange-500">From Order to Delivery</span>
        </h1>
        <p class="text-gray-400 text-lg max-w-2xl mx-auto mb-10">
            InkForge is a web-based Manufacturing Execution System built for custom t-shirt printing businesses.
            Plan production, track materials, manage work orders, and control quality — all in one place.
        </p>
        <div class="flex items-center justify-center gap-4 flex-wrap">
            <a href="{{ route('register') }}" class="bg-orange-500 hover:bg-orange-600 text-white font-semibold px-6 py-3 rounded-lg transition text-sm">
                Start for Free →
            </a>
            <a href="{{ route('login') }}" class="border border-gray-700 hover:border-gray-500 text-gray-300 hover:text-white font-medium px-6 py-3 rounded-lg transition text-sm">
                Sign In
            </a>
        </div>
    </section>

    {{-- Features --}}
    <section class="max-w-7xl mx-auto px-6 pb-24">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach([
                ['🗓️', 'Production Planning', 'Schedule production runs based on design, quantity, and delivery date.'],
                ['📦', 'Material Requirements', 'Auto-compute material needs and detect shortages before production starts.'],
                ['🔧', 'Work Order Management', 'Generate and monitor work orders from schedule to completion.'],
                ['💰', 'Production Costing', 'Compute cost per batch and generate cost summaries automatically.'],
                ['✅', 'Quality Control', 'Record and track inspection results for every production output.'],
                ['👥', 'Role-Based Access', 'Admin, Planner, Inventory, Operator, and QC roles with proper permissions.'],
            ] as [$icon, $title, $desc])
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 hover:border-orange-500/40 transition">
                <div class="text-3xl mb-3">{{ $icon }}</div>
                <h3 class="font-semibold text-white mb-2">{{ $title }}</h3>
                <p class="text-gray-400 text-sm leading-relaxed">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </section>

    {{-- Footer --}}
    <footer class="border-t border-gray-800 px-6 py-6 text-center text-gray-600 text-sm">
        © {{ date('Y') }} InkForge Solutions. Built with Laravel.
    </footer>

</body>
</html>
