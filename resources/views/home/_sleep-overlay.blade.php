{{-- ── SLEEP SCREEN OVERLAY ─────────────────────────────── --}}
<div
    id="sleep-overlay"
    x-show="sleepMode"
    x-transition:enter="transition ease-in duration-500"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-out duration-300"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-[9998] flex items-center justify-center cursor-pointer group"
    :style="`background: ${sleepColor}`"
    @click="exitSleepMode()"
>
    <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-500 text-center select-none pointer-events-none"
         :class="(sleepColor === '#ffffff' || sleepColor === '#ffff00') ? 'text-slate-700' : 'text-white/30'">
        <p class="text-sm">Click or press Esc to exit</p>
    </div>
</div>
