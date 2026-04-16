<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sleep Screen — Faroise</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { overflow: hidden; font-family: system-ui, sans-serif; }

        #cs {
            position: fixed; inset: 0;
            display: flex; align-items: center; justify-content: center;
            transition: background 0.6s ease;
            cursor: pointer;
        }

        .hint {
            position: absolute; bottom: 2rem; left: 50%; transform: translateX(-50%);
            font-size: 0.75rem; letter-spacing: 0.05em;
            opacity: 0; transition: opacity 0.4s;
            user-select: none; pointer-events: none; white-space: nowrap;
        }
        #cs:hover .hint { opacity: 0.35; }

        .swatches {
            position: absolute; top: 1.5rem; right: 1.5rem;
            display: flex; flex-direction: column; gap: 8px;
            opacity: 0; transition: opacity 0.4s;
        }
        #cs:hover .swatches { opacity: 1; }

        .swatch {
            width: 22px; height: 22px; border-radius: 50%;
            border: 2px solid rgba(128,128,128,0.3);
            cursor: pointer; transition: transform 0.15s, border-color 0.15s;
            text-decoration: none; display: block;
        }
        .swatch:hover, .swatch.active { transform: scale(1.25); border-color: rgba(255,255,255,0.6); }

        .custom-color {
            width: 22px; height: 22px; border-radius: 50%;
            border: 2px solid rgba(128,128,128,0.3);
            cursor: pointer; overflow: hidden; appearance: none;
            -webkit-appearance: none; padding: 0;
        }

        .clock {
            font-size: 4rem; font-weight: 700; letter-spacing: -0.02em;
            font-variant-numeric: tabular-nums;
            opacity: 0; transition: opacity 0.4s;
            user-select: none; pointer-events: none;
        }
        #cs:hover .clock { opacity: 0.2; }

        .fs-btn {
            position: absolute; bottom: 2rem; right: 1.5rem;
            font-size: 0.7rem; letter-spacing: 0.08em; text-transform: uppercase;
            opacity: 0; transition: opacity 0.4s;
            background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2);
            border-radius: 6px; padding: 4px 10px; cursor: pointer;
            color: inherit;
        }
        #cs:hover .fs-btn { opacity: 0.6; }
        .fs-btn:hover { opacity: 1 !important; }
    </style>
</head>
<body>
<div id="cs">

    {{-- Color swatches --}}
    <div class="swatches" id="swatches">
        @foreach (['#000000' => 'Black', '#1e293b' => 'Dark', '#7c3aed' => 'Purple', '#be185d' => 'Pink', '#dc2626' => 'Red', '#16a34a' => 'Green', '#ffffff' => 'White'] as $hex => $label)
        <a href="?c={{ urlencode($hex) }}" class="swatch {{ request('c', '#000000') === $hex ? 'active' : '' }}"
           style="background: {{ $hex }}" title="{{ $label }}"></a>
        @endforeach
        <input type="color" class="custom-color" id="customColor"
               value="{{ request('c', '#000000') }}" title="Custom color">
    </div>

    {{-- Clock --}}
    <div class="clock" id="clock">00:00</div>

    {{-- Hints --}}
    <div class="hint" id="hint">click to toggle fullscreen</div>

    {{-- Fullscreen button --}}
    <button class="fs-btn" onclick="toggleFullscreen()">⛶ Fullscreen</button>

</div>

<script>
    const cs = document.getElementById('cs');
    const clock = document.getElementById('clock');
    const hint = document.getElementById('hint');
    const customColor = document.getElementById('customColor');

    // Set initial color from query param
    const initColor = decodeURIComponent('{{ request('c', '#000000') }}') || '#000000';
    cs.style.background = initColor;

    // Text color: invert for light backgrounds
    function updateTextColor(bg) {
        const hex = bg.replace('#', '');
        const r = parseInt(hex.substring(0,2), 16);
        const g = parseInt(hex.substring(2,4), 16);
        const b = parseInt(hex.substring(4,6), 16);
        const lum = (0.299*r + 0.587*g + 0.114*b) / 255;
        const c = lum > 0.5 ? 'rgba(0,0,0,0.6)' : 'rgba(255,255,255,0.6)';
        clock.style.color = c;
        hint.style.color = c;
        document.querySelectorAll('.fs-btn').forEach(el => el.style.color = c);
    }
    updateTextColor(initColor);

    // Custom color picker
    customColor.addEventListener('input', () => {
        cs.style.background = customColor.value;
        updateTextColor(customColor.value);
    });
    customColor.addEventListener('change', () => {
        history.replaceState(null, '', `?c=${encodeURIComponent(customColor.value)}`);
    });

    // Clock
    function updateClock() {
        const now = new Date();
        const h = now.getHours().toString().padStart(2, '0');
        const m = now.getMinutes().toString().padStart(2, '0');
        clock.textContent = `${h}:${m}`;
    }
    updateClock();
    setInterval(updateClock, 10000);

    // Fullscreen
    function toggleFullscreen() {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen().catch(() => {});
        } else {
            document.exitFullscreen().catch(() => {});
        }
    }
    cs.addEventListener('click', toggleFullscreen);

    // Auto-fullscreen after short delay
    setTimeout(() => {
        document.documentElement.requestFullscreen().catch(() => {});
    }, 800);
</script>
</body>
</html>
