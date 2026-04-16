<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-950 text-slate-100 min-h-screen">

    <div class="flex min-h-screen">

        {{-- Sidebar --}}
        <aside class="w-56 bg-slate-900 border-r border-slate-800 flex flex-col shrink-0">
            <div class="px-5 py-5 border-b border-slate-800">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <span>🏮</span>
                    <span class="font-bold text-sm bg-gradient-to-r from-cyan-400 to-blue-400 bg-clip-text text-transparent">Faroise</span>
                </a>
                <p class="text-xs text-slate-600 mt-1">Admin Panel</p>
            </div>

            <nav class="flex-1 px-3 py-4 space-y-1">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-2.5 px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <span>📊</span> Dashboard
                </a>
                <a href="{{ route('admin.sounds.index') }}"
                   class="flex items-center gap-2.5 px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.sounds.*') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <span>🎵</span> Sounds
                </a>
                <a href="{{ route('admin.categories.index') }}"
                   class="flex items-center gap-2.5 px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.categories.*') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <span>🗂</span> Categories
                </a>
                <a href="{{ route('admin.mixes.index') }}"
                   class="flex items-center gap-2.5 px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.mixes.*') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <span>🎛️</span> Mixes
                </a>
                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center gap-2.5 px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <span>👥</span> Users
                </a>
            </nav>

            <div class="px-3 py-4 border-t border-slate-800">
                <a href="{{ route('home') }}" class="flex items-center gap-2 px-3 py-2 text-xs text-slate-500 hover:text-slate-300 transition-colors rounded-lg hover:bg-slate-800">
                    <span>←</span> Back to site
                </a>
            </div>
        </aside>

        {{-- Main --}}
        <div class="flex-1 flex flex-col min-w-0">
            <header class="bg-slate-900 border-b border-slate-800 px-6 py-4 flex items-center justify-between">
                <h1 class="text-sm font-semibold text-white">@yield('pageTitle', 'Admin')</h1>
                <span class="text-xs text-slate-500">{{ auth()->user()->name }}</span>
            </header>

            <main class="flex-1 p-6">

                @include('admin._flash')

                @yield('content')
            </main>
        </div>

    </div>
</body>
</html>
