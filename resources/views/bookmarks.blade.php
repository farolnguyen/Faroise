<x-app-layout>
    <x-slot name="title">Bookmarks</x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-white">Bookmarks</h1>
                <p class="text-slate-400 text-sm mt-1">Mixes you've saved for later</p>
            </div>
            <a href="{{ route('explore') }}"
               class="text-sm text-cyan-400 hover:text-cyan-300 transition-colors border border-cyan-800 rounded-lg px-4 py-2 hover:border-cyan-600">
                Explore more →
            </a>
        </div>

        @forelse ($mixes as $mix)
        <div
            x-data="{ bookmarked: true }"
            class="bg-slate-800/60 border border-slate-700 rounded-2xl p-5 mb-4 hover:border-slate-600 transition-colors"
            x-show="bookmarked"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
        >
            <div class="flex items-start justify-between gap-4">

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <h2 class="font-semibold text-white truncate">{{ $mix->name }}</h2>
                        @if ($mix->is_public)
                            <span class="text-xs text-cyan-400 border border-cyan-800 rounded px-1.5 py-0.5 shrink-0">Public</span>
                        @endif
                    </div>

                    @if ($mix->description)
                        <p class="text-sm text-slate-400 mb-2 line-clamp-1">{{ $mix->description }}</p>
                    @endif

                    <div class="flex items-center gap-3 text-xs text-slate-500">
                        <span>🎵 {{ $mix->sounds_count }} {{ Str::plural('sound', $mix->sounds_count) }}</span>
                        <span>·</span>
                        <span>by <span class="text-slate-400">{{ $mix->user->name }}</span></span>
                        <span>·</span>
                        <span>❤️ {{ $mix->likes_count }}</span>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-2 shrink-0">
                    <a href="{{ route('home') }}?mix={{ $mix->slug }}"
                       class="text-xs text-slate-400 hover:text-white transition-colors px-3 py-1.5 border border-slate-700 rounded-lg hover:border-slate-500">
                        ▶ Play
                    </a>

                    {{-- Remove Bookmark --}}
                    <button
                        @click="
                            bookmarked = false;
                            fetch('{{ route('bookmarks.mix', $mix) }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                    'Accept': 'application/json'
                                }
                            });
                        "
                        class="text-xs text-amber-400 hover:text-amber-300 transition-colors px-3 py-1.5 border border-amber-800 rounded-lg hover:border-amber-600"
                        title="Remove bookmark"
                    >
                        🔖 Remove
                    </button>
                </div>

            </div>
        </div>
        @empty
        <div class="text-center py-24 text-slate-500">
            <p class="text-5xl mb-4">🔖</p>
            <p class="text-lg mb-2">No bookmarks yet</p>
            <p class="text-sm mb-6">Browse the Explore page and bookmark mixes you like.</p>
            <a href="{{ route('explore') }}"
               class="text-sm text-cyan-400 hover:text-cyan-300 border border-cyan-800 rounded-lg px-5 py-2.5 hover:border-cyan-600 transition-colors">
                Explore mixes
            </a>
        </div>
        @endforelse

    </div>
</x-app-layout>
