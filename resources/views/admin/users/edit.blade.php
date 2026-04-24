@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<div class="max-w-3xl">
    <div class="bg-[#111114] border border-white/[0.05] rounded-[24px] p-8">
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-white tracking-tight">Edit User</h2>
            <p class="text-sm text-gray-500 mt-1">Update account details and role.</p>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 rounded-2xl bg-red-500/10 border border-red-500/20 text-sm text-red-300">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6">
            @csrf
            @method('PATCH')

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full bg-black/20 border border-white/[0.08] rounded-2xl px-4 py-3 text-sm text-white focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full bg-black/20 border border-white/[0.08] rounded-2xl px-4 py-3 text-sm text-white focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Role</label>
                <select name="role_id" class="w-full bg-black/20 border border-white/[0.08] rounded-2xl px-4 py-3 text-sm text-white focus:outline-none">
                    <option value="">Superadmin (no role)</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" @selected((string) old('role_id', (string) $user->role_id) === (string) $role->id)>{{ $role->role_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">New Password (optional)</label>
                    <input type="password" name="password" class="w-full bg-black/20 border border-white/[0.08] rounded-2xl px-4 py-3 text-sm text-white focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="w-full bg-black/20 border border-white/[0.08] rounded-2xl px-4 py-3 text-sm text-white focus:outline-none">
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4">
                <a href="{{ route('admin.users.index') }}" class="px-6 py-3 bg-white/[0.03] hover:bg-white/[0.08] text-white text-sm font-bold rounded-xl border border-white/[0.05] transition-all">Back</a>
                <button type="submit" class="px-6 py-3 bg-orange-600 hover:bg-orange-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-orange-900/20">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
