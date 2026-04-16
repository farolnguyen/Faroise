{{-- ── SAVE MIX MODAL ───────────────────────────────────── --}}
<div
    x-show="showSaveMix"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 flex items-center justify-center p-4"
    @click="showSaveMix = false"
>
    <div
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        class="bg-slate-800 border border-slate-700 rounded-2xl shadow-2xl w-full max-w-md p-6 max-h-[90vh] overflow-y-auto"
        @click.stop
    >
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-lg font-semibold text-white">💾 Save Your Mix</h3>
            <button @click="showSaveMix = false" class="text-slate-400 hover:text-white transition-colors text-xl leading-none">&times;</button>
        </div>

        <div class="mb-5">
            <label class="block text-xs text-slate-500 uppercase tracking-wider mb-2">Sounds <span class="text-slate-700 normal-case">(drag to reorder)</span></label>
            <ul id="sort-sounds-list" class="space-y-1.5">
                <template x-for="s in sortedSounds" :key="s.id">
                    <li class="flex items-center gap-3 bg-slate-700/60 rounded-lg px-3 py-2">
                        <span class="drag-handle cursor-grab text-slate-600 hover:text-slate-400 select-none">⠿</span>
                        <span x-text="s.icon" class="text-xl leading-none"></span>
                        <span x-text="s.name" class="flex-1 text-sm text-slate-300"></span>
                        <span x-text="s.volume + '%'" class="text-xs text-slate-500"></span>
                    </li>
                </template>
            </ul>
        </div>

        <div class="mb-4">
            <label class="block text-sm text-slate-400 mb-1">Mix name <span class="text-red-400">*</span></label>
            <input type="text" x-model="mixName" placeholder="e.g. Late night focus..."
                class="w-full bg-slate-700 border border-slate-600 text-slate-100 placeholder-slate-500 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-cyan-500"
                @keydown.enter="saveMix()">
        </div>

        <div class="mb-4">
            <label class="block text-sm text-slate-400 mb-1">Description <span class="text-slate-600">(optional)</span></label>
            <textarea x-model="mixDesc" rows="2" placeholder="What's this mix for?"
                class="w-full bg-slate-700 border border-slate-600 text-slate-100 placeholder-slate-500 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-cyan-500 resize-none"></textarea>
        </div>

        <div class="mb-6 flex items-center gap-3">
            <button type="button" @click="mixPublic = !mixPublic"
                class="relative inline-flex h-5 w-9 items-center rounded-full transition-colors"
                :class="mixPublic ? 'bg-cyan-500' : 'bg-slate-600'">
                <span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white shadow transition-transform"
                      :class="mixPublic ? 'translate-x-4' : 'translate-x-1'"></span>
            </button>
            <span class="text-sm text-slate-400">Make public</span>
        </div>

        <div class="flex gap-3">
            <button @click="showSaveMix = false"
                class="flex-1 px-4 py-2 text-sm text-slate-400 border border-slate-600 rounded-lg hover:bg-slate-700 transition-colors">Cancel</button>
            <button @click="saveMix()" :disabled="!mixName.trim() || saving"
                class="flex-1 px-4 py-2 text-sm font-semibold bg-cyan-500 hover:bg-cyan-400 text-slate-900 rounded-lg transition-colors disabled:opacity-40 disabled:cursor-not-allowed">
                <span x-show="!saving">Save Mix</span>
                <span x-show="saving">Saving...</span>
            </button>
        </div>
    </div>
</div>
