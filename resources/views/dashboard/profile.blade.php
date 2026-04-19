@extends('layouts.admin')
@section('title', 'My Profile')

@section('content')
<div class="max-w-2xl space-y-6">

    {{-- Success message --}}
    @if (session('success'))
        <div class="bg-green-500/10 border border-green-500/20 text-green-400 text-sm px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    {{-- Profile Card --}}
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 bg-orange-500 rounded-full flex items-center justify-center text-2xl font-bold flex-shrink-0">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div>
                <div class="font-semibold text-white text-lg">{{ auth()->user()->name }}</div>
                <div class="flex items-center gap-2 mt-0.5">
                    <span class="text-gray-400 text-sm">{{ auth()->user()->email }}</span>
                    @if (auth()->user()->hasVerifiedEmail())
                        <span class="inline-flex items-center gap-1 text-xs bg-green-500/10 text-green-400 border border-green-500/20 px-2 py-0.5 rounded-full">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            Verified
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 text-xs bg-yellow-500/10 text-yellow-400 border border-yellow-500/20 px-2 py-0.5 rounded-full">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            Unverified
                        </span>
                    @endif
                </div>
                <span class="inline-block mt-1.5 text-xs bg-orange-500/10 text-orange-400 border border-orange-500/20 px-2 py-0.5 rounded-full font-medium">
                    {{ ucfirst(optional(auth()->user()->role)->role_name ?? 'N/A') }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 text-sm border-t border-gray-800 pt-4">
            <div>
                <div class="text-gray-500 text-xs mb-1">Member since</div>
                <div class="text-gray-300">{{ auth()->user()->created_at->format('F d, Y') }}</div>
            </div>
            <div>
                <div class="text-gray-500 text-xs mb-1">Last updated</div>
                <div class="text-gray-300">{{ auth()->user()->updated_at->format('F d, Y') }}</div>
            </div>
        </div>
    </div>

    {{-- Update Info --}}
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
        <h2 class="font-semibold text-white mb-4">Update Information</h2>
        <form method="POST" action="{{ route('admin.profile.update') }}" class="space-y-4">
            @csrf
            @method('PATCH')

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">Full Name</label>
                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required
                    class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
                @error('name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">Email Address</label>
                <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required
                    class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition">
                @error('email') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror

                {{-- Email verification status --}}
                @if (auth()->user()->hasVerifiedEmail())
                    <p class="text-green-400 text-xs mt-2 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        Email verified
                    </p>
                @else
                    <p class="text-yellow-400 text-xs mt-2">⚠ Your email is not yet verified.</p>
                @endif
            </div>

            <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-medium px-5 py-2.5 rounded-lg text-sm transition">
                Save Changes
            </button>
        </form>
    </div>

    {{-- Email Verification --}}
    @if (!auth()->user()->hasVerifiedEmail())
    <div class="bg-gray-900 border border-yellow-500/20 rounded-xl p-6">
        <div class="flex items-start gap-3">
            <div class="w-9 h-9 bg-yellow-500/10 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="flex-1">
                <h2 class="font-semibold text-white mb-1">Verify your email address</h2>
                <p class="text-gray-400 text-sm mb-4">Click the button below to receive a verification link at <span class="text-white font-medium">{{ auth()->user()->email }}</span>.</p>
                <form method="POST" action="{{ route('admin.profile.verify') }}">
                    @csrf
                    <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-semibold px-5 py-2.5 rounded-lg text-sm transition">
                        Send Verification Link
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- Change Password --}}
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
        <h2 class="font-semibold text-white mb-4">Change Password</h2>
        <form method="POST" action="{{ route('admin.profile.password') }}" class="space-y-4">
            @csrf
            @method('PATCH')

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">Current Password</label>
                <input type="password" name="current_password" required
                    class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
                    placeholder="••••••••">
                @error('current_password') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">New Password</label>
                <input type="password" name="password" required
                    class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
                    placeholder="••••••••">
                @error('password') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">Confirm New Password</label>
                <input type="password" name="password_confirmation" required
                    class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
                    placeholder="••••••••">
            </div>

            <button type="submit" class="bg-gray-700 hover:bg-gray-600 text-white font-medium px-5 py-2.5 rounded-lg text-sm transition">
                Update Password
            </button>
        </form>
    </div>

    {{-- Danger Zone --}}
    <div class="bg-gray-900 border border-red-500/20 rounded-xl p-6">
        <h2 class="font-semibold text-red-400 mb-1">Sign Out</h2>
        <p class="text-gray-500 text-sm mb-4">End your current session and return to the login page.</p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="bg-red-500/10 hover:bg-red-500/20 text-red-400 border border-red-500/20 font-medium px-5 py-2.5 rounded-lg text-sm transition">
                Logout
            </button>
        </form>
    </div>

</div>
@endsection
