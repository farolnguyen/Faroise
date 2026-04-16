<x-app-layout>
    <x-slot name="title">Explore Mixes</x-slot>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-white mb-1">Explore Mixes</h1>
            <p class="text-slate-400 text-sm">Discover ambient sound mixes from the community</p>
        </div>

        {{-- Featured Mixes --}}
        @if ($featured->isNotEmpty() && !request('q'))
        <div class="mb-10">
            <h2 class="text-xs font-semibold text-amber-400 uppercase tracking-widest mb-4">⭐ Featured Mixes</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                @foreach ($featured as $mix)
                <div class="bg-gradient-to-br from-amber-950/40 to-slate-800/60 border border-amber-800/40 rounded-2xl p-5 hover:border-amber-700/60 transition-colors">
                    <h3 class="font-semibold text-white truncate mb-1">{{ $mix->name }}</h3>
                    @if ($mix->description)
                        <p class="text-xs text-slate-400 line-clamp-2 mb-3">{{ $mix->description }}</p>
                    @endif
                    <div class="text-xs text-slate-500 mb-3">
                        🎵 {{ $mix->sounds_count }} sounds · ❤️ {{ $mix->likes_count }}
                    </div>
                    <div class="flex items-center justify-between">
                        <a href="{{ route('user.profile', $mix->user) }}" class="text-xs text-slate-400 hover:text-slate-200 transition-colors">
                            by {{ $mix->user->name }}
                        </a>
                        <a href="{{ route('mixes.show', $mix->slug) }}"
                           class="text-xs text-amber-400 hover:text-amber-300 border border-amber-800 rounded-lg px-2.5 py-1 hover:border-amber-600 transition-colors">
                            View →
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Search --}}
        <form method="GET" action="{{ route('explore') }}" class="mb-8">
            <div class="flex gap-3 max-w-md">
                <input
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Search mixes..."
                    class="flex-1 bg-slate-800 border border-slate-700 text-slate-100 placeholder-slate-500 rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500"
                >
                <button type="submit"
                        class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-300 text-sm rounded-lg border border-slate-600 transition-colors">
                    Search
                </button>
                @if(request('q'))
                    <a href="{{ route('explore') }}"
                       class="px-4 py-2 text-slate-400 hover:text-white text-sm transition-colors">
                        Clear
                    </a>
                @endif
            </div>
        </form>

        {{-- Results count --}}
        @if(request('q'))
            <p class="text-sm text-slate-500 mb-6">
                {{ $mixes->total() }} {{ Str::plural('result', $mixes->total()) }} for
                <span class="text-slate-300">"{{ request('q') }}"</span>
            </p>
        @endif

        {{-- Mix Cards Grid --}}
        @forelse ($mixes as $mix)
        <div
            x-data="{
                liked: {{ $mix->likes->isNotEmpty() ? 'true' : 'false' }},
                likeCount: {{ $mix->likes_count }},
                bookmarked: {{ $mix->bookmarks->isNotEmpty() ? 'true' : 'false' }},
                saving: false,

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
                    window.dispatchEvent(new CustomEvent('toast', { detail: {
                        message: json.bookmarked ? 'Bookmarked!' : 'Bookmark removed',
                        type: json.bookmarked ? 'success' : 'info'
                    }}));
                    @else
                    window.location = '{{ route('login') }}';
                    @endauth
                },
            }"
            class="bg-slate-800/60 border border-slate-700 rounded-2xl p-5 mb-4 hover:border-slate-600 transition-colors"
        >
            <div class="flex items-start justify-between gap-4">

                {{-- Left: Info --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1 flex-wrap">
                        <h2 class="font-semibold text-white truncate">{{ $mix->name }}</h2>
                    </div>

                    @if ($mix->description)
                        <p class="text-sm text-slate-400 mb-2 line-clamp-1">{{ $mix->description }}</p>
                    @endif

                    <div class="flex items-center gap-3 text-xs text-slate-500">
                        <span>🎵 {{ $mix->sounds_count }} {{ Str::plural('sound', $mix->sounds_count) }}</span>
                        <span>·</span>
                        <span>by <a href="{{ route('user.profile', $mix->user) }}" class="text-slate-400 hover:text-cyan-400 transition-colors">{{ $mix->user->name }}</a></span>
                        <span>·</span>
                        <span>{{ $mix->created_at->diffForHumans() }}</span>
                    </div>
                </div>

                {{-- Right: Actions --}}
                <div class="flex items-center gap-2 shrink-0">

                    {{-- Like Button --}}
                    <button
                        @click="toggleLike()"
                        class="flex items-center gap-1.5 text-xs px-3 py-1.5 border rounded-lg transition-colors"
                        :class="liked
                            ? 'text-pink-400 border-pink-800 bg-pink-950/40 hover:border-pink-600'
                            : 'text-slate-400 border-slate-700 hover:text-pink-400 hover:border-pink-800'"
                    >
                        <span x-text="liked ? '❤️' : '🤍'"></span>
                        <span x-text="likeCount"></span>
                    </button>

                    {{-- Bookmark Button --}}
                    <button
                        @click="toggleBookmark()"
                        class="text-xs px-3 py-1.5 border rounded-lg transition-colors"
                        :class="bookmarked
                            ? 'text-amber-400 border-amber-800 bg-amber-950/40 hover:border-amber-600'
                            : 'text-slate-400 border-slate-700 hover:text-amber-400 hover:border-amber-800'"
                        :title="bookmarked ? 'Remove bookmark' : 'Bookmark this mix'"
                    >
                        <span x-text="bookmarked ? '🔖' : '📌'"></span>
                    </button>

                    {{-- Play Button --}}
                    <a href="{{ route('home') }}?mix={{ $mix->slug }}"
                       class="text-xs text-slate-400 hover:text-white transition-colors px-3 py-1.5 border border-slate-700 rounded-lg hover:border-slate-500">
                        ▶ Play
                    </a>

                    {{-- Share --}}
                    <button
                        onclick="navigator.clipboard.writeText('{{ route('mixes.show', $mix->slug) }}').then(() => this.textContent = '✅').catch(() => {}); setTimeout(() => this.textContent = '🔗', 1500)"
                        class="text-xs text-slate-400 hover:text-cyan-400 transition-colors px-3 py-1.5 border border-slate-700 rounded-lg hover:border-cyan-700"
                        title="Copy link">
                        🔗 Share
                    </button>
                </div>

            </div>
        </div>
        @empty
        <div class="text-center py-24 text-slate-500">
            <p class="text-5xl mb-4">🔍</p>
            @if(request('q'))
                <p class="text-lg">No mixes found for "{{ request('q') }}"</p>
            @else
                <p class="text-lg mb-2">No public mixes yet</p>
                <p class="text-sm mb-6">Be the first to share a mix!</p>
                <a href="{{ route('home') }}"
                   class="text-sm text-cyan-400 hover:text-cyan-300 border border-cyan-800 rounded-lg px-5 py-2.5 hover:border-cyan-600 transition-colors">
                    Start mixing
                </a>
            @endif
        </div>
        @endforelse

        {{-- Pagination --}}
        @if ($mixes->hasPages())
            <div class="mt-8">
                {{ $mixes->links() }}
            </div>
        @endif

    </div>
</x-app-layout>
