{{-- ── STAGE ────────────────────────────────────────────── --}}
<div class="flex-1 flex flex-col items-center justify-start bg-gradient-to-b from-slate-900 to-slate-950 relative overflow-y-auto px-4 py-10 min-h-[280px]">

    {{-- Ambient glow (dynamic) --}}
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[450px] rounded-full blur-3xl"
             :style="stageGlow()"
             style="transition: background 1.5s ease"></div>
    </div>

    {{-- Stage label --}}
    <p class="absolute top-4 left-1/2 -translate-x-1/2 text-[10px] text-slate-700 font-semibold uppercase tracking-[0.2em] select-none">
        ◈ Stage
    </p>

    {{-- Dynamic Slots (flex-wrap, fixed card size) --}}
    <div class="grid grid-cols-5 gap-3 relative z-10 w-full max-w-7xl px-2">
        <template x-for="(slot, i) in slots" :key="'slot-'+i">
            <div
                class="relative rounded-2xl border-2 transition-all duration-200 overflow-hidden w-full h-[280px]"
                :class="draggingOver === i && !slot ? 'scale-105' : ''"
                :style="slot
                    ? `border-style:solid; border-color:${slot.color}80; background:${slot.color}1a; box-shadow:0 0 32px ${slot.color}28`
                    : draggingOver === i
                        ? 'border-style:dashed; border-color:#22d3ee; background:rgba(34,211,238,0.08); box-shadow:0 0 20px rgba(34,211,238,0.15)'
                        : 'border-style:dashed; border-color:#475569; background:rgba(30,41,59,0.5); cursor:pointer'"
                @dragover.prevent="draggingOver = i"
                @dragleave.self="draggingOver = null"
                @drop.prevent="dropToSlot(i, JSON.parse($event.dataTransfer.getData('sound'))); draggingOver = null"
            >
                {{-- Empty slot --}}
                <template x-if="!slot">
                    <div class="absolute inset-0 flex flex-col items-center justify-center gap-2">
                        <div class="text-slate-500 font-light select-none leading-none" style="font-size:6rem">+</div>
                        <div class="text-slate-600 text-[10px] uppercase tracking-widest select-none">drop here</div>
                    </div>
                </template>

                {{-- Filled slot --}}
                <template x-if="slot">
                    <div class="absolute inset-0 flex flex-col items-center justify-center p-3" @click.stop>

                        {{-- Remove (top-right) --}}
                        <button @click.stop="clearSlot(i)"
                            class="absolute top-1.5 right-1.5 w-6 h-6 rounded-full bg-black/60 text-slate-400 hover:text-white hover:bg-black/80 text-sm flex items-center justify-center transition-all z-10"
                        >×</button>

                        {{-- Mute (top-left) --}}
                        <button @click.stop="toggleMute(slot.id)"
                            class="absolute top-1.5 left-1.5 w-6 h-6 rounded-full bg-black/60 text-slate-400 hover:text-white hover:bg-black/80 text-xs flex items-center justify-center transition-all z-10"
                            :title="isMuted(slot.id) ? 'Unmute' : 'Mute'"
                        ><span x-text="isMuted(slot.id) ? '🔇' : '🔊'" style="font-size:10px"></span></button>

                        {{-- Wave bars --}}
                        <div class="flex gap-0.5 items-end mb-2" style="height:18px">
                            <template x-if="!isMuted(slot.id)">
                                <div class="flex gap-0.5 items-end" style="height:18px">
                                    <span class="w-1 rounded-full animate-[bounce_0.8s_ease-in-out_infinite]"      :style="`background:${slot.color}; height:55%`"></span>
                                    <span class="w-1 rounded-full animate-[bounce_0.8s_ease-in-out_infinite_0.1s]" :style="`background:${slot.color}; height:100%`"></span>
                                    <span class="w-1 rounded-full animate-[bounce_0.8s_ease-in-out_infinite_0.2s]" :style="`background:${slot.color}; height:70%`"></span>
                                    <span class="w-1 rounded-full animate-[bounce_0.8s_ease-in-out_infinite_0.3s]" :style="`background:${slot.color}; height:40%`"></span>
                                </div>
                            </template>
                            <template x-if="isMuted(slot.id)">
                                <div class="flex gap-0.5 items-end" style="height:18px">
                                    <span class="w-1 rounded-full bg-slate-600" style="height:30%"></span>
                                    <span class="w-1 rounded-full bg-slate-600" style="height:30%"></span>
                                    <span class="w-1 rounded-full bg-slate-600" style="height:30%"></span>
                                    <span class="w-1 rounded-full bg-slate-600" style="height:30%"></span>
                                </div>
                            </template>
                        </div>

                        {{-- Icon --}}
                        <div class="leading-none mb-1.5 select-none">
                            <template x-if="slot.iconPath">
                                <img :src="slot.iconPath" class="w-10 h-10 mx-auto object-contain" :alt="slot.name">
                            </template>
                            <template x-if="!slot.iconPath">
                                <span style="font-size:2.5rem" x-text="slot.icon"></span>
                            </template>
                        </div>

                        {{-- Name --}}
                        <p class="text-xs font-semibold text-white text-center leading-tight mb-3 w-full truncate select-none px-1"
                           x-text="slot.name"></p>

                        {{-- Volume slider + % --}}
                        <div class="w-full px-1" @click.stop>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-[10px] select-none"
                                      :class="isMuted(slot.id) ? 'text-slate-600' : 'text-slate-400'"
                                      x-text="isMuted(slot.id) ? 'muted' : (volumes[slot.id] ?? 70) + '%'"></span>
                            </div>
                            <input type="range" min="0" max="100"
                                x-effect="$el.value = volumes[slot.id] ?? 70"
                                :disabled="isMuted(slot.id)"
                                @input="setVolume(slot.id, $event.target.value)"
                                class="w-full cursor-pointer disabled:opacity-30 disabled:cursor-not-allowed"
                                style="height:4px; border-radius:4px"
                                :style="`accent-color:${slot.color}`">
                        </div>

                    </div>
                </template>
            </div>
        </template>
    </div>

    {{-- Empty stage hint --}}
    <p
        x-show="slots.every(s => s === null)"
        x-transition:enter="transition ease-out duration-700 delay-500"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        class="absolute bottom-6 left-1/2 -translate-x-1/2 text-[11px] text-slate-700 pointer-events-none select-none whitespace-nowrap"
        x-text="'ontouchstart' in window ? '↓ tap a sound below to add it to the stage' : '↓ drag sounds onto the stage — or tap to add'"
    ></p>

</div>
