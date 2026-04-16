{{-- ── TIMER MODAL ──────────────────────────────────────── --}}
<div
    x-show="showTimer"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 flex items-center justify-center p-4"
    @click="showTimer = false"
>
    <div
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        class="bg-slate-800 border border-slate-700 rounded-2xl shadow-2xl w-full max-w-md p-6"
        @click.stop
    >
        {{-- Header + close --}}
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-lg font-semibold text-white">⏱ Sleep Timer</h3>
            <button @click="showTimer = false" class="text-slate-400 hover:text-white transition-colors text-xl leading-none">&times;</button>
        </div>

        <div>
            {{-- Countdown --}}
            <div class="text-center mb-5">
                <p class="font-mono font-bold text-white mb-3 tabular-nums" style="font-size:3rem" x-text="timerDisplay()">01:00:00</p>
                <div class="w-full bg-slate-700 rounded-full overflow-hidden" style="height:5px">
                    <div class="bg-amber-400 h-full rounded-full transition-all duration-1000"
                         :style="`width: ${timerProgress()}%`"></div>
                </div>
            </div>

            {{-- Preset pills (always visible, disabled while running) --}}
            <div class="mb-4">
                <label class="block text-xs text-slate-500 uppercase tracking-wider mb-2">Quick set</label>
                <div class="flex gap-2">
                    @foreach ([60 => '1h', 540 => '9h', 720 => '12h'] as $min => $label)
                    <button @click="setPreset({{ $min }})" :disabled="timerRunning"
                        class="flex-1 py-2 text-sm font-semibold border rounded-xl transition-all disabled:opacity-30 disabled:cursor-not-allowed"
                        :class="timerPreset === {{ $min }} ? 'bg-amber-500/20 border-amber-600 text-amber-300' : 'border-slate-600 text-slate-400 hover:border-amber-600 hover:text-amber-400'">
                        {{ $label }}
                    </button>
                    @endforeach
                </div>
            </div>

            {{-- Custom time input --}}
            <div class="mb-5">
                <label class="block text-xs text-slate-500 uppercase tracking-wider mb-2">Custom duration</label>
                <div class="flex items-center gap-2">
                    <div class="flex items-center gap-2 flex-1 bg-slate-700/60 border border-slate-600 rounded-xl px-4 py-2">
                        <input type="number" x-model="timerCustomH" placeholder="0" min="0" max="99" :disabled="timerRunning"
                            class="w-20 bg-transparent text-white text-center font-mono text-3xl focus:outline-none disabled:opacity-30 py-1"
                            @keydown.enter="setCustomTimer()">
                        <span class="text-slate-400 text-base font-medium">h</span>
                        <span class="text-slate-600 mx-1 text-xl">:</span>
                        <input type="number" x-model="timerCustomM" placeholder="0" min="0" max="59" :disabled="timerRunning"
                            class="w-20 bg-transparent text-white text-center font-mono text-3xl focus:outline-none disabled:opacity-30 py-1"
                            @keydown.enter="setCustomTimer()">
                        <span class="text-slate-400 text-base font-medium">m</span>
                    </div>
                    <button @click="setCustomTimer()" :disabled="timerRunning || (!timerCustomH && !timerCustomM)"
                        class="px-4 py-2 text-sm font-medium bg-slate-700 border border-slate-600 text-slate-300 rounded-xl hover:bg-slate-600 transition-all disabled:opacity-30 disabled:cursor-not-allowed">
                        Set
                    </button>
                </div>
            </div>

            {{-- Controls --}}
            <div class="flex gap-3">
                <template x-if="!timerRunning">
                    <button @click="startTimer()"
                        class="flex-1 py-2.5 text-sm font-semibold bg-amber-500 hover:bg-amber-400 text-slate-900 rounded-xl transition-colors">
                        ▶ Start
                    </button>
                </template>
                <template x-if="timerRunning">
                    <button @click="pauseTimer()"
                        class="flex-1 py-2.5 text-sm font-semibold bg-amber-500/20 border border-amber-600 text-amber-400 rounded-xl hover:bg-amber-500/30 transition-colors">
                        ⏸ Pause
                    </button>
                </template>
                <button @click="resetTimer()"
                    class="px-4 py-2.5 text-sm text-slate-400 border border-slate-600 rounded-xl hover:bg-slate-700 transition-colors">
                    Reset
                </button>
            </div>
            <p class="text-xs text-slate-600 text-center mt-3">Sounds stop + beep when timer ends</p>
        </div>

    </div>
</div>
