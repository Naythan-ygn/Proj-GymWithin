@extends('layouts.client-app')

@section('title', 'About Us - GymWithin')

@section('content')
    <section class="about-hero">
        <div class="container mx-auto px-6">
            <div class="max-w-2xl loading-shield">
                <h1 class="text-6xl font-black mb-4 leading-tight">Our Story</h1>
                <p class="text-xl text-gray-300 mb-8">
                    Building a Stronger Community, Together. We started with a simple vision: to make elite fitness
                    accessible to everyone.
                </p>
                <a href="#team"
                    class="magnetic-btn inline-block py-3 px-10 rounded-full bg-gradient-to-r from-[#ff6b35] to-[#f7931e] text-white font-bold text-lg">Meet
                    The Team</a>
            </div>
        </div>
    </section>

    <section class="content-section">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row items-center gap-12">
                <div class="md:w-1/2 loading-shield">
                    <h2 class="text-4xl font-bold mb-6">Our Commitment to Your Fitness Journey</h2>
                    <p class="text-gray-400 leading-relaxed mb-6">
                        GymWithin is a leading brand committed to empowering health goals. Founded in 2025, our energy is
                        aimed at providing the best balance of resources for fitness enthusiasts where our community and
                        facilities thrive.
                    </p>
                    <p class="text-gray-400 leading-relaxed">
                        Beyond just equipment, we focus on the holistic experience—ensuring you have the support,
                        environment, and motivation to push your limits every single day.
                    </p>
                </div>

                <div class="md:w-1/2 about-image-container loading-shield">
                    <img src="{{ asset('Treadmill_Images/about_us_profile.jpg') }}" alt="Our Community in Action">
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 bg-black">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold mb-12">Why We Do It</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="p-8 bg-zinc-900 rounded-2xl border border-zinc-800">
                    <i class="fas fa-heart text-orange-500 text-3xl mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Passion</h3>
                    <p class="text-gray-500">We love what we do and the people we help every day.</p>
                </div>
                <div class="p-8 bg-zinc-900 rounded-2xl border border-zinc-800">
                    <i class="fas fa-users text-orange-500 text-3xl mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Community</h3>
                    <p class="text-gray-500">You aren't just a member; you're part of the family.</p>
                </div>
                <div class="p-8 bg-zinc-900 rounded-2xl border border-zinc-800">
                    <i class="fas fa-trophy text-orange-500 text-3xl mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Excellence</h3>
                    <p class="text-gray-500">Only the best equipment and training for our members.</p>
                </div>
            </div>
        </div>
    </section>
@endsection
