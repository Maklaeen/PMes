<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register – InkForge Solutions</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-950 min-h-screen flex flex-col">

    {{-- Navbar --}}
    <nav class="border-b border-gray-800 px-6 py-4 flex items-center justify-between max-w-7xl mx-auto w-full">
        <a href="{{ url('/') }}" class="flex items-center gap-2 text-gray-400 hover:text-white transition text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Home
        </a>
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 bg-orange-500 rounded-lg flex items-center justify-center font-bold text-xs">IF</div>
            <span class="font-semibold text-white text-sm">InkForge Solutions</span>
        </div>
    </nav>

    {{-- Form --}}
    <div class="flex-1 flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">
            <div class="text-center mb-8">
                <div class="w-12 h-12 bg-orange-500 rounded-xl flex items-center justify-center font-bold text-lg mx-auto mb-4">IF</div>
                <h1 class="text-2xl font-bold text-white">Create your account</h1>
                <p class="text-gray-400 text-sm mt-1">You'll be set up as the <span class="text-orange-400 font-medium">Admin</span> of your company</p>
            </div>

            <div class="bg-gray-900 border border-gray-800 rounded-2xl p-8">
                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-300 mb-1.5">Full Name</label>
                        <input id="name" name="name" type="text" required autofocus
                            value="{{ old('name') }}"
                            class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent placeholder-gray-500 transition"
                            placeholder="Juan dela Cruz">
                        @error('name')
                            <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-300 mb-1.5">Email address</label>
                        <input id="email" name="email" type="email" required
                            value="{{ old('email') }}"
                            class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent placeholder-gray-500 transition"
                            placeholder="you@example.com">
                        @error('email')
                            <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-300 mb-1.5">Password</label>
                        <input id="password" name="password" type="password" required
                            class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent placeholder-gray-500 transition"
                            placeholder="••••••••">
                        @error('password')
                            <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-1.5">Confirm Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                            class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent placeholder-gray-500 transition"
                            placeholder="••••••••">
                        @error('password_confirmation')
                            <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2.5 rounded-lg transition text-sm">
                        Create Account
                    </button>
                </form>
            </div>

            <p class="text-center text-gray-500 text-sm mt-6">
                Already have an account?
                <a href="{{ route('login') }}" class="text-orange-400 hover:text-orange-300 transition font-medium">Sign in →</a>
            </p>
        </div>
    </div>

</body>
</html>
