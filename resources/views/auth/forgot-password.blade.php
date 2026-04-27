<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password – PMES</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-white text-gray-900 dark:bg-gray-950 dark:text-white min-h-screen flex flex-col">

    {{-- Navbar --}}
    <nav class="border-b border-gray-200 dark:border-gray-800 px-6 py-4 flex items-center justify-between max-w-7xl mx-auto w-full">
        <a href="{{ route('login') }}" class="flex items-center gap-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Login
        </a>
        <div class="flex items-center gap-3">
            <img src="{{ asset('build/LOGO.jpeg') }}" alt="PMES Logo" class="h-7 w-auto object-contain" />
            <span class="font-semibold text-gray-900 dark:text-white text-sm">PMES</span>
        </div>
    </nav>

    {{-- Form --}}
    <div class="flex-1 flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">
            <div class="text-center mb-8">
                <div class="w-12 h-12 bg-orange-500/10 border border-orange-500/20 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Forgot your password?</h1>
                <p class="text-gray-600 dark:text-gray-400 text-sm mt-2 max-w-sm mx-auto">
                    No problem. Enter your email and we'll send you a reset link.
                </p>
            </div>

            <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-8">

                @if (session('status'))
                    <div class="mb-5 bg-green-500/10 border border-green-500/20 text-green-400 text-sm px-4 py-3 rounded-lg flex items-center gap-2">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
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

                    <button type="submit"
                        class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2.5 rounded-lg transition text-sm">
                        Send Reset Link
                    </button>
                </form>
            </div>

            <p class="text-center text-gray-500 text-sm mt-6">
                Remembered your password?
                <a href="{{ route('login') }}" class="text-orange-400 hover:text-orange-300 transition font-medium">Sign in →</a>
            </p>
        </div>
    </div>

</body>
</html>
