@extends('admin.layout')
@section('pageTitle', 'Public Mixes')

@section('content')

<p class="text-sm text-slate-400 mb-5">{{ $mixes->total() }} mixes total</p>

<div class="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-slate-800 text-left">
                <th class="px-4 py-3 text-xs font-medium text-slate-500 uppercase tracking-wide">Mix</th>
                <th class="px-4 py-3 text-xs font-medium text-slate-500 uppercase tracking-wide">Author</th>
                <th class="px-4 py-3 text-xs font-medium text-slate-500 uppercase tracking-wide">Sounds</th>
                <th class="px-4 py-3 text-xs font-medium text-slate-500 uppercase tracking-wide">Likes</th>
                <th class="px-4 py-3 text-xs font-medium text-slate-500 uppercase tracking-wide">Featured</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-800">
            @foreach ($mixes as $mix)
            <tr x-data="{ featured: {{ $mix->is_featured ? 'true' : 'false' }}, visible: {{ $mix->is_public ? 'true' : 'false' }} }"
                :class="!visible ? 'opacity-50' : 'hover:bg-slate-800/50'"
                class="transition-all">
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2">
                        <p class="font-medium text-white truncate max-w-[160px]">{{ $mix->name }}</p>
                        <span x-show="!visible" class="text-[10px] text-slate-500 border border-slate-700 rounded px-1.5 py-0.5 shrink-0">Hidden</span>
                    </div>
                    @if ($mix->description)
                        <p class="text-xs text-slate-500 truncate max-w-[180px]">{{ $mix->description }}</p>
                    @endif
                </td>
                <td class="px-4 py-3 text-slate-400 text-xs">{{ $mix->user->name }}</td>
                <td class="px-4 py-3 text-slate-400">{{ $mix->sounds_count }}</td>
                <td class="px-4 py-3 text-slate-400">{{ $mix->likes_count }}</td>
                <td class="px-4 py-3">
                    <span x-show="featured" class="text-xs text-amber-400 border border-amber-800 rounded px-2 py-0.5">⭐ Featured</span>
                    <span x-show="!featured" class="text-xs text-slate-600">—</span>
                </td>
                <td class="px-4 py-3 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <button
                            @click="
                                fetch('{{ route('admin.mixes.visibility', $mix) }}', {
                                    method: 'POST',
                                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' }
                                }).then(r => r.json()).then(d => visible = d.is_public)
                            "
                            :title="visible ? 'Hide mix' : 'Show mix'"
                            class="text-xs px-3 py-1 border rounded-lg transition-colors"
                            :class="visible
                                ? 'text-slate-400 border-slate-700 hover:text-slate-200 hover:border-slate-500'
                                : 'text-cyan-400 border-cyan-800 hover:border-cyan-600'"
                            x-text="visible ? '🙈 Hide' : '👁 Show'"
                        ></button>
                        <button
                            @click="
                                fetch('{{ route('admin.mixes.featured', $mix) }}', {
                                    method: 'POST',
                                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' }
                                }).then(r => r.json()).then(d => featured = d.is_featured)
                            "
                            :title="featured ? 'Remove from featured' : 'Mark as featured'"
                            class="text-xs px-3 py-1 border rounded-lg transition-colors"
                            :class="featured
                                ? 'text-amber-400 border-amber-800 hover:border-amber-600'
                                : 'text-slate-400 border-slate-700 hover:text-amber-400 hover:border-amber-700'"
                            x-text="featured ? '★ Unfeature' : '☆ Feature'"
                        ></button>
                        <form method="POST" action="{{ route('admin.mixes.destroy', $mix) }}"
                              onsubmit="return confirm('Delete mix \'{{ addslashes($mix->name) }}\'? This cannot be undone.')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="text-xs px-3 py-1 border border-red-900 text-red-400 rounded-lg hover:border-red-700 transition-colors">
                                🗑 Delete
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $mixes->links() }}
</div>

@endsection
