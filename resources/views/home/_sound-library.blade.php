{{-- ── SOUND LIBRARY ────────────────────────────────────── --}}
<div class="shrink-0 bg-slate-900/95 border-t-2 border-slate-700/60"
     :style="activeCount() > 0 ? 'padding-bottom: 80px' : ''">
    <div class="flex gap-3 overflow-x-auto px-5 py-4 scrollbar-none">
        @foreach ($allSounds as $sound)
        <div
            data-sound-chip
            style="opacity:0; transform:translateX(16px); transition:opacity 0.25s ease, transform 0.25s ease"
            x-show="soundFilter({{ $sound->id }}, {{ $sound->category_id }}, [{{ $sound->tags->pluck('id')->join(',') }}])"
            draggable="true"
            @dragstart="cancelHoverPreview(); $event.dataTransfer.setData('sound', JSON.stringify({ id: {{ $sound->id }}, url: @js($sound->audio_url), name: @js($sound->name), icon: @js($sound->icon), iconPath: @js($sound->icon_path ? asset('storage/'.$sound->icon_path) : null), color: @js($sound->color), loopStart: {{ $sound->loop_start }} }))"
            @click="addToFirstSlot({ id: {{ $sound->id }}, url: @js($sound->audio_url), name: @js($sound->name), icon: @js($sound->icon), iconPath: @js($sound->icon_path ? asset('storage/'.$sound->icon_path) : null), color: @js($sound->color), loopStart: {{ $sound->loop_start }} })"
            @mouseenter="startHoverPreview(@js($sound->audio_url))"
            @mouseleave="cancelHoverPreview()"
            class="shrink-0 w-28 rounded-2xl border-2 p-3 text-center transition-all duration-200 select-none"
            :class="isInSlot({{ $sound->id }})
                ? 'opacity-35 border-slate-700 bg-slate-800/20 cursor-not-allowed'
                : 'border-slate-600 bg-slate-800 hover:bg-slate-700 hover:border-slate-400 hover:scale-110 cursor-grab active:cursor-grabbing active:scale-95 active:border-cyan-500'"
            :title="isInSlot({{ $sound->id }}) ? 'On stage' : 'Hold to preview · ' + @js($sound->name)"
        >
            <div class="leading-none mb-2">
                @if($sound->icon_path)
                <img src="{{ asset('storage/'.$sound->icon_path) }}" class="w-10 h-10 mx-auto object-contain" alt="{{ $sound->name }}">
                @else
                <span class="text-4xl">{{ $sound->icon }}</span>
                @endif
            </div>
            <p class="text-xs leading-tight truncate font-medium"
               :class="isInSlot({{ $sound->id }}) ? 'text-slate-600' : 'text-slate-300'">{{ $sound->name }}</p>
        </div>
        @endforeach
    </div>
</div>
