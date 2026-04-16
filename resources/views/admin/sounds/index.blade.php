@extends('admin.layout')
@section('pageTitle', 'Sounds')

@section('content')

<div class="flex items-center justify-between mb-5">
    <p class="text-sm text-slate-400">{{ $sounds->count() }} sounds total</p>
    <a href="{{ route('admin.sounds.create') }}"
       class="text-sm font-medium bg-cyan-500 hover:bg-cyan-400 text-slate-900 px-4 py-2 rounded-lg transition-colors">
        + Add Sound
    </a>
</div>

<div class="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-slate-800 text-left">
                <th class="px-4 py-3 text-xs font-medium text-slate-500 uppercase tracking-wide">Sound</th>
                <th class="px-4 py-3 text-xs font-medium text-slate-500 uppercase tracking-wide">Category</th>
                <th class="px-4 py-3 text-xs font-medium text-slate-500 uppercase tracking-wide">Color</th>
                <th class="px-4 py-3 text-xs font-medium text-slate-500 uppercase tracking-wide">Order</th>
                <th class="px-4 py-3 text-xs font-medium text-slate-500 uppercase tracking-wide">Status</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-800">
            @forelse ($sounds as $sound)
            <tr class="hover:bg-slate-800/50 transition-colors">
                <td class="px-4 py-3">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl">{{ $sound->icon }}</span>
                        <div>
                            <p class="font-medium text-white">{{ $sound->name }}</p>
                            <p class="text-xs text-slate-500 truncate max-w-xs">{{ $sound->audio_url }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3 text-slate-400">
                    {{ $sound->category?->icon }} {{ $sound->category?->name ?? '—' }}
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2">
                        <div class="w-5 h-5 rounded-full border border-slate-700" style="background: {{ $sound->color }}"></div>
                        <span class="text-xs text-slate-500 font-mono">{{ $sound->color }}</span>
                    </div>
                </td>
                <td class="px-4 py-3 text-slate-400">{{ $sound->sort_order }}</td>
                <td class="px-4 py-3">
                    @if ($sound->is_active)
                        <span class="text-xs text-emerald-400 border border-emerald-800 rounded px-2 py-0.5">Active</span>
                    @else
                        <span class="text-xs text-slate-500 border border-slate-700 rounded px-2 py-0.5">Hidden</span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2 justify-end">
                        <a href="{{ route('admin.sounds.edit', $sound) }}"
                           class="text-xs text-slate-400 hover:text-cyan-400 transition-colors px-2.5 py-1 border border-slate-700 rounded-lg hover:border-cyan-700">
                            Edit
                        </a>
                        <form method="POST" action="{{ route('admin.sounds.destroy', $sound) }}"
                              onsubmit="return confirm('Delete {{ addslashes($sound->name) }}?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="text-xs text-red-500 hover:text-red-400 px-2.5 py-1 border border-red-900/50 rounded-lg hover:border-red-700 transition-colors">
                                Del
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-8 text-center text-slate-500">No sounds yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
