@extends('layouts.client-app')

@section('title', 'Benefits & Features - GymWithin')

{{-- Inject the page-specific CSS --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/benefits.css') }}">
@endpush

@section('content')
    {{-- HERO SECTION --}}
    <section class="benefits-hero-custom relative w-full overflow-hidden">
        {{-- 
         We keep this div with the ID for animation.js parallax effect.
         It now inherits the background image and gradient from the CSS.
    --}}
        <div id="heroImage" class="hero-image"></div>

        {{-- Hero Content --}}
        <div class="relative z-10 container mx-auto px-6 lg:px-16 hero-transition loading-shield">
            <div class="max-w-3xl">
                <h1 class="text-4xl md:text-6xl lg:text-7xl font-black text-white mb-4 leading-tight">
                    Unlock Your <br>
                    <span class="gradient-text">Full Potential</span>
                </h1>
                <p class="text-lg md:text-xl text-gray-300 mb-8 max-w-xl">
                    Experience the transformative advantages of a GymWithin membership.
                </p>
                <div>
                    <a href="#"
                        class="magnetic-btn inline-block py-3 px-10 rounded-full bg-gradient-to-r from-[#ff6b35] to-[#f7931e] text-white font-bold text-lg">
                        Meet The Team
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- FEATURES SECTION --}}
    <section class="bg-black py-24 px-6 lg:px-16 relative z-20">
        <div class="container mx-auto" data-fade>
            <h2 class="text-3xl lg:text-5xl font-bold text-white mb-4">Comprehensive Wellness</h2>
            <p class="text-gray-400 mb-16 text-lg max-w-3xl">More than just gym, we holistic approach of a GymWithin
                membership</p>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
                {{-- Card 1 --}}
                <div class="feature-card">
                    <div class="card-icon-wrapper">
                        <i class="fa-solid fa-heart-pulse card-icon"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3 gradient-text">Physical Strength</h3>
                    <p class="text-gray-400">Build muscle, increase endurance, and enhance your overall physical capability.
                    </p>
                </div>

                {{-- Card 2 --}}
                <div class="feature-card">
                    <div class="card-icon-wrapper">
                        <i class="fa-solid fa-brain card-icon"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3 gradient-text">Mental Clarity</h3>
                    <p class="text-gray-400">Reduce stress and improve focus through mindfulness and exercise.</p>
                </div>

                {{-- Card 3 --}}
                <div class="feature-card">
                    <div class="card-icon-wrapper">
                        <i class="fa-solid fa-spa card-icon"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3 gradient-text">Recovery & Rest</h3>
                    <p class="text-gray-400">Dedicated zones and programs for essential post-workout recovery.</p>
                </div>

                {{-- Card 4 --}}
                <div class="feature-card">
                    <div class="card-icon-wrapper">
                        <i class="fa-solid fa-users card-icon"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3 gradient-text">Community Support</h3>
                    <p class="text-gray-400">Connect with like-minded individuals in a supportive environment.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- TAILORED SUCCESS & EVENTS SPLIT SECTION --}}
    <section class="bg-black pt-10 pb-32 px-6 lg:px-16 relative z-20">
        <div class="container mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16 xl:gap-24" data-fade>

            {{-- Left Column: Tailored Success --}}
            <div>
                <h2 class="text-3xl lg:text-4xl font-bold text-white mb-6">Tailored for a Success</h2>
                <p class="text-gray-400 mb-12 text-lg">More than just the physical, a holistic approach to health and
                    wellness.</p>

                <div class="tailored-image-container">
                    {{-- Replace with your actual image --}}
                    <img src="{{ asset('Treadmill_Images/Benefits1.png') }}" alt="Personal training session">

                    <ul class="space-y-4 text-gray-300 pl-4">
                        <li class="flex items-center">
                            <i class="fa-solid fa-circle-check gradient-text mr-4 text-xl"></i>
                            <span class="text-lg">Personalized Training Plans</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fa-solid fa-circle-check gradient-text mr-4 text-xl"></i>
                            <span class="text-lg">Nutritional Guidance</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fa-solid fa-circle-check gradient-text mr-4 text-xl"></i>
                            <span class="text-lg">Recovery & Mindfulness Classes</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fa-solid fa-circle-check gradient-text mr-4 text-xl"></i>
                            <span class="text-lg">Exclusive Member Events</span>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Right Column: Exclusive Events --}}
            <div class="flex flex-col justify-center">
                <h2 class="text-3xl lg:text-4xl font-bold text-white mb-6">Exclusive Member Events</h2>
                <p class="text-gray-400 mb-12 text-lg">Join our vibrant community for special workshops, challenges, and
                    social gatherings designed to keep you motivated.</p>

                <div class="grid grid-cols-3 gap-4 mb-12 events-grid">
                    {{-- Replace with your actual event images --}}
                    <img src="{{ asset('Treadmill_Images/benefits2.png') }}" alt="Yoga event">
                    <img src="{{ asset('Treadmill_Images/benefits3.png') }}" alt="Group training">
                    <img src="{{ asset('Treadmill_Images/benefits4.png') }}" alt="Social gathering">
                </div>

                <a href="#"
                    class="magnetic-btn w-full block text-center py-5 rounded-full bg-gradient-to-r from-[#ff6b35] to-[#f7931e] text-white font-bold text-xl transition-transform hover:scale-[1.02]">
                    Explore Events
                </a>
            </div>

        </div>
    </section>
@endsection
