<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $code }} — Faroise</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-900 text-slate-100 min-h-screen flex items-center justify-center px-4">
    <div class="text-center max-w-md">
        <p class="text-8xl font-black mb-6 bg-gradient-to-r {{ $gradient }} bg-clip-text text-transparent">
            {{ $code }}
        </p>
        <h1 class="text-2xl font-bold text-white mb-3">{{ $title }}</h1>
        <p class="text-slate-400 mb-8 leading-relaxed">{{ $description }}</p>
        <div class="flex gap-3 justify-center">
            <a href="{{ url('/') }}"
               class="px-5 py-2.5 bg-cyan-500 hover:bg-cyan-400 text-slate-900 font-semibold text-sm rounded-xl transition-colors">
                🏠 Go Home
            </a>
            <button onclick="history.back()"
                    class="px-5 py-2.5 border border-slate-700 hover:border-slate-500 text-slate-300 hover:text-white text-sm rounded-xl transition-colors">
                ← Go Back
            </button>
        </div>
    </div>
</body>
</html>
