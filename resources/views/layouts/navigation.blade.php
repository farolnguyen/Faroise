<nav x-data="{ open: false }" class="bg-slate-900/90 backdrop-blur-md border-b border-slate-800 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <img src="{{ asset('images/logo.png') }}" alt="Faroise" class="h-8 w-auto">
                <span class="text-lg font-bold tracking-tight bg-gradient-to-r from-cyan-400 to-blue-400 bg-clip-text text-transparent">
                    Faroise
                </span>
            </a>

            {{-- Desktop Nav Links --}}
            <div class="hidden md:flex items-center gap-8">
                <a href="{{ route('home') }}"
                   class="text-sm font-medium transition-colors {{ request()->routeIs('home') ? 'text-cyan-400' : 'text-slate-400 hover:text-slate-200' }}">
                    Home
                </a>
                <a href="{{ route('explore') }}"
                   class="text-sm font-medium transition-colors {{ request()->routeIs('explore') ? 'text-cyan-400' : 'text-slate-400 hover:text-slate-200' }}">
                    Explore
                </a>
                @auth
                <a href="{{ route('dashboard') }}"
                   class="text-sm font-medium transition-colors {{ request()->routeIs('dashboard') ? 'text-cyan-400' : 'text-slate-400 hover:text-slate-200' }}">
                    My Mixes
                </a>
                @endauth
            </div>

            {{-- Desktop Auth --}}
            <div class="hidden md:flex items-center gap-3">
                @auth
                    @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}"
                       class="text-sm font-medium text-amber-400 hover:text-amber-300 transition-colors border border-amber-800/60 rounded-lg px-3 py-1.5 hover:border-amber-700">
                        ⚙ Admin
                    </a>
                    @endif
                    <a href="{{ route('bookmarks.index') }}"
                       class="text-sm font-medium transition-colors {{ request()->routeIs('bookmarks.*') ? 'text-amber-400' : 'text-slate-400 hover:text-slate-200' }}"
                       title="Bookmarks">🔖 Bookmarks</a>
                    <a href="{{ route('profile.edit') }}"
                       class="text-sm font-medium transition-colors {{ request()->routeIs('profile.*') ? 'text-cyan-400' : 'text-slate-400 hover:text-slate-200' }}">
                        {{ Auth::user()->name }}
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="text-sm text-slate-400 hover:text-white transition-colors px-3 py-1.5 rounded-lg hover:bg-slate-800">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="text-sm text-slate-400 hover:text-white transition-colors px-3 py-1.5 rounded-lg hover:bg-slate-800">
                        Login
                    </a>
                    <a href="{{ route('register') }}"
                       class="text-sm font-semibold bg-cyan-500 hover:bg-cyan-400 text-slate-900 px-4 py-2 rounded-lg transition-colors">
                        Register
                    </a>
                @endauth
            </div>

            {{-- Mobile Hamburger --}}
            <button @click="open = !open"
                    class="md:hidden p-2 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition-colors">
                <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'hidden': open, 'block': !open}" class="block" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{'hidden': !open, 'block': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div x-show="open" x-transition class="md:hidden border-t border-slate-800 bg-slate-900 px-4 py-4 space-y-3">
        <a href="{{ route('home') }}" class="block text-sm text-slate-300 hover:text-white py-1">Home</a>
        <a href="{{ route('explore') }}" class="block text-sm text-slate-300 hover:text-white py-1">Explore</a>
        @auth
            <a href="{{ route('dashboard') }}" class="block text-sm text-slate-300 hover:text-white py-1">My Mixes</a>
            <div class="border-t border-slate-800 pt-3 mt-3">
                <p class="text-xs text-slate-500 mb-2">{{ Auth::user()->email }}</p>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-slate-400 hover:text-red-400 transition-colors">Logout</button>
                </form>
            </div>
        @else
            <div class="flex gap-3 pt-1">
                <a href="{{ route('login') }}" class="text-sm text-slate-400 hover:text-white transition-colors">Login</a>
                <a href="{{ route('register') }}" class="text-sm font-semibold text-cyan-400 hover:text-cyan-300 transition-colors">Register</a>
            </div>
        @endauth
    </div>
</nav>
