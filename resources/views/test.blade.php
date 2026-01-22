<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GymWithin - Premium Fitness Equipment</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/motion@11.11.13/dist/motion.js"></script>
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
</head>

<body class="bg-black text-white overflow-x-hidden">

    <!-- Premium Navigation -->
    <nav class="glass-nav fixed top-0 left-0 right-0 z-50">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg"></div>
                    <span class="text-xl font-bold tracking-tight">GymWithin</span>
                </div>

                <div class="hidden md:flex items-center space-x-8 text-sm font-medium">
                    <a href="#equipment" class="text-gray-300 hover:text-white transition-colors">Equipment</a>
                    <a href="#benefits" class="text-gray-300 hover:text-white transition-colors">Benefits</a>
                    <a href="#cta" class="text-gray-300 hover:text-white transition-colors">Get Started</a>
                    <a href="#footer" class="text-gray-300 hover:text-white transition-colors">Contact</a>
                </div>

                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                            Dashboard
                        </a>
                    @else
                        {{-- class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent
                        hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal" --}}
                        <a href="{{ route('login') }}">
                            <button
                                class="hidden sm:block text-sm font-medium text-gray-300 hover:text-white transition-colors">

                                Log in
                            </button>
                        </a>
                        @if (Route::has('register'))
                            {{-- class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a]
                            border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm
                            leading-normal" --}}
                            <a href="{{ route('register') }}">
                                <button
                                    class="bg-white text-black px-5 py-2.5 rounded-full text-sm font-semibold hover:bg-gray-100 transition-all">
                                    Register
                                </button>
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section with Motion.dev Scroll Animation -->
    <section class="hero-wrapper">
        <div class="hero-canvas">
            <img id="heroImage" src="{{ asset('Treadmill_Images/treadmill_hero.webp') }}" alt="Premium Treadmill"
                class="hero-image">

            <!-- Gradient Overlays -->
            <div
                class="absolute inset-0 bg-gradient-to-b from-black/40 via-transparent to-black/80 pointer-events-none">
            </div>
            <div
                class="absolute inset-0 bg-gradient-to-r from-black/30 via-transparent to-black/30 pointer-events-none">
            </div>

            <!-- Hero Content -->
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="text-center px-6 max-w-5xl">
                    <h1
                        class="text-5xl sm:text-6xl md:text-7xl lg:text-8xl font-bold tracking-tight mb-6 leading-[1.1]">
                        <span class="block text-white">Redefine Your</span>
                        <span class="block gradient-text mt-2">Fitness Standards</span>
                    </h1>
                    <p class="text-lg sm:text-xl text-gray-300 mb-10 max-w-2xl mx-auto font-light">
                        Experience commercial-grade equipment designed for champions. Premium quality meets intelligent
                        design.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                        <button
                            class="magnetic-btn bg-gradient-to-r from-orange-500 to-orange-600 px-8 py-4 rounded-full font-semibold text-base w-full sm:w-auto">
                            Explore Equipment
                        </button>
                        <button
                            class="glass-card px-8 py-4 rounded-full font-semibold text-base hover:bg-white/10 transition-all w-full sm:w-auto">
                            Watch Demo
                        </button>
                    </div>
                </div>
            </div>

            <!-- Scroll Indicator -->
            <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 flex flex-col items-center gap-2">
                <span class="text-xs text-gray-400 uppercase tracking-widest">Scroll to explore</span>
                <div class="w-6 h-10 border-2 border-white/30 rounded-full flex justify-center p-1">
                    <div class="w-1.5 h-3 bg-white/60 rounded-full animate-bounce"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Equipment Section -->
    <section id="equipment" class="py-24 px-6 bg-black">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16 fade-in" data-fade>
                <h2 class="text-4xl md:text-5xl lg:text-6xl font-bold tracking-tight mb-4">
                    Premium <span class="gradient-text">Equipment</span>
                </h2>
                <p class="text-gray-400 text-lg max-w-2xl mx-auto font-light">
                    Engineered for performance. Built to inspire greatness.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Product Card 1 -->
                <div class="glass-card rounded-3xl overflow-hidden hover:border-orange-500/50 transition-all duration-500 group fade-in"
                    data-fade>
                    <div
                        class="aspect-square bg-gradient-to-br from-gray-900 to-black flex items-center justify-center relative overflow-hidden">
                        <div class="text-8xl group-hover:scale-110 transition-transform duration-500">🏃</div>
                        <div
                            class="absolute top-4 right-4 bg-orange-500 text-white px-3 py-1 rounded-full text-xs font-semibold">
                            FEATURED
                        </div>
                    </div>
                    <div class="p-8">
                        <h3 class="text-2xl font-bold mb-2 group-hover:text-orange-500 transition-colors">Pro Treadmill
                            X1</h3>
                        <p class="text-gray-400 mb-6 text-sm leading-relaxed">
                            Advanced shock absorption with AI-powered performance tracking
                        </p>
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-3xl font-bold text-white">$2,499</div>
                                <div class="text-xs text-gray-500">or $104/mo</div>
                            </div>
                            <button
                                class="bg-white text-black px-6 py-3 rounded-full font-semibold text-sm hover:bg-gray-200 transition-all">
                                Learn More
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Product Card 2 -->
                <div class="glass-card rounded-3xl overflow-hidden hover:border-orange-500/50 transition-all duration-500 group fade-in"
                    data-fade>
                    <div
                        class="aspect-square bg-gradient-to-br from-gray-900 to-black flex items-center justify-center relative overflow-hidden">
                        <div class="text-8xl group-hover:scale-110 transition-transform duration-500">💪</div>
                    </div>
                    <div class="p-8">
                        <h3 class="text-2xl font-bold mb-2 group-hover:text-orange-500 transition-colors">PowerRack
                            Elite</h3>
                        <p class="text-gray-400 mb-6 text-sm leading-relaxed">
                            Commercial-grade steel construction with unlimited exercise versatility
                        </p>
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-3xl font-bold text-white">$1,899</div>
                                <div class="text-xs text-gray-500">or $79/mo</div>
                            </div>
                            <button
                                class="bg-white text-black px-6 py-3 rounded-full font-semibold text-sm hover:bg-gray-200 transition-all">
                                Learn More
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Product Card 3 -->
                <div class="glass-card rounded-3xl overflow-hidden hover:border-orange-500/50 transition-all duration-500 group fade-in"
                    data-fade>
                    <div
                        class="aspect-square bg-gradient-to-br from-gray-900 to-black flex items-center justify-center relative overflow-hidden">
                        <div class="text-8xl group-hover:scale-110 transition-transform duration-500">🚴</div>
                    </div>
                    <div class="p-8">
                        <h3 class="text-2xl font-bold mb-2 group-hover:text-orange-500 transition-colors">Spin Cycle Pro
                        </h3>
                        <p class="text-gray-400 mb-6 text-sm leading-relaxed">
                            Studio-quality magnetic resistance with live performance metrics
                        </p>
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-3xl font-bold text-white">$1,299</div>
                                <div class="text-xs text-gray-500">or $54/mo</div>
                            </div>
                            <button
                                class="bg-white text-black px-6 py-3 rounded-full font-semibold text-sm hover:bg-gray-200 transition-all">
                                Learn More
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section id="benefits" class="py-24 px-6 bg-gradient-to-b from-black to-gray-950">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16 fade-in" data-fade>
                <h2 class="text-4xl md:text-5xl lg:text-6xl font-bold tracking-tight mb-4">
                    Why <span class="gradient-text">GymWithin</span>
                </h2>
                <p class="text-gray-400 text-lg max-w-2xl mx-auto font-light">
                    More than equipment. A commitment to excellence.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center fade-in" data-fade>
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-orange-500/20 to-orange-600/20 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-orange-500/30">
                        <span class="text-4xl">🏆</span>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Premium Quality</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Commercial-grade materials engineered for a lifetime of performance
                    </p>
                </div>

                <div class="text-center fade-in" data-fade>
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-orange-500/20 to-orange-600/20 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-orange-500/30">
                        <span class="text-4xl">🚚</span>
                    </div>
                    <h3 class="text-xl font-bold mb-3">White Glove Service</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Complimentary delivery and professional installation included
                    </p>
                </div>

                <div class="text-center fade-in" data-fade>
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-orange-500/20 to-orange-600/20 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-orange-500/30">
                        <span class="text-4xl">🛡️</span>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Lifetime Warranty</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Comprehensive coverage on all structural components
                    </p>
                </div>

                <div class="text-center fade-in" data-fade>
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-orange-500/20 to-orange-600/20 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-orange-500/30">
                        <span class="text-4xl">💬</span>
                    </div>
                    <h3 class="text-xl font-bold mb-3">24/7 Support</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Expert guidance available whenever you need assistance
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Premium CTA Section -->
    <section id="cta"
        class="py-32 px-6 bg-gradient-to-br from-orange-600 via-orange-500 to-orange-700 relative overflow-hidden">
        <div
            class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxwYXRoIGQ9Ik0zNiAxOGMzLjMxNCAwIDYgMi42ODYgNiA2cy0yLjY4NiA2LTYgNi02LTIuNjg2LTYtNiAyLjY4Ni02IDYtNiIgc3Ryb2tlPSJyZ2JhKDI1NSwyNTUsMjU1LDAuMSkiLz48L2c+PC9zdmc+')] opacity-30">
        </div>

        <div class="max-w-4xl mx-auto text-center relative z-10 fade-in" data-fade>
            <h2 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 text-white tracking-tight">
                Transform Your Space.<br />Elevate Your Performance.
            </h2>
            <p class="text-xl text-white/90 mb-10 max-w-2xl mx-auto font-light">
                Join thousands of athletes who've upgraded their training with GymWithin's premium equipment.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <button
                    class="magnetic-btn bg-black text-white px-10 py-5 rounded-full font-semibold text-lg hover:bg-gray-900 w-full sm:w-auto">
                    Shop All Equipment
                </button>
                <button
                    class="bg-white/20 backdrop-blur-sm text-white px-10 py-5 rounded-full font-semibold text-lg hover:bg-white/30 transition-all border border-white/30 w-full sm:w-auto">
                    Schedule Consultation
                </button>
            </div>

            <!-- Trust Indicators -->
            <div class="mt-16 pt-16 border-t border-white/20">
                <div class="grid grid-cols-3 gap-8 max-w-3xl mx-auto">
                    <div>
                        <div class="text-4xl font-bold text-white mb-1">15K+</div>
                        <div class="text-white/80 text-sm">Happy Customers</div>
                    </div>
                    <div>
                        <div class="text-4xl font-bold text-white mb-1">4.9★</div>
                        <div class="text-white/80 text-sm">Average Rating</div>
                    </div>
                    <div>
                        <div class="text-4xl font-bold text-white mb-1">98%</div>
                        <div class="text-white/80 text-sm">Would Recommend</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Premium Footer -->
    <footer id="footer" class="bg-black border-t border-white/10 py-16 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-12 mb-12">
                <div class="lg:col-span-2">
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-8 h-8 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg"></div>
                        <span class="text-xl font-bold">GymWithin</span>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed mb-6 max-w-xs">
                        Transforming fitness through premium equipment and unwavering commitment to excellence.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#"
                            class="w-10 h-10 bg-white/5 hover:bg-white/10 rounded-full flex items-center justify-center transition-all border border-white/10">
                            <span class="text-sm">📷</span>
                        </a>
                        <a href="#"
                            class="w-10 h-10 bg-white/5 hover:bg-white/10 rounded-full flex items-center justify-center transition-all border border-white/10">
                            <span class="text-sm">f</span>
                        </a>
                        <a href="#"
                            class="w-10 h-10 bg-white/5 hover:bg-white/10 rounded-full flex items-center justify-center transition-all border border-white/10">
                            <span class="text-sm">▶</span>
                        </a>
                    </div>
                </div>

                <div>
                    <h4 class="font-semibold mb-4 text-sm uppercase tracking-wider text-gray-300">Products</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Cardio
                                Equipment</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Strength
                                Training</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Accessories</a>
                        </li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Bundles &
                                Packages</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-semibold mb-4 text-sm uppercase tracking-wider text-gray-300">Support</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Contact Us</a>
                        </li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Warranty
                                Information</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Shipping &
                                Delivery</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Returns &
                                Exchanges</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-semibold mb-4 text-sm uppercase tracking-wider text-gray-300">Company</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">About
                                GymWithin</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Our Story</a>
                        </li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Careers</a>
                        </li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Press &
                                Media</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-white/10 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-gray-500 text-sm">
                    &copy; 2025 GymWithin, Inc. All rights reserved.
                </p>
                <div class="flex gap-6 text-sm">
                    <a href="#" class="text-gray-500 hover:text-white transition-colors">Privacy Policy</a>
                    <a href="#" class="text-gray-500 hover:text-white transition-colors">Terms of Service</a>
                    <a href="#" class="text-gray-500 hover:text-white transition-colors">Cookie Settings</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scroll to Top Button -->
    <button id="scrollToTop" class="scroll-to-top" aria-label="Scroll to top">
        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
        </svg>
    </button>

    <!-- Chatbot Widget -->
    <div class="chatbot-widget">
        <div id="chatbotWindow" class="chatbot-window">
            <div class="chatbot-header">
                <div>
                    <h3 class="font-bold text-white">GymWithin Assistant</h3>
                    <p class="text-xs text-white/80">Always here to help</p>
                </div>
                <button id="closeChatbot" class="text-white hover:text-white/80 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div id="chatbotMessages" class="chatbot-messages">
                <div class="chatbot-message bot">
                    👋 Hello! I'm your GymWithin assistant. How can I help you today?
                </div>
                <div class="chatbot-message bot">
                    I can help you with:
                    <br />• Product recommendations
                    <br />• Pricing and financing
                    <br />• Shipping information
                    <br />• Technical support
                </div>
            </div>

            <div class="chatbot-input-area">
                <input type="text" id="chatbotInput" class="chatbot-input" placeholder="Type your message..."
                    autocomplete="off">
                <button id="chatbotSend" class="chatbot-send">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                </button>
            </div>
        </div>

        <button id="chatbotButton" class="chatbot-button">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
            </svg>
        </button>
    </div>


</body>

</html>

{{-- This is orginal login page --}}
<x-layouts::auth>
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Log in to your account')" :description="__('Enter your email and password below to log in')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Email Address -->
            <flux:input name="email" :label="__('Email address')" :value="old('email')" type="email" required autofocus
                autocomplete="email" placeholder="email@example.com" />

            <!-- Password -->
            <div class="relative">
                <flux:input name="password" :label="__('Password')" type="password" required
                    autocomplete="current-password" :placeholder="__('Password')" viewable />

                @if (Route::has('password.request'))
                    <flux:link class="absolute top-0 text-sm end-0" :href="route('password.request')" wire:navigate>
                        {{ __('Forgot your password?') }}
                    </flux:link>
                @endif
            </div>

            <!-- Remember Me -->
            <flux:checkbox name="remember" :label="__('Remember me')" :checked="old('remember')" />

            <div class="flex items-center justify-end">
                <flux:button variant="primary" type="submit" class="w-full" data-test="login-button">
                    {{ __('Log in') }}
                </flux:button>
            </div>
        </form>

        @if (Route::has('register'))
            <div class="space-x-1 text-sm text-center rtl:space-x-reverse text-zinc-600 dark:text-zinc-400">
                <span>{{ __('Don\'t have an account?') }}</span>
                <flux:link :href="route('register')" wire:navigate>{{ __('Sign up') }}</flux:link>
            </div>
        @endif
    </div>
</x-layouts::auth>


{{-- The previous version of new update --}}
<x-layouts::auth>
    <div class="grid min-h-svh w-full lg:grid-cols-2">
        <div
            class="relative z-10 flex items-center justify-center bg-zinc-50 px-8 py-12 dark:bg-zinc-900 lg:px-16 border-r border-zinc-200 dark:border-zinc-800 shadow-2xl">
            <div class="w-full max-w-sm space-y-8">
                <div class="space-y-2">
                    <x-auth-header :title="__('Welcome Back')" :description="__('Enter your details to access GymWithin')" />
                </div>

                <x-auth-session-status :status="session('status')" />

                <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6">
                    @csrf
                    <flux:input name="email" :label="__('Email')" type="email" placeholder="Your Email" required
                        autofocus />

                    <div class="relative">
                        <flux:input name="password" :label="__('Password')" type="password" placeholder="Your Password"
                            required viewable />
                        @if (Route::has('password.request'))
                            <flux:link class="absolute top-0 end-0 text-xs font-semibold text-orange-500"
                                :href="route('password.request')" wire:navigate>
                                {{ __('Forgot?') }}
                            </flux:link>
                        @endif
                    </div>
                    <flux:checkbox name="remember" :label="__('Remember me')" />
                    <flux:button variant="primary" type="submit" class="
                        w-full h-12
                        shadow-lg shadow-accent/20
                      ">
                        {{ __('Log in') }}
                    </flux:button>
                </form>

                @if (Route::has('register'))
                    <div class="
                                text-center text-sm text-zinc-500
                                dark:text-zinc-400
                              ">
                        {{ __('Don\'t have an account?') }}
                        <flux:link :href="route('register')" class="
                                    font-semibold
                                  " wire:navigate variant="primary">
                            {{ __('Join now') }}
                        </flux:link>
                    </div>
                @endif
            </div>
        </div>

        {{-- Right side of the login pannel --}}
        <div x-data="{ x: 0, y: 0 }"
            @mousemove="x = $event.clientX - $el.getBoundingClientRect().left; y = $event.clientY - $el.getBoundingClientRect().top"
            class="relative hidden lg:flex flex-col items-center justify-center overflow-hidden bg-zinc-890">
            <div class="pointer-events-none absolute -inset-[200px] z-0 opacity-50 transition-all duration-500 ease-out"
                :style="`background: radial-gradient(600px circle at ${x}px ${y}px, rgba(249, 115, 22, 0.15), transparent 50%)`">
            </div>

            <div class="absolute inset-0 z-0">
                <div
                    class="absolute bottom-[-10%] right-[-10%] h-[50%] w-[50%] rounded-full bg-zinc-800/30 blur-[100px]">
                </div>
                <div
                    class="absolute inset-0 opacity-[0.03] [background-image:linear-gradient(to_right,#808080_1px,transparent_1px),linear-gradient(to_bottom,#808080_1px,transparent_1px)] [background-size:40px_40px]">
                </div>
            </div>

            <div class="relative z-10 flex flex-col items-center">
                <a href="{{ route('home') }}" wire:navigate class="group relative mb-12">
                    <div
                        class="absolute -inset-4 rounded-[2rem] bg-orange-500/10 opacity-0 blur-xl transition duration-500 group-hover:opacity-100">
                    </div>
                    <div
                        class="relative flex h-32 w-32 items-center justify-center rounded-3xl border border-white/10 bg-white/5 backdrop-blur-2xl shadow-2xl transition-all duration-700 group-hover:-translate-y-3 group-hover:rotate-3">
                        <img src="{{ asset('Treadmill_Images/logo.png') }}" alt="Logo"
                            class="h-20 w-auto drop-shadow-2xl">
                    </div>
                </a>

                <div class="text-center space-y-2">
                    <h1 class="text-6xl font-black tracking-tighter text-white uppercase italic brand-glow">
                        Gym<span class="text-orange-500">Within</span>
                    </h1>
                    <div class="flex items-center justify-center gap-4">
                        <span class="h-px w-8 bg-zinc-800"></span>
                        <p class="text-sm font-medium uppercase tracking-[0.3em] text-zinc-500">
                            Redefine Your Limits
                        </p>
                        <span class="h-px w-8 bg-zinc-800"></span>
                    </div>
                </div>

                <div class="mt-16 grid grid-cols-3 gap-12 opacity-30 transition-opacity hover:opacity-60 duration-500">
                    <div class="flex flex-col items-center">
                        <span class="text-xl font-bold text-white">01</span>
                        <span class="text-[10px] uppercase tracking-widest text-grey-500">Track</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <span class="text-xl font-bold text-white">02</span>
                        <span class="text-[10px] uppercase tracking-widest text-grey-500">Train</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <span class="text-xl font-bold text-white">03</span>
                        <span class="text-[10px] uppercase tracking-widest text-grey-500">Transform</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts::auth>