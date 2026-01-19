<nav class="glass-nav fixed top-0 left-0 right-0 z-50">
    <div class="max-w-7xl mx-auto px-6 h-20 flex justify-between items-center">
        <div class="flex items-center space-x-2">
            <div class="w-8 h-8 bg-orange-500 rounded-lg"></div>
            <span class="text-xl font-bold">GymWithin</span>
        </div>

        <div class="hidden md:flex space-x-8">
            <a href="#equipment">Equipment</a>
            <a href="#benefits">Benefits</a>
        </div>

        <div class="flex items-center space-x-4">
            @auth
                <a href="{{ url('/dashboard') }}">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="text-gray-300">Log in</a>
                <a href="{{ route('register') }}" class="bg-white text-black px-5 py-2 rounded-full">Register</a>
            @endauth
        </div>
    </div>
</nav>