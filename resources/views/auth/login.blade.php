
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | InkForge Solutions</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-indigo-600 to-blue-400 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8">
        <div class="flex flex-col items-center mb-6">
            <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mb-2">
                <svg class="w-10 h-10 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 01-8 0m8 0a4 4 0 00-8 0m8 0V5a4 4 0 00-8 0v2m8 0v2a4 4 0 01-8 0V7"></path></svg>
            </div>
            <h1 class="text-2xl font-bold text-indigo-700">InkForge Solutions</h1>
            <p class="text-gray-500 text-sm">Custom T-Shirt Printing MES</p>
        </div>
        <!-- Session Status -->
        @if (session('status'))
            <div class="mb-4 text-green-600 text-center">
                {{ session('status') }}
            </div>
        @endif
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-gray-700 font-medium">Email</label>
                <input id="email" name="email" type="email" required autofocus class="mt-1 w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-indigo-400" value="{{ old('email') }}">
                @if ($errors->has('email'))
                    <div class="text-red-500 text-xs mt-1">{{ $errors->first('email') }}</div>
                @endif
            </div>
            <div>
                <label for="password" class="block text-gray-700 font-medium">Password</label>
                <input id="password" name="password" type="password" required class="mt-1 w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-indigo-400">
                @if ($errors->has('password'))
                    <div class="text-red-500 text-xs mt-1">{{ $errors->first('password') }}</div>
                @endif
            </div>
            <div class="flex items-center justify-between">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-400">
                    <span class="ml-2 text-sm text-gray-600">Remember me</span>
                </label>
                @if (Route::has('password.request'))
                    <a class="text-sm text-indigo-600 hover:underline" href="{{ route('password.request') }}">Forgot password?</a>
                @endif
            </div>
            <button type="submit" class="w-full py-2 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded transition">Login</button>
        </form>
    </div>
</body>
</html>
