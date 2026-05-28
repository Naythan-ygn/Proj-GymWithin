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
                        <a href="{{ route('equipment', array_filter(['search' => request('search')])) }}">
                            <i class="fas fa-th-large filter-icon"></i> All Equipment
                        </a>
                    </h3>
                </div>

                @foreach($categories as $cat)
                    <div class="filter-group mb-4">
                        <h3 class="cursor-pointer {{ request('category') == $cat->slug ? 'text-orange-500' : '' }}">
                            <a
                                href="{{ route('equipment', array_filter(['category' => $cat->slug, 'search' => request('search')])) }}">
                                {{ $cat->name }}
                            </a>
                        </h3>
                    </div>
                @endforeach
            </aside>

            {{-- Right Side Grid --}}
            <div class="equipment-grid-section">
                <h2 class="text-3xl font-bold mb-8">Our Full Equipment Range</h2>

                <form method="GET" action="{{ route('equipment') }}"
                    class="mb-8 flex flex-col gap-3 md:flex-row md:items-center">
                    <input type="text" name="search" value="{{ old('search', request('search')) }}"
                        placeholder="Search equipment by name, SKU, or description..."
                        class="w-full rounded-xl border border-zinc-700 bg-zinc-900 px-4 py-3 text-white placeholder:text-zinc-500 focus:border-orange-500 focus:outline-none">

                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif

                    <button type="submit"
                        class="rounded-full bg-orange-500 px-6 py-3 font-semibold text-white hover:bg-orange-600">
                        Search
                    </button>
                </form>

                @if($search)
                    <p class="mb-6 text-orange-300">
                        Showing {{ $products->count() }} result{{ $products->count() === 1 ? '' : 's' }} for "{{ $search }}"
                    </p>
                @endif

                @if($products->isEmpty())
                    <div class="rounded-3xl border border-zinc-800 bg-zinc-950 p-12 text-center text-zinc-400">
                        <p class="mb-4 text-lg font-semibold text-white">No equipment matched your search criteria.</p>
                        <p>Try a broader keyword or clear the search field.</p>
                    </div>
                @else
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
                                    <a href="{{ route('products.show', $item->sku) }}" class="view-details-btn">
                                        View Details <i class="fas fa-arrow-right ml-2 text-xs"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </section>
@endsection
