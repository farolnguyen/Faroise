<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-900 text-slate-100">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2 mb-6">
                <span class="text-2xl">🏮</span>
                <span class="text-xl font-bold tracking-tight bg-gradient-to-r from-cyan-400 to-blue-400 bg-clip-text text-transparent">
                    Faroise
                </span>
            </a>

            {{-- Card --}}
            <div class="w-full sm:max-w-md px-6 py-8 bg-slate-800 border border-slate-700 shadow-xl overflow-hidden sm:rounded-2xl">
                {{ $slot }}
            </div>

            {{-- Back to home --}}
            <a href="{{ route('home') }}" class="mt-6 text-sm text-slate-500 hover:text-slate-300 transition-colors">
                ← Back to Faroise
            </a>
        </div>
    </body>
</html>
