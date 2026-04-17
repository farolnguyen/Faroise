{{-- ── BOTTOM BAR (when playing) ─────────────────────────── --}}
<div
    x-show="activeCount() > 0"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="translate-y-full opacity-0"
    x-transition:enter-end="translate-y-0 opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="translate-y-0 opacity-100"
    x-transition:leave-end="translate-y-full opacity-0"
    class="fixed bottom-0 left-0 right-0 bg-slate-950/95 backdrop-blur-md border-t border-slate-800 px-3 sm:px-5 py-2.5 sm:py-3.5 z-40"
>
    <div class="max-w-5xl mx-auto flex items-center justify-between gap-2 sm:gap-4">

        {{-- LEFT: playing + save + stop --}}
        <div class="flex items-center gap-2 sm:gap-3 min-w-0">
            {{-- Wave animation --}}
            <div class="flex gap-0.5 items-end shrink-0" style="height:22px">
                <span class="w-1.5 rounded-full bg-cyan-400 animate-[bounce_0.9s_ease-in-out_infinite]"       style="height:100%"></span>
                <span class="w-1.5 rounded-full bg-cyan-400 animate-[bounce_0.9s_ease-in-out_infinite_0.1s]"  style="height:60%"></span>
                <span class="w-1.5 rounded-full bg-cyan-400 animate-[bounce_0.9s_ease-in-out_infinite_0.25s]" style="height:80%"></span>
                <span class="w-1.5 rounded-full bg-cyan-400 animate-[bounce_0.9s_ease-in-out_infinite_0.15s]" style="height:45%"></span>
            </div>
            <span class="text-sm sm:text-base text-slate-200 font-medium shrink-0">
                <span x-text="activeCount()"></span>
                <span class="hidden sm:inline" x-text="activeCount() === 1 ? ' sound' : ' sounds'"></span>
                <span class="text-slate-500 font-normal hidden sm:inline"> playing</span>
            </span>

            <div class="w-px h-5 bg-slate-700 shrink-0"></div>
            <button @click="stopAll()"
                class="text-xs sm:text-sm text-red-400 hover:text-red-300 transition-colors px-2.5 sm:px-4 py-1.5 sm:py-2 border border-red-900/60 rounded-xl hover:border-red-600 font-medium">
                ⏹ <span class="hidden sm:inline">Stop All</span>
            </button>
            @auth
            <button @click="openSaveMix()"
                class="text-xs sm:text-sm text-slate-300 hover:text-cyan-300 transition-colors px-2.5 sm:px-4 py-1.5 sm:py-2 border border-slate-700 rounded-xl hover:border-cyan-700 font-medium">
                💾 <span class="hidden sm:inline">Save</span>
            </button>
            @else
            <a href="{{ route('login') }}"
               class="text-xs sm:text-sm text-slate-300 hover:text-cyan-300 transition-colors px-2.5 sm:px-4 py-1.5 sm:py-2 border border-slate-700 rounded-xl hover:border-cyan-700 font-medium">
                <span class="sm:hidden">Login</span><span class="hidden sm:inline">Login to Save</span>
            </a>
            @endauth

            <button @click="copyShareLink()" x-show="activeCount() > 0"
                class="hidden sm:inline-flex text-sm text-slate-400 hover:text-slate-200 transition-colors px-4 py-2 border border-slate-700 rounded-xl hover:border-slate-500 font-medium">
                🔗 Share
            </button>
        </div>

        {{-- RIGHT: timer + sleep --}}
        <div class="hidden sm:flex items-center gap-2 shrink-0">
            <button @click="showTimer = true"
                class="text-sm transition-colors px-4 py-2 border rounded-xl font-medium"
                :class="timerRunning
                    ? 'text-amber-300 border-amber-600 bg-amber-900/20'
                    : 'text-slate-400 border-slate-700 hover:text-amber-300 hover:border-amber-600'">
                <span x-show="timerRunning" x-text="'⏱ ' + timerDisplay()"></span>
                <span x-show="!timerRunning">⏱ Timer</span>
            </button>
            <button @click="showSleepPanel = !showSleepPanel"
                class="text-sm transition-colors px-4 py-2 border rounded-xl font-medium"
                :class="showSleepPanel
                    ? 'text-indigo-300 border-indigo-600 bg-indigo-900/20'
                    : 'text-slate-400 border-slate-700 hover:text-indigo-300 hover:border-indigo-600'">
                🌑 Sleep
            </button>
        </div>
    </div>

    {{-- Sleep Screen Panel --}}
    <div
        x-show="showSleepPanel"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        class="max-w-4xl mx-auto mt-3 pt-3 border-t border-slate-800 flex items-center gap-4 flex-wrap"
        @click.stop
    >
        <span class="text-xs text-slate-500 shrink-0">Color:</span>

        {{-- Preset swatches --}}
        <div class="flex gap-2 items-center">
            <template x-for="c in sleepPresets" :key="c">
                <button
                    @click="sleepColor = c"
                    class="w-6 h-6 rounded-full border-2 transition-all hover:scale-110"
                    :style="`background: ${c}`"
                    :class="sleepColor === c ? 'border-cyan-400 scale-110' : 'border-slate-700'"
                ></button>
            </template>
            <input type="color" x-model="sleepColor"
                   class="w-6 h-6 rounded-full cursor-pointer border-2 border-slate-700 bg-transparent"
                   title="Custom color">
        </div>

        <div class="flex items-center gap-2 ml-auto">
            <button
                @click="openSleepWindow()"
                class="text-xs text-slate-400 hover:text-slate-200 px-3 py-1.5 border border-slate-700 rounded-lg hover:border-slate-500 transition-colors"
                title="Open in new window (for 2nd monitor)">
                ↗ New window
            </button>
            <button
                @click="enterSleepMode()"
                class="text-xs text-indigo-300 hover:text-white px-3 py-1.5 bg-indigo-900/40 border border-indigo-700 rounded-lg hover:bg-indigo-800/60 transition-colors">
                🌑 Enter Sleep Mode
            </button>
        </div>
    </div>

</div>
