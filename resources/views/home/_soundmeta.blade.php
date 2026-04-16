{{-- Sound Metadata for JS --}}
<script>
    window.soundMeta = {
        @foreach ($allSounds as $sound)
        {{ $sound->id }}: { name: @js($sound->name), icon: @js($sound->icon), iconPath: @js($sound->icon_path ? asset('storage/'.$sound->icon_path) : null), color: @js($sound->color), url: @js($sound->audio_url), loopStart: {{ $sound->loop_start }} },
        @endforeach
    };
</script>
