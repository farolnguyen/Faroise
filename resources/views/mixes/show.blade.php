<x-app-layout>
    <x-slot name="title">{{ $mix->name }}</x-slot>
    <x-slot name="description">{{ $mix->description ?: $mix->sounds->count() . ' ambient sounds by ' . $mix->user->name . ' — listen on Faroise.' }}</x-slot>

    <div
        x-data="{
            ...soundPlayer(),
            liked: {{ $liked ? 'true' : 'false' }},
            likeCount: {{ $mix->likes_count }},
            bookmarked: {{ $bookmarked ? 'true' : 'false' }},

            async toggleLike() {
                @auth
                this.liked = !this.liked;
                this.likeCount += this.liked ? 1 : -1;
                const csrf = document.querySelector('meta[name=csrf-token]').content;
                const res = await fetch('{{ route('likes.mix', $mix) }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                });
                const json = await res.json();
                this.liked = json.liked;
                this.likeCount = json.count;
                window.dispatchEvent(new CustomEvent('toast', { detail: {
                    message: json.liked ? 'Added to likes!' : 'Removed from likes',
                    type: json.liked ? 'success' : 'info'
                }}));
                @else
                window.location = '{{ route('login') }}';
                @endauth
            },

            async toggleBookmark() {
                @auth
                this.bookmarked = !this.bookmarked;
                const csrf = document.querySelector('meta[name=csrf-token]').content;
                const res = await fetch('{{ route('bookmarks.mix', $mix) }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                });
                const json = await res.json();
                this.bookmarked = json.bookmarked;
                window.dispatchEvent(new CustomEvent('toast', { detail: {
                    message: json.bookmarked ? 'Bookmarked!' : 'Bookmark removed',
                    type: json.bookmarked ? 'success' : 'info'
                }}));
                @else
                window.location = '{{ route('login') }}';
                @endauth
            },

            copyLink() {
                navigator.clipboard.writeText(window.location.href).then(() => {
                    window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Link copied!', type: 'success' } }));
                });
            },

            playAll() {
                @foreach ($mix->sounds as $sound)
                if (!this.isPlaying({{ $sound->id }})) this.toggle({{ $sound->id }}, '{{ addslashes($sound->audio_url) }}');
                @endforeach
            }
        }"
        class="max-w-3xl mx-auto px-4 sm:px-6 py-10"
    >

        {{-- Back link --}}
        <a href="{{ route('explore') }}" class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-300 transition-colors mb-8">
            ← Explore
        </a>

        {{-- Hero Header --}}
        <div class="bg-slate-800/60 border border-slate-700 rounded-2xl p-8 mb-6">
            <div class="flex items-start justify-between gap-4 flex-wrap">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-white mb-2">{{ $mix->name }}</h1>
                    @if ($mix->description)
                        <p class="text-slate-400 mb-3">{{ $mix->description }}</p>
                    @endif
                    <div class="flex items-center gap-3 text-sm text-slate-500">
                        <span>by <span class="text-slate-300 font-medium">{{ $mix->user->name }}</span></span>
                        <span>·</span>
                        <span>🎵 {{ $mix->sounds->count() }} sounds</span>
                        <span>·</span>
                        <span>{{ $mix->created_at->format('M d, Y') }}</span>
                    </div>
                </div>

                {{-- Play All Button --}}
                <button @click="playAll()"
                        class="shrink-0 flex items-center gap-2 px-6 py-3 bg-cyan-500 hover:bg-cyan-400 text-slate-900 font-semibold rounded-xl transition-colors">
                    <span x-show="activeCount() === 0">▶ Play All</span>
                    <span x-show="activeCount() > 0"><span x-text="activeCount()"></span> playing</span>
                </button>
            </div>

            {{-- Stats + Actions row --}}
            <div class="flex items-center gap-3 mt-6 pt-5 border-t border-slate-700/60 flex-wrap">
                {{-- Like --}}
                <button @click="toggleLike()"
                        class="flex items-center gap-1.5 text-sm px-3 py-1.5 rounded-lg border transition-colors"
                        :class="liked
                            ? 'text-red-400 border-red-800 bg-red-900/20'
                            : 'text-slate-400 border-slate-700 hover:border-red-800 hover:text-red-400'">
                    <span>❤️</span>
                    <span x-text="likeCount"></span>
                    <span x-text="liked ? 'Liked' : 'Like'"></span>
                </button>

                {{-- Bookmark --}}
                <button @click="toggleBookmark()"
                        class="flex items-center gap-1.5 text-sm px-3 py-1.5 rounded-lg border transition-colors"
                        :class="bookmarked
                            ? 'text-amber-400 border-amber-800 bg-amber-900/20'
                            : 'text-slate-400 border-slate-700 hover:border-amber-800 hover:text-amber-400'">
                    <span>🔖</span>
                    <span x-text="bookmarked ? 'Saved' : 'Save'"></span>
                </button>

                {{-- Share --}}
                <button @click="copyLink()"
                        class="flex items-center gap-1.5 text-sm px-3 py-1.5 rounded-lg border border-slate-700 text-slate-400 hover:border-cyan-700 hover:text-cyan-400 transition-colors">
                    🔗 Share
                </button>

                @if ($mix->user_id === auth()->id())
                <a href="{{ route('mixes.index') }}"
                   class="ml-auto text-xs text-slate-500 hover:text-slate-300 transition-colors">
                    ✏️ Edit in My Mixes →
                </a>
                @endif
            </div>
        </div>

        {{-- Sound Cards --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 mb-28">
            @foreach ($mix->sounds as $sound)
            <div
                class="relative cursor-pointer rounded-2xl p-5 border transition-all duration-300 select-none"
                :class="isPlaying({{ $sound->id }})
                    ? 'scale-[1.03]'
                    : 'bg-slate-800/60 border-slate-700/60 hover:bg-slate-800 hover:border-slate-600'"
                :style="isPlaying({{ $sound->id }})
                    ? 'background: {{ $sound->color }}1a; border-color: {{ $sound->color }}; box-shadow: 0 0 28px {{ $sound->color }}30'
                    : ''"
                @click="toggle({{ $sound->id }}, '{{ addslashes($sound->audio_url) }}')"
            >
                <div x-show="isPlaying({{ $sound->id }})"
                     class="absolute top-3 right-3 w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></div>
                <div class="text-4xl text-center mb-3 leading-none">{{ $sound->icon }}</div>
                <p class="text-sm font-medium text-center leading-tight"
                   :class="isPlaying({{ $sound->id }}) ? 'text-white' : 'text-slate-300'">
                    {{ $sound->name }}
                </p>
                <p class="text-xs text-center text-slate-500 mt-1">{{ $sound->pivot->volume }}%</p>
                <div x-show="isPlaying({{ $sound->id }})" x-transition @click.stop class="mt-3">
                    <input type="range" min="0" max="100"
                        :value="volumes[{{ $sound->id }}] ?? {{ $sound->pivot->volume }}"
                        @input="setVolume({{ $sound->id }}, $event.target.value)"
                        class="w-full h-1 rounded-full accent-cyan-400 cursor-pointer bg-slate-700">
                </div>
            </div>
            @endforeach
        </div>

        {{-- Related Mixes --}}
        @if ($related->isNotEmpty())
        <div class="mb-28">
            <h2 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-4">
                More from {{ $mix->user->name }}
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                @foreach ($related as $r)
                <a href="{{ route('mixes.show', $r->slug) }}"
                   class="block bg-slate-800/60 border border-slate-700 rounded-xl p-4 hover:border-slate-500 transition-colors">
                    <p class="font-medium text-white text-sm truncate mb-1">{{ $r->name }}</p>
                    <p class="text-xs text-slate-500">🎵 {{ $r->sounds_count }} sounds · ❤️ {{ $r->likes_count }}</p>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Bottom Bar --}}
        <div x-show="activeCount() > 0" x-transition
             class="fixed bottom-0 left-0 right-0 bg-slate-950/95 backdrop-blur-md border-t border-slate-800 px-4 py-3 z-40">
            <div class="max-w-3xl mx-auto flex items-center justify-between">
                <span class="text-sm text-slate-300">
                    <span x-text="activeCount()"></span> sounds playing
                </span>
                <button @click="stopAll()"
                        class="text-xs text-red-400 px-3 py-1.5 border border-red-900/60 rounded-lg hover:border-red-700 transition-colors">
                    ⏹ Stop All
                </button>
            </div>
        </div>

    </div>
</x-app-layout>
