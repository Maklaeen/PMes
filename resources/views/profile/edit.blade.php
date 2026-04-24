@extends('layouts.admin')
@section('title', 'My Profile')

@section('content')
<div class="w-full flex justify-center pt-12 pb-24">
    <div class="w-full max-w-2xl space-y-8 mx-auto">
        {{-- Success message --}}
        @if (session('status'))
            <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm px-4 py-3 rounded-xl text-center">
                {{ session('status') }}
            </div>
        @endif

        {{-- Profile Card Modernized --}}
        <div class="bg-[#111114] border border-white/[0.05] rounded-[24px] p-8 flex flex-col items-center shadow-lg">
            <div class="w-20 h-20 bg-orange-500/90 rounded-full flex items-center justify-center text-3xl font-extrabold mb-4">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>
            <div class="text-2xl font-extrabold text-white tracking-tight text-center">{{ $user->name }}</div>
            <div class="flex flex-col items-center gap-2 mt-2">
                <span class="text-gray-400 text-base text-center">{{ $user->email }}</span>
                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && $user->hasVerifiedEmail())
                    <span class="inline-flex items-center gap-1 text-xs bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 px-2 py-0.5 rounded-full">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        Verified
                    </span>
                @elseif($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail)
                    <span class="inline-flex items-center gap-1 text-xs bg-yellow-500/10 text-yellow-400 border border-yellow-500/20 px-2 py-0.5 rounded-full">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        Unverified
                    </span>
                @endif
            </div>
            <span class="inline-block mt-2 text-xs bg-orange-500/10 text-orange-400 border border-orange-500/20 px-3 py-1 rounded-full font-bold uppercase tracking-widest text-center">
                {{ ucfirst(optional($user->role)->role_name ?? 'N/A') }}
            </span>
            <div class="grid grid-cols-2 gap-6 mt-8 w-full">
                <div class="bg-[#18181c] border border-white/[0.05] rounded-2xl p-4 flex flex-col items-center">
                    <div class="text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1">Member since</div>
                    <div class="text-lg font-extrabold text-white tracking-tight">{{ $user->created_at->format('F d, Y') }}</div>
                </div>
                <div class="bg-[#18181c] border border-white/[0.05] rounded-2xl p-4 flex flex-col items-center">
                    <div class="text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1">Last updated</div>
                    <div class="text-lg font-extrabold text-white tracking-tight">{{ $user->updated_at->format('F d, Y') }}</div>
                </div>
            </div>
        </div>

        {{-- Update Info --}}
        <div class="bg-[#111114] border border-white/[0.05] rounded-[24px] p-8 shadow-lg">
            <h2 class="font-bold text-white mb-4 text-lg tracking-tight">Update Information</h2>
            @include('profile.partials.update-profile-information-form')
        </div>

        {{-- Change Password --}}
        <div class="bg-[#111114] border border-white/[0.05] rounded-[24px] p-8 shadow-lg">
            <h2 class="font-bold text-white mb-4 text-lg tracking-tight">Change Password</h2>
            @include('profile.partials.update-password-form')
        </div>

        {{-- Danger Zone --}}
        <div class="bg-[#111114] border border-red-500/20 rounded-[24px] p-8 shadow-lg">
            <h2 class="font-bold text-red-400 mb-2 text-lg tracking-tight">Delete Account</h2>
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</div>
@endsection
