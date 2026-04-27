<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-white text-gray-900 dark:bg-gray-950 dark:text-white min-h-screen flex flex-col">

    {{-- Navbar --}}
    <nav class="border-b border-gray-200 dark:border-gray-800 px-6 py-4 flex items-center justify-between max-w-7xl mx-auto w-full">
        <a href="{{ url('/') }}" class="flex items-center gap-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Home
        </a>
        <div class="flex items-center gap-3">
            <img src="{{ asset('build/LOGO.jpeg') }}" alt="PMES Logo" class="h-7 w-auto object-contain" />

        </div>
    </nav>

    

    {{-- Form --}}
    <div class="flex-1 flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">
            <div class="text-center mb-8">
                <img src="{{ asset('build/LOGO.jpeg') }}" alt="PMES Logo" class="h-12 w-auto mx-auto mb-4 object-contain" />
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Welcome back</h1>
                <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Sign in to your PMES account</p>
            </div>

            <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-8">
                @if (session('status'))
                    <div class="mb-4 text-green-400 text-sm text-center bg-green-400/10 border border-green-400/20 rounded-lg px-4 py-2">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Email address</label>
                        <input id="email" name="email" type="email" required autofocus
                            value="{{ old('email') }}"
                            class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent placeholder-gray-400 dark:placeholder-gray-500 transition"
                            placeholder="you@example.com">
                        @error('email')
                            <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Password</label>
                        <input id="password" name="password" type="password" required
                            class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent placeholder-gray-400 dark:placeholder-gray-500 transition"
                            placeholder="••••••••">
                        @error('password')
                            <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-600 bg-gray-800 text-orange-500 focus:ring-orange-500">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Remember me</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm text-orange-400 hover:text-orange-300 transition">Forgot password?</a>
                        @endif
                    </div>

                    <button type="submit"
                        class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2.5 rounded-lg transition text-sm">
                        Sign In
                    </button>
                </form>
            </div>

            <p class="text-center text-gray-500 text-sm mt-6">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-orange-400 hover:text-orange-300 transition font-medium">Create one →</a>
            </p>
        </div>
    </div>

</body>
</html>
