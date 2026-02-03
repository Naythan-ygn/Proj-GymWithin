<nav x-data="{ mobileMenuOpen: false }" class="glass-nav fixed top-0 left-0 right-0 z-50">
    <div class="max-w-7xl mx-auto px-6 h-20 flex justify-between items-center">
        <div class="flex items-center space-x-3">
            <a href="/" class="flex items-center space-x-3">
                <img src="{{ asset('Treadmill_Images/logo.png') }}" alt="GymWithin Logo"
                    class="w-auto h-12 md:h-16 object-contain">
                <span class="text-xl font-bold tracking-tight">GymWithin</span>
            </a>
        </div>

        <div class="hidden md:flex items-center space-x-8">
            <a href="#equipment" class="hover:text-orange-500 transition-colors">Equipment</a>
            <a href="#benefits" class="hover:text-orange-500 transition-colors">Benefits</a>
            <a href="#about" class="hover:text-orange-500 transition-colors">About Us</a>
            <a href="{{ url('/contact') }}" class="hover:text-orange-500 transition-colors">Contat Us</a>

            <div class="flex items-center space-x-4 ml-4 border-l border-white/10 pl-8">
                @auth
                    <a href="{{ url('/dashboard') }}">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-gray-300 hover:text-white">Log in</a>
                    <a href="{{ route('register') }}"
                        class="bg-white text-black px-5 py-2 rounded-full font-medium hover:bg-orange-500 hover:text-white transition-all">Register</a>
                @endauth
            </div>
        </div>

        <div class="md:hidden flex items-center">
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-white focus:outline-none p-2">
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

    <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-4" class="md:hidden glass-nav border-t border-white/10">
        <div class="px-6 py-6 space-y-4 flex flex-col">
            <a href="#equipment" @click="mobileMenuOpen = false" class="text-lg py-2">Equipment</a>
            <a href="#benefits" @click="mobileMenuOpen = false" class="text-lg py-2">Benefits</a>
            <a href="#about" @click="mobileMenuOpen = false" class="text-lg py-2">About Us</a>
            <a href="{{ url('/contact') }}" @click="mobileMenuOpen = false" class="text-lg py-2">Contact Us</a>
            <hr class="border-white/10">
            @auth
                <a href="{{ url('/dashboard') }}" class="text-lg py-2">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="text-lg py-2">Log in</a>
                <a href="{{ route('register') }}"
                    class="bg-orange-500 text-white px-5 py-3 rounded-xl text-center font-bold">Register</a>
            @endauth
        </div>
    </div>
</nav>