@extends('layouts.client-app')

@section('content')
    <section class="hero-wrapper">
        <div class="hero-canvas">
            <img id="heroImage" src="{{ asset('Treadmill_Images/treadmill_hero.webp') }}" alt="Hero" class="hero-image">
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="text-center px-6 max-w-5xl">
                    <h1 class="text-5xl md:text-8xl font-bold tracking-tight mb-6">
                        <span id="heroTitle1" class="loading-shield block">Redefine Your</span>
                        <span id="heroTitle2" class="loading-shield block gradient-text">Fitness Standards</span>
                    </h1>

                    <div id="heroScroll"
                        class="loading-shield absolute bottom-8 left-1/2 transform -translate-x-1/2 flex flex-col items-center gap-2">
                        <!-- Scroll Indicator -->
                        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 flex flex-col items-center gap-2">
                            <span class="text-xs text-gray-400 uppercase tracking-widest">Scroll to explore</span>
                            <div class="w-6 h-10 border-2 border-white/30 rounded-full flex justify-center p-1">
                                <div class="w-1.5 h-3 bg-white/60 rounded-full animate-bounce"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="equipment" class="x-6 bg-black">
        <div class="text-center mb-16 fade-in" data-fade>
            <h2 class="text-4xl md:text-5xl lg:text-6xl font-bold tracking-tight mb-4">
                Premium <span class="gradient-text">Equipment</span>
            </h2>
            <p class="text-gray-400 text-lg max-w-2xl mx-auto font-light">
                Engineered for performance. Built to inspire greatness.
            </p>
        </div>
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6">
            @php
                $products = [
                    ['name' => 'Pro Treadmill X1', 'price' => '$2,499', 'emoji' => '🏃', 'desc' => 'AI tracking.'],
                    ['name' => 'PowerRack Elite', 'price' => '$1,899', 'emoji' => '💪', 'desc' => 'Steel build.'],
                    ['name' => 'Spin Cycle Pro', 'price' => '$1,299', 'emoji' => '🚴', 'desc' => 'Magnetic res.']
                ];
            @endphp

            @foreach($products as $product)
                <div class="glass-card rounded-3xl overflow-hidden group fade-in" data-fade>
                    <div class="aspect-square flex items-center justify-center text-8xl">
                        {{ $product['emoji'] }}
                    </div>
                    <div class="p-4">
                        <h3 class="text-2xl font-bold mb-2">{{ $product['name'] }}</h3>
                        <p class="text-gray-400 mb-6">{{ $product['desc'] }}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-3xl font-bold">{{ $product['price'] }}</span>
                            <button class="bg-white text-black px-6 py-2 rounded-full font-semibold">Learn More</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

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

                <x-feature-block icon="🏆" title="Premium Quality"
                    description="Commercial-grade materials engineered for a lifetime of performance" />

                <x-feature-block icon="🚚" title="White Glove Service"
                    description="Complimentary delivery and professional installation included" />

                <x-feature-block icon="🛡️" title="Lifetime Warranty"
                    description="Comprehensive coverage on all structural components" />

                <x-feature-block icon="💬" title="24/7 Support"
                    description="Expert guidance available whenever you need assistance" />
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
@endsection