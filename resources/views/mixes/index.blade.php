<x-app-layout>
    <x-slot name="title">My Mixes</x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-white">My Mixes</h1>
                <p class="text-slate-400 text-sm mt-1">Your saved sound combinations</p>
            </div>
            <a href="{{ route('home') }}"
               class="text-sm text-cyan-400 hover:text-cyan-300 transition-colors border border-cyan-800 rounded-lg px-4 py-2 hover:border-cyan-600">
                + Create New
            </a>
        </div>

        {{-- Flash message --}}
        @if (session('status'))
            <div class="mb-6 px-4 py-3 bg-slate-800 border border-slate-700 rounded-lg text-sm text-slate-300">
                {{ session('status') }}
            </div>
        @endif

        {{-- Mixes List --}}
        @forelse ($mixes as $mix)
        <div
            x-data="{
                editing: false,
                saving: false,
                name: @js($mix->name),
                description: @js($mix->description ?? ''),
                is_public: {{ $mix->is_public ? 'true' : 'false' }},
                displayName: @js($mix->name),
                displayDesc: @js($mix->description ?? ''),
                displayPublic: {{ $mix->is_public ? 'true' : 'false' }},

                async save() {
                    this.saving = true;
                    const csrf = document.querySelector('meta[name=csrf-token]').content;
                    const res = await fetch('{{ route('mixes.update', $mix) }}', {
                        method: 'PATCH',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                        body: JSON.stringify({ name: this.name, description: this.description, is_public: this.is_public }),
                    });
                    const json = await res.json();
                    if (json.success) {
                        this.displayName = json.mix.name;
                        this.displayDesc = json.mix.description ?? '';
                        this.displayPublic = json.mix.is_public;
                        this.editing = false;
                    }
                    this.saving = false;
                }
            }"
            class="bg-slate-800/60 border border-slate-700 rounded-2xl p-5 mb-4 hover:border-slate-600 transition-colors"
        >
            {{-- View Mode --}}
            <div x-show="!editing" class="flex items-start justify-between gap-4">

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <h2 class="font-semibold text-white truncate" x-text="displayName"></h2>
                        <span x-show="displayPublic" class="text-xs text-cyan-400 border border-cyan-800 rounded px-1.5 py-0.5 shrink-0">Public</span>
                        <span x-show="!displayPublic" class="text-xs text-slate-500 border border-slate-700 rounded px-1.5 py-0.5 shrink-0">Private</span>
                    </div>
                    <p x-show="displayDesc" x-text="displayDesc" class="text-sm text-slate-400 mb-2 line-clamp-1"></p>
                    <p class="text-xs text-slate-500">
                        🎵 {{ $mix->sounds_count }} {{ Str::plural('sound', $mix->sounds_count) }}
                        &nbsp;·&nbsp;
                        {{ $mix->created_at->diffForHumans() }}
                    </p>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-2 shrink-0">
                    <a href="{{ route('home') }}?mix={{ $mix->slug }}"
                       class="text-xs text-slate-400 hover:text-white transition-colors px-3 py-1.5 border border-slate-700 rounded-lg hover:border-slate-500">
                        ▶ Play
                    </a>
                    <button
                        @click="editing = true; name = displayName; description = displayDesc; is_public = displayPublic"
                        class="text-xs text-slate-400 hover:text-cyan-400 transition-colors px-3 py-1.5 border border-slate-700 rounded-lg hover:border-cyan-700">
                        ✏️ Edit
                    </button>
                    <span x-show="displayPublic">
                        <button
                            onclick="navigator.clipboard.writeText('{{ route('mixes.show', $mix->slug) }}').then(() => this.textContent = '✅').catch(() => {}); setTimeout(() => this.textContent = '🔗 Share', 1500)"
                            class="text-xs text-slate-400 hover:text-cyan-400 transition-colors px-3 py-1.5 border border-slate-700 rounded-lg hover:border-cyan-700">
                            🔗 Share
                        </button>
                    </span>
                    <form method="POST" action="{{ route('mixes.destroy', $mix) }}"
                          onsubmit="return confirm('Delete this mix?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="text-xs text-red-500 hover:text-red-400 transition-colors px-3 py-1.5 border border-red-900/50 rounded-lg hover:border-red-700">
                            🗑
                        </button>
                    </form>
                </div>
            </div>

            {{-- Edit Mode --}}
            <div x-show="editing" x-transition>
                <div class="mb-3">
                    <input type="text" x-model="name"
                           class="w-full bg-slate-700 border border-slate-600 text-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-cyan-500"
                           placeholder="Mix name" @keydown.enter="save()" @keydown.escape="editing = false">
                </div>
                <div class="mb-3">
                    <textarea x-model="description" rows="2"
                              class="w-full bg-slate-700 border border-slate-600 text-slate-100 placeholder-slate-500 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-cyan-500 resize-none"
                              placeholder="Description (optional)"></textarea>
                </div>
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm text-slate-400 cursor-pointer">
                        <button type="button" @click="is_public = !is_public"
                                class="relative inline-flex h-5 w-9 items-center rounded-full transition-colors"
                                :class="is_public ? 'bg-cyan-500' : 'bg-slate-600'">
                            <span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white shadow transition-transform"
                                  :class="is_public ? 'translate-x-4' : 'translate-x-1'"></span>
                        </button>
                        Public
                    </label>
                    <div class="flex gap-2">
                        <button @click="editing = false"
                                class="text-xs text-slate-400 px-3 py-1.5 border border-slate-600 rounded-lg hover:bg-slate-700 transition-colors">
                            Cancel
                        </button>
                        <button @click="save()" :disabled="saving || !name.trim()"
                                class="text-xs font-semibold bg-cyan-500 hover:bg-cyan-400 text-slate-900 px-4 py-1.5 rounded-lg transition-colors disabled:opacity-40">
                            <span x-show="!saving">Save</span>
                            <span x-show="saving">Saving...</span>
                        </button>
                    </div>
                </div>
            </div>

        </div>
        @empty
        <div class="text-center py-24 text-slate-500">
            <p class="text-5xl mb-4">🎵</p>
            <p class="text-lg mb-2">No mixes yet</p>
            <p class="text-sm mb-6">Play some sounds and hit "Save Mix" to create your first one.</p>
            <a href="{{ route('home') }}"
               class="text-sm text-cyan-400 hover:text-cyan-300 border border-cyan-800 rounded-lg px-5 py-2.5 hover:border-cyan-600 transition-colors">
                Start mixing
            </a>
        </div>
        @endforelse

    </div>
</x-app-layout>
