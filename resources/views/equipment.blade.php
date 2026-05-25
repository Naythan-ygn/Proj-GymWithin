@extends('layouts.client-app')

@section('title', 'Find Your Perfect Gear - GymWithin')

@section('content')
    {{-- Hero Section --}}
    <section class="equipment-hero">
        <div class="container mx-auto px-4">
            <div class="max-w-2xl">
                {{-- Added 'loading-shield' to trigger the playEntrance() function in animation.js --}}
                <h1 class="loading-shield text-4xl md:text-6xl font-extrabold text-white mb-4">
                    Find Your <span class="text-orange-500">Perfect</span> Gear
                </h1>

                <p class="loading-shield text-lg text-gray-300 mb-8">
                    Browse our curated collection of premium fitness equipment.
                </p>

                <div class="loading-shield">
                    <a href="#equipment-grid"
                        class="magnetic-btn inline-block py-3 px-10 rounded-full bg-gradient-to-r from-[#ff6b35] to-[#f7931e] text-white font-bold text-lg">
                        Shop Now
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Main Content Section (Sidebar + Grid) --}}
    <section class="bg-black text-white py-10" id="equipment-grid">
        <div class="container mx-auto px-4 equipment-container">

            {{-- Sidebar Filters --}}
            <aside class="sidebar-filters">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold mb-6">Filters</h2>
                </div>

                <div class="filter-group mb-4">
                    <h3 class="cursor-pointer {{ !request('category') ? 'text-orange-500' : '' }}">
                        <a href="{{ route('equipment') }}">
                            <i class="fas fa-th-large filter-icon"></i> All Equipment
                        </a>
                    </h3>
                </div>

                @foreach($categories as $cat)
                    <div class="filter-group mb-4">
                        <h3 class="cursor-pointer {{ request('category') == $cat->slug ? 'text-orange-500' : '' }}">
                            <a href="{{ route('equipment', ['category' => $cat->slug]) }}">
                                {{ $cat->name }}
                            </a>
                        </h3>
                    </div>
                @endforeach
            </aside>

            {{-- Right Side Grid --}}
            <div class="equipment-grid-section">
                <h2 class="text-3xl font-bold mb-8">Our Full Equipment Range</h2>

                <div class="grid-container">
                    {{-- Loop through equipment passed from controller --}}
                    @foreach ($products as $item)
                        <div class="product-card fade-in" data-fade>
                            {{-- Conditional Price Badge --}}
                            @if ($item['price'])
                                <div class="price-badge">${{ $item['price'] }}</div>
                            @endif

                            <div class="product-image-wrapper">
                                <img src="{{ $item->image_path ? asset('storage/' . $item->image_path) : 'https://placehold.co/400' }}"
                                    alt="{{ $item->name }}">
                            </div>

                            <div class="product-details">
                                <h3 class="product-title" title="{{ $item['name'] }}">{{ $item['name'] }}</h3>
                                {{-- Update your button/link to this --}}
                                <a href="{{ route('products.show', $item->sku) }}" class="view-details-btn">
                                    View Details <i class="fas fa-arrow-right ml-2 text-xs"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </section>
@endsection
