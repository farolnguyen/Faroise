{{-- ── TOP TOOLBAR ─────────────────────────────────────── --}}
<div class="shrink-0 bg-slate-950/95 backdrop-blur border-b border-slate-800 sticky top-0 z-30">

    {{-- Row 1: Search + Category (single select) --}}
    <div class="flex items-center gap-2 px-4 pt-3 pb-2 overflow-x-auto scrollbar-none">
        <div class="relative shrink-0">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 pointer-events-none">🔍</span>
            <input type="text" x-model="soundSearch" placeholder="Search..."
                class="bg-slate-800 border border-slate-700 text-slate-100 placeholder-slate-600 rounded-full pl-9 pr-4 py-2 text-sm w-36 focus:outline-none focus:border-cyan-600 focus:w-48 transition-all duration-300">
        </div>

        <div class="w-px h-7 bg-slate-700/80 shrink-0 mx-1"></div>
        <span class="shrink-0 text-xs text-slate-600 font-medium uppercase tracking-wider">Category</span>

        <button @click="activeCategory = null"
            class="shrink-0 px-4 py-2 text-sm rounded-full border transition-all font-medium"
            :class="activeCategory === null ? 'bg-cyan-500/20 border-cyan-600 text-cyan-300' : 'border-slate-700 text-slate-500 hover:border-slate-500 hover:text-slate-300'"
        >All</button>

        @foreach ($categories as $category)
        <button @click="activeCategory = activeCategory === {{ $category->id }} ? null : {{ $category->id }}"
            class="shrink-0 px-4 py-2 text-sm rounded-full border transition-all"
            :class="activeCategory === {{ $category->id }} ? 'bg-cyan-500/20 border-cyan-600 text-cyan-300' : 'border-slate-700 text-slate-500 hover:border-slate-500 hover:text-slate-300'"
        >{{ $category->icon }} {{ $category->name }}</button>
        @endforeach
    </div>

    {{-- Row 2: Tags (multi-select) --}}
    <div class="flex items-center gap-2 px-4 pb-3 overflow-x-auto scrollbar-none">
        <span class="shrink-0 text-xs text-slate-600 font-medium uppercase tracking-wider">Tags</span>

        <button @click="activeTags = []"
            class="shrink-0 px-3 py-1.5 text-xs rounded-full border transition-all"
            :class="activeTags.length === 0 ? 'bg-purple-500/20 border-purple-600 text-purple-300' : 'border-slate-700 text-slate-500 hover:border-slate-600 hover:text-slate-400'"
        >Any</button>

        @foreach ($tags as $tag)
        <button @click="toggleTag({{ $tag->id }})"
            class="shrink-0 px-3 py-1.5 text-xs rounded-full border transition-all"
            :class="activeTags.includes({{ $tag->id }}) ? 'bg-purple-500/20 border-purple-600 text-purple-300' : 'border-slate-700 text-slate-500 hover:border-slate-600 hover:text-slate-400'"
        >#{{ $tag->name }}</button>
        @endforeach
    </div>
</div>
