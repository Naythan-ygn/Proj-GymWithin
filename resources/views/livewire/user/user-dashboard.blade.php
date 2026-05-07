<div>
    @if (!empty($paymentToasts))
        <div x-data="{ toasts: @js($paymentToasts) }" class="fixed right-4 top-20 z-[10050] space-y-3 w-[min(24rem,calc(100vw-2rem))]">
            <template x-for="(toast, index) in toasts" :key="`${toast.order_number}-${index}`">
                <div x-data="{ show: true }"
                    x-init="setTimeout(() => show = false, 4500); setTimeout(() => { toasts.splice(index, 1) }, 5000)"
                    x-show="show" x-transition
                    class="rounded-2xl border px-5 py-4 shadow-2xl"
                    :class="toast.payment_status === 'approved'
                        ? 'border-green-500/30 bg-green-500/10 text-green-100'
                        : 'border-red-500/30 bg-red-500/10 text-red-100'">
                    <p class="font-semibold" x-text="`Order #${toast.order_number} payment ${toast.payment_status}.`"></p>
                    <p class="mt-1 text-sm opacity-90"
                        x-text="toast.payment_notes || (toast.payment_status === 'approved'
                            ? 'Your order is confirmed and ready for fulfillment.'
                            : 'Please contact support or upload a clearer receipt.')"></p>
                </div>
            </template>
        </div>
    @endif

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
            @if (session('success'))
                <div class="mb-8 rounded-2xl border border-green-500/30 bg-green-500/10 px-5 py-4 text-green-100">
                    {{ session('success') }}
                </div>
            @endif

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
