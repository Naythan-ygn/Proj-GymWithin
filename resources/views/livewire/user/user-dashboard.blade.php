<div>
    {{-- Hero Section --}}
    <section class="equipment-hero relative" style="height: 40vh; min-height: 350px;">
        <div class="container mx-auto px-4 z-10 relative">
            <div class="max-w-3xl">
                <h1 class="loading-shield text-4xl md:text-5xl font-extrabold text-white mb-4">
                    Welcome Back, <span class="text-orange-500">{{ auth()->user()->name ?? 'CUSTOMER' }}</span>
                </h1>
                <p class="loading-shield text-lg text-gray-300 mb-8">
                    Ready to crush your goals? Explore new arrivals and top categories tailored for your training
                    style.
                </p>
            </div>
        </div>
    </section>

    {{-- Main Dashboard Content --}}
    <section class="bg-black text-white py-12 min-h-screen" id="user-dashboard">
        <div class="container mx-auto px-4">

            {{-- Categories Row --}}
            <div class="mb-16 fade-in" data-fade>
                <h2 class="text-3xl font-bold mb-8">Browse by Category</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    @foreach ($categories as $category)
                    <a href="{{ route('equipment') }}?category={{ strtolower($category['name']) }}"
                        class="glass-card m-0 group flex flex-col items-center justify-center p-8 rounded-2xl hover:border-orange-500 transition-all duration-300 transform hover:-translate-y-2 cursor-pointer text-center">
                        <i
                            class="{{ $category['icon'] }} text-4xl text-gray-400 group-hover:text-orange-500 mb-4 transition-colors"></i>
                        <h3 class="text-xl font-semibold">{{ $category['name'] }}</h3>
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Recommended Products Grid --}}
            <div class="equipment-grid-section fade-in" data-fade>
                <div class="flex justify-between items-end mb-8">
                    <h2 class="text-3xl font-bold">Recommended For You</h2>
                    <a href="{{ route('equipment') }}"
                        class="text-orange-500 hover:text-orange-400 font-medium flex items-center transition-colors">
                        View All Equipment <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>

                <div class="grid-container">
                    @forelse ($products as $item)
                        <div class="product-card fade-in" data-fade>
                            <div class="price-badge">${{ number_format($item->price, 2) }}</div>
                            <div class="product-image-wrapper">
                                <img src="{{ asset('Equipment/' . $item->image) }}" alt="{{ $item->name }}">
                            </div>
                            <div class="product-details">
                                <p class="text-xs text-orange-500 font-semibold mb-1 uppercase tracking-wider">
                                    {{ $item->category->name ?? 'Gear' }}
                                </p>
                                <h3 class="product-title" title="{{ $item->name }}">{{ $item->name }}</h3>
                                {{-- Update your button/link to this --}}
                                <a href="{{ route('products.show', $item->sku) }}" class="view-details-btn">
                                    View Details <i class="fas fa-arrow-right ml-2 text-xs"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center text-gray-500 py-8">
                            No products found. Add some from the admin dashboard!
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
</div>
