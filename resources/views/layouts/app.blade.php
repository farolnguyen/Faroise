<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Faroise') }} — {{ $title ?? 'Find your sound' }}</title>
        <meta name="description" content="{{ $description ?? 'Mix ambient sounds to help you focus, relax, or drift into sleep. Free, beautiful, no distractions.' }}">

        <!-- Open Graph -->
        <meta property="og:type"        content="website">
        <meta property="og:site_name"   content="{{ config('app.name', 'Faroise') }}">
        <meta property="og:title"       content="{{ config('app.name', 'Faroise') }} — {{ $title ?? 'Find your sound' }}">
        <meta property="og:description" content="{{ $description ?? 'Mix ambient sounds to help you focus, relax, or drift into sleep.' }}">
        <meta property="og:url"         content="{{ url()->current() }}">

        <!-- Twitter Card -->
        <meta name="twitter:card"        content="summary">
        <meta name="twitter:title"       content="{{ config('app.name', 'Faroise') }} — {{ $title ?? 'Find your sound' }}">
        <meta name="twitter:description" content="{{ $description ?? 'Mix ambient sounds to help you focus, relax, or drift into sleep.' }}">

        <!-- PWA -->
        <link rel="manifest" href="/manifest.json">
        <meta name="theme-color" content="#0e7490">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-title" content="Faroise">
        <link rel="apple-touch-icon" href="/icons/icon.svg">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-900 text-slate-100 min-h-screen">

        @include('layouts.navigation')

        <main>
            {{ $slot }}
        </main>

        @include('layouts._footer')

        {{-- Toast Container --}}
        <div
            x-data="toastManager()"
            @toast.window="add($event.detail.message, $event.detail.type ?? 'success')"
            class="fixed top-5 right-5 z-[9999] flex flex-col gap-2 w-80 pointer-events-none"
        >
            <template x-for="toast in toasts" :key="toast.id">
                <div
                    x-show="true"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-x-8"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-x-0"
                    x-transition:leave-end="opacity-0 translate-x-8"
                    class="pointer-events-auto flex items-start gap-3 px-4 py-3 rounded-xl border shadow-lg backdrop-blur-md text-sm"
                    :class="{
                        'bg-emerald-900/80 border-emerald-700 text-emerald-100': toast.type === 'success',
                        'bg-red-900/80 border-red-700 text-red-100':             toast.type === 'error',
                        'bg-amber-900/80 border-amber-700 text-amber-100':       toast.type === 'warning',
                        'bg-slate-800/90 border-slate-600 text-slate-100':       toast.type === 'info',
                    }"
                >
                    <span x-text="{success:'✅', error:'❌', warning:'⚠️', info:'ℹ️'}[toast.type] ?? '✅'"></span>
                    <span x-text="toast.message" class="flex-1 leading-snug"></span>
                    <button @click="remove(toast.id)" class="shrink-0 opacity-60 hover:opacity-100 transition-opacity">✕</button>
                </div>
            </template>
        </div>

        {{-- Flash → Toast bridge --}}
        @if (session('status'))
        <script>
            document.addEventListener('alpine:init', () => {
                setTimeout(() => window.dispatchEvent(new CustomEvent('toast', {
                    detail: { message: @js(session('status')), type: 'success' }
                })), 100);
            });
        </script>
        @endif
        @if (session('error'))
        <script>
            document.addEventListener('alpine:init', () => {
                setTimeout(() => window.dispatchEvent(new CustomEvent('toast', {
                    detail: { message: @js(session('error')), type: 'error' }
                })), 100);
            });
        </script>
        @endif

        <script>
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', () => {
                    navigator.serviceWorker.register('/sw.js').catch(() => {});
                });
            }
        </script>
    </body>
</html>
