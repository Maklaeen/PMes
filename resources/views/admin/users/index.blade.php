@extends('layouts.admin')

@section('title', 'Users Management')

@section('content')
<div class="bg-[#111114] border border-white/[0.05] rounded-[24px] p-8">
    <div class="flex items-center justify-between mb-10">
        <div>
            <h2 class="text-2xl font-bold text-white tracking-tight">System Users</h2>
            <p class="text-sm text-gray-500 mt-1">Manage and monitor all system access and roles.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="px-6 py-3 bg-orange-600 hover:bg-orange-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-orange-900/20">
            Add New User
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-separate border-spacing-y-3">
            <thead>
                <tr class="text-[11px] font-bold text-gray-600 uppercase tracking-widest">
                    <th class="px-6 py-4">User</th>
                    <th class="px-6 py-4">Role</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Joined</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr class="bg-white/[0.02] hover:bg-white/[0.05] transition-colors group">
                    <td class="px-6 py-5 rounded-l-2xl border-y border-l border-white/[0.05]">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center font-bold text-white shadow-lg shadow-blue-900/20">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                            <div>
                                <div class="text-sm font-bold text-white">{{ $user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-5 border-y border-white/[0.05]">
                        <span class="text-[10px] font-bold text-orange-500 uppercase tracking-widest bg-orange-500/5 px-2.5 py-1 rounded-lg border border-orange-500/10">
                            {{ optional($user->role)->role_name ?? 'Superadmin' }}
                        </span>
                    </td>
                    <td class="px-6 py-5 border-y border-white/[0.05]">
                        <div class="flex items-center gap-2 text-emerald-500 text-xs font-bold">
                            <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full shadow-[0_0_8px_rgba(16,185,129,0.5)]"></div>
                            Active
                        </div>
                    </td>
                    <td class="px-6 py-5 border-y border-white/[0.05]">
                        <span class="text-xs text-gray-400">{{ $user->created_at->format('M d, Y') }}</span>
                    </td>
                    <td class="px-6 py-5 rounded-r-2xl border-y border-r border-white/[0.05] text-right">
                        <div class="inline-flex items-center gap-2">
                            <a href="{{ route('admin.users.edit', $user) }}" class="px-4 py-2 text-xs font-bold rounded-xl bg-white/[0.03] hover:bg-white/[0.08] border border-white/[0.05] text-white transition-all">Edit</a>
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Delete this user?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 text-xs font-bold rounded-xl bg-red-600/20 hover:bg-red-600/30 border border-red-500/30 text-red-300 transition-all">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $users->links() }}
    </div>
</div>
@endsection
