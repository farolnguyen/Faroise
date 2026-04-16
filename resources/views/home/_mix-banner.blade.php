{{-- Loaded Mix Banner --}}
@if ($mixData)
<div class="shrink-0 bg-cyan-950/60 border-b border-cyan-900/40 px-4 py-2 text-center text-sm text-slate-400">
    🎵 Playing: <span class="text-cyan-400 font-medium">{{ $mixData['name'] }}</span>
    <a href="{{ route('home') }}" class="ml-3 text-xs text-slate-600 hover:text-slate-400 transition-colors">✕ Clear</a>
</div>
@endif
