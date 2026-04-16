@extends('admin.layout')
@section('pageTitle', 'Users')

@section('content')

<p class="text-sm text-slate-400 mb-5">{{ $users->count() }} users total</p>

<div class="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-slate-800 text-left">
                <th class="px-4 py-3 text-xs font-medium text-slate-500 uppercase tracking-wide">User</th>
                <th class="px-4 py-3 text-xs font-medium text-slate-500 uppercase tracking-wide">Role</th>
                <th class="px-4 py-3 text-xs font-medium text-slate-500 uppercase tracking-wide">Mixes</th>
                <th class="px-4 py-3 text-xs font-medium text-slate-500 uppercase tracking-wide">Likes</th>
                <th class="px-4 py-3 text-xs font-medium text-slate-500 uppercase tracking-wide">Joined</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-800">
            @foreach ($users as $user)
            <tr
                x-data="{ role: '{{ $user->role }}', banned: {{ $user->isBanned() ? 'true' : 'false' }} }"
                :class="banned ? 'bg-red-950/20' : 'hover:bg-slate-800/50'"
                class="transition-colors"
            >
                <td class="px-4 py-3">
                    <p class="font-medium" :class="banned ? 'text-slate-500 line-through' : 'text-white'">{{ $user->name }}</p>
                    <p class="text-xs text-slate-500">{{ $user->email }}</p>
                    <span x-show="banned" class="text-[10px] text-red-400 border border-red-900 rounded px-1.5 py-0.5 mt-0.5 inline-block">🚫 Banned</span>
                </td>
                <td class="px-4 py-3">
                    <span
                        x-text="role"
                        :class="role === 'admin' ? 'text-amber-400 border-amber-800' : 'text-slate-400 border-slate-700'"
                        class="text-xs border rounded px-2 py-0.5 capitalize"
                    ></span>
                </td>
                <td class="px-4 py-3 text-slate-400">{{ $user->mixes_count }}</td>
                <td class="px-4 py-3 text-slate-400">{{ $user->likes_count }}</td>
                <td class="px-4 py-3 text-slate-500 text-xs">{{ $user->created_at->format('d M Y') }}</td>
                <td class="px-4 py-3 text-right">
                    @if ($user->id !== auth()->id())
                    <div class="flex items-center justify-end gap-2">
                        <button
                            @click="
                                fetch('{{ route('admin.users.role', $user) }}', {
                                    method: 'POST',
                                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' }
                                }).then(r => r.json()).then(d => role = d.role)
                            "
                            :title="role === 'admin' ? 'Demote to user' : 'Promote to admin'"
                            class="text-xs px-3 py-1 border rounded-lg transition-colors"
                            :class="role === 'admin'
                                ? 'text-amber-400 border-amber-800 hover:border-amber-600'
                                : 'text-slate-400 border-slate-700 hover:text-amber-400 hover:border-amber-700'"
                            x-text="role === 'admin' ? '↓ Demote' : '↑ Promote'"
                        ></button>
                        <button
                            @click="
                                if (!confirm(banned ? 'Unban this user?' : 'Ban this user? They will be logged out immediately.')) return;
                                fetch('{{ route('admin.users.ban', $user) }}', {
                                    method: 'POST',
                                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' }
                                }).then(r => r.json()).then(d => banned = d.banned)
                            "
                            :title="banned ? 'Unban user' : 'Ban user'"
                            class="text-xs px-3 py-1 border rounded-lg transition-colors"
                            :class="banned
                                ? 'text-green-400 border-green-800 hover:border-green-600'
                                : 'text-red-400 border-red-900 hover:border-red-700'"
                            x-text="banned ? '✓ Unban' : '🚫 Ban'"
                        ></button>
                    </div>
                    @else
                    <span class="text-xs text-slate-600">You</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
