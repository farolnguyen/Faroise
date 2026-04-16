<x-app-layout>
    <x-slot name="title">{{ $user->name }}'s Profile</x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 py-12">

        {{-- Profile Header --}}
        <div class="bg-slate-800/60 border border-slate-700 rounded-2xl p-8 mb-8">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center text-2xl font-bold text-white shrink-0">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h1 class="text-xl font-bold text-white">{{ $user->name }}</h1>
                        @if ($user->role === 'admin')
                            <span class="text-xs text-amber-400 border border-amber-800 rounded px-1.5 py-0.5">Admin</span>
                        @endif
                    </div>
                    <p class="text-slate-500 text-sm mt-0.5">Member since {{ $user->created_at->format('M Y') }}</p>
                </div>
            </div>

            {{-- Stats --}}
            <div class="flex gap-6 mt-6 pt-5 border-t border-slate-700/60">
                <div class="text-center">
                    <p class="text-2xl font-bold text-white">{{ $stats['mixes'] }}</p>
                    <p class="text-xs text-slate-500 mt-0.5">Public Mixes</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-white">{{ $stats['totalLikes'] }}</p>
                    <p class="text-xs text-slate-500 mt-0.5">Total Likes</p>
                </div>
            </div>
        </div>

        {{-- Mixes List --}}
        <h2 class="text-sm font-semibold text-slate-400 uppercase tracking-wider mb-4">
            Public Mixes
        </h2>

        @forelse ($mixes as $mix)
        <div class="bg-slate-800/60 border border-slate-700 rounded-2xl p-5 mb-3 hover:border-slate-600 transition-colors">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1 min-w-0">
                    <h3 class="font-semibold text-white truncate mb-1">{{ $mix->name }}</h3>
                    @if ($mix->description)
                        <p class="text-sm text-slate-400 line-clamp-1 mb-2">{{ $mix->description }}</p>
                    @endif
                    <div class="flex items-center gap-3 text-xs text-slate-500">
                        <span>🎵 {{ $mix->sounds_count }} {{ Str::plural('sound', $mix->sounds_count) }}</span>
                        <span>·</span>
                        <span>❤️ {{ $mix->likes_count }}</span>
                        <span>·</span>
                        <span>{{ $mix->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <a href="{{ route('home') }}?mix={{ $mix->slug }}"
                       class="text-xs text-slate-400 hover:text-white px-3 py-1.5 border border-slate-700 rounded-lg hover:border-slate-500 transition-colors">
                        ▶ Play
                    </a>
                    <a href="{{ route('mixes.show', $mix->slug) }}"
                       class="text-xs text-cyan-400 hover:text-cyan-300 px-3 py-1.5 border border-cyan-800 rounded-lg hover:border-cyan-600 transition-colors">
                        View
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-16 text-slate-500">
            <p class="text-4xl mb-3">🎵</p>
            <p>{{ $user->name }} hasn't shared any public mixes yet.</p>
        </div>
        @endforelse

    </div>
</x-app-layout>
