<nav x-data="{ mobileMenuOpen: false }" class="dark glass-panel fixed top-0 left-0 right-0 z-50 border-b border-white/10 shadow-2xl"
    style="background: rgba(24, 24, 27, 0.85); border-color: rgba(63, 63, 70, 0.4);">

    <div class="max-w-7xl mx-auto px-6 h-20 flex justify-between items-center">
        {{-- Logo Section --}}
        <div class="flex items-center space-x-3">
            <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
                <img src="{{ asset('Treadmill_Images/logo.png') }}" alt="GymWithin Logo"
                    class="w-auto h-12 md:h-16 object-contain transition-transform group-hover:scale-105">
                <span class="text-xl font-bold tracking-tight text-white">GymWithin</span>
            </a>
        </div>

        {{-- Desktop Navigation --}}
        <div class="hidden md:flex items-center space-x-8">
            {{-- NEW: Conditional Home Link for Logged-in Users --}}
            @auth
                <a href="{{ auth()->user()->role === 'admin' ? route('dashboard') : route('user.home') }}"
                    class="text-zinc-300 hover:text-orange-500 transition-colors font-medium">
                    Home
                </a>
            @endauth

            <a href="{{ route('equipment') }}"
                class="text-zinc-300 hover:text-orange-500 transition-colors font-medium">Equipment</a>
            <a href="{{ route('benefits') }}"
                class="text-zinc-300 hover:text-orange-500 transition-colors font-medium">Benefits</a>
            <a href="{{ route('about') }}"
                class="text-zinc-300 hover:text-orange-500 transition-colors font-medium">About Us</a>
            <a href="{{ route('contact') }}"
                class="text-zinc-300 hover:text-orange-500 transition-colors font-medium">Contact Us</a>

            <div class="flex items-center space-x-4 ml-4 border-l border-white/10 pl-8">
                @auth
                    {{-- Role-Based Dashboard Access --}}
                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('admin.users.index') }}"
                            class="text-orange-400 hover:text-orange-300 font-semibold transition-colors">
                            Manage Members
                        </a>
                    @else
                        {{-- User name links to their dashboard/home --}}
                        <a href="{{ route('user.home') }}" class="text-zinc-100 hover:text-white font-semibold transition-colors">
                            {{ auth()->user()->name }}
                        </a>
                    @endif

                    <flux:avatar :name="auth()->user()->name" size="sm" class="border border-white/20 ml-2" />
                @else
                    <a href="{{ route('login') }}" class="text-zinc-400 hover:text-white transition-colors">Log in</a>
                    <a href="{{ route('register') }}"
                        class="bg-orange-500 text-white px-6 py-2.5 rounded-full font-bold hover:bg-orange-600 shadow-lg shadow-orange-500/20 transition-all active:scale-95">
                        Register
                    </a>
                @endauth
            </div>
        </div>

        {{-- Mobile Toggle --}}
        <div class="md:hidden flex items-center">
            <button @click="mobileMenuOpen = !mobileMenuOpen"
                class="text-white focus:outline-none p-2 rounded-lg hover:bg-white/5 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16" />
                    <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div x-show="mobileMenuOpen" x-cloak x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-4" class="md:hidden border-t border-white/10"
        style="background: rgba(24, 24, 27, 0.98);">
        <div class="px-6 py-8 space-y-6 flex flex-col">
            <a href="{{ route('equipment') }}" @click="mobileMenuOpen = false"
                class="text-lg font-medium text-zinc-100">Equipment</a>
            <a href="{{ route('benefits') }}" @click="mobileMenuOpen = false"
                class="text-lg font-medium text-zinc-100">Benefits</a>
            <a href="{{ route('about') }}" @click="mobileMenuOpen = false"
                class="text-lg font-medium text-zinc-100">About Us</a>
            <a href="{{ route('contact') }}" @click="mobileMenuOpen = false"
                class="text-lg font-medium text-zinc-100">Contact Us</a>
            <hr class="border-white/10">

            @auth
                <a href="{{ auth()->user()->role === 'admin' ? route('dashboard') : route('user.home') }}"
                    class="text-lg font-bold text-orange-500">
                    {{ auth()->user()->role === 'admin' ? 'Member Management' : 'My Dashboard' }}
                </a>
            @else
                <div class="flex flex-col gap-4 text-center">
                    <a href="{{ route('login') }}" class="text-lg font-medium text-zinc-400">Log in</a>
                    <a href="{{ route('register') }}"
                        class="bg-orange-500 text-white px-5 py-4 rounded-2xl font-bold shadow-xl shadow-orange-500/20">
                        Register
                    </a>
                </div>
            @endauth
        </div>
    </div>
</nav>
