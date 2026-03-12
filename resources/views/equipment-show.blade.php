@extends('layouts.client-app')

@section('title', $product->name . ' - GymWithin')

@section('content')
    <section class="bg-black text-white min-h-screen py-20">
        <div class="container mx-auto px-4">
            {{-- Breadcrumbs --}}
            <nav class="mb-8 flex items-center text-sm text-gray-500">
                <a href="{{ route('equipment') }}" class="hover:text-orange-500 transition">Equipment</a>
                <i class="fas fa-chevron-right mx-3 text-xs"></i>
                <span class="text-gray-300">{{ $product->category->name ?? 'Gear' }}</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
                {{-- Left: Product Image --}}
                <div class="glass-panel p-4 rounded-3xl border border-white/10 overflow-hidden">
                    <div class="aspect-square rounded-2xl overflow-hidden bg-zinc-900">
                        <img src="{{ $product->image_path ? asset('storage/' . $product->image_path) : 'https://placehold.co/800' }}"
                            alt="{{ $product->name }}" class="w-full h-full object-cover">
                    </div>
                </div>

                {{-- Right: Product Info --}}
                <div class="space-y-8">
                    <div>
                        <span
                            class="inline-block px-3 py-1 rounded-full bg-orange-500/10 text-orange-500 text-xs font-bold uppercase tracking-widest mb-4">
                            {{ $product->category->name ?? 'Premium Gear' }}
                        </span>
                        <h1 class="text-4xl md:text-5xl font-extrabold mb-2">{{ $product->name }}</h1>
                        <p class="text-zinc-500 font-mono uppercase tracking-tighter">SKU: {{ $product->sku }}</p>
                    </div>

                    <div class="flex items-baseline gap-4">
                        <span class="text-4xl font-bold text-white">${{ number_format($product->price, 2) }}</span>
                        @if($product->stock <= 5 && $product->stock > 0)
                            <span class="text-orange-500 text-sm font-medium animate-pulse">
                                Only {{ $product->stock }} left in stock!
                            </span>
                        @endif
                    </div>

                    <div class="glass-panel p-6 rounded-2xl border border-white/5 bg-white/5">
                        <h3 class="text-lg font-semibold mb-3">Product Description</h3>
                        <p class="text-gray-400 leading-relaxed">
                            {{ $product->description ?? 'No description provided for this premium item.' }}
                        </p>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex flex-col sm:flex-row gap-4 pt-4">
                        <form action="{{ route('cart.add', $product->id) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit"
                                class="w-full py-4 px-8 rounded-full bg-gradient-to-r from-[#ff6b35] to-[#f7931e] text-white font-bold text-lg hover:opacity-90 transition transform hover:scale-[1.02] cursor-pointer">
                                Add to Cart <i class="fas fa-shopping-cart ml-2"></i>
                            </button>
                        </form>
                        <button
                            class="py-4 px-8 rounded-full border border-white/20 hover:bg-white/5 transition cursor-pointer">
                            <i class="far fa-heart"></i>
                        </button>
                    </div>

                    {{-- Shipping/Returns Info --}}
                    <div class="grid grid-cols-2 gap-4 pt-8 border-t border-white/10">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-truck text-orange-500"></i>
                            <span class="text-sm text-gray-400">Fast MM Delivery</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="fas fa-undo text-orange-500"></i>
                            <span class="text-sm text-gray-400">30-Day Returns</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Related Products Section --}}
            @if($relatedProducts->count() > 0)
                <div class="mt-24">
                    <h2 class="text-3xl font-bold mb-8">Related Equipment</h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        @foreach($relatedProducts as $related)
                            <a href="{{ route('products.show', $related->sku) }}" class="product-card group">
                                <div class="aspect-square rounded-xl overflow-hidden bg-zinc-900 mb-4 border border-white/5">
                                    <img src="{{ $related->image_path ? asset('storage/' . $related->image_path) : 'https://placehold.co/400' }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                </div>
                                <h4 class="font-semibold px-4 group-hover:text-orange-500 transition">{{ $related->name }}</h4>
                                <p class="text-gray-500 px-4 pb-2">${{ number_format($related->price, 2) }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
