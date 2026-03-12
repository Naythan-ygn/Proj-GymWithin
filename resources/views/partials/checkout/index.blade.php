@extends('layouts.client-app')

@section('title', 'Secure Checkout - GymWithin')

@section('content')
    <section class="bg-black text-white min-h-screen py-20">
        <div class="container mx-auto px-4">
            <div class="mb-8">
                <a href="{{ route('cart.index') }}"
                    class="inline-flex items-center gap-2 text-zinc-400 hover:text-orange-500 transition-colors group">
                    <div
                        class="size-8 rounded-full bg-white/5 border border-white/10 flex items-center justify-center group-hover:border-orange-500/50">
                        <i class="fas fa-chevron-left text-xs"></i>
                    </div>
                    <span class="text-sm font-medium">Back to Shopping Cart</span>
                </a>
            </div>
            <h1 class="text-4xl font-extrabold mb-10">Secure <span class="text-orange-500">Checkout</span></h1>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                {{-- Left Side: Shipping Form --}}
                <div class="glass-panel p-8 rounded-3xl border border-white/10">
                    <h2 class="text-2xl font-bold mb-6 flex items-center gap-3">
                        <i class="fas fa-truck text-orange-500"></i> Shipping Information
                    </h2>

                    <form action="{{ route('checkout.process') }}" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-zinc-400 mb-2">Full Name</label>
                            <input type="text" value="{{ auth()->user()->name }}" disabled
                                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-zinc-500 cursor-not-allowed">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-zinc-400 mb-2">Shipping Address</label>
                            <textarea name="shipping_address" rows="4" required
                                placeholder="Enter your full delivery address in Myanmar..."
                                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition outline-none"></textarea>
                            @error('shipping_address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="pt-4">
                            <h3 class="text-lg font-semibold mb-4">Payment Method</h3>
                            <div class="p-4 rounded-xl border border-orange-500/50 bg-orange-500/5 flex items-center gap-4">
                                <i class="fas fa-money-bill-wave text-orange-500"></i>
                                <div>
                                    <p class="font-bold text-sm">Cash on Delivery (COD)</p>
                                    <p class="text-xs text-zinc-400">Pay when your equipment arrives.</p>
                                </div>
                                <i class="fas fa-check-circle ml-auto text-orange-500"></i>
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full py-4 rounded-full bg-gradient-to-r from-[#ff6b35] to-[#f7931e] text-white font-bold text-lg hover:opacity-90 transition transform hover:scale-[1.01]">
                            Place Order (${{ number_format($total, 2) }})
                        </button>
                    </form>
                </div>

                {{-- Right Side: Order Summary --}}
                <div class="space-y-6">
                    <div class="glass-panel p-8 rounded-3xl border border-white/5 bg-zinc-900/30">
                        <h2 class="text-xl font-bold mb-6">Order Summary</h2>
                        <div class="space-y-4 max-h-96 overflow-y-auto pr-2 mb-6">
                            @foreach($cart as $id => $item)
                                <div class="flex items-center gap-4">
                                    <div
                                        class="size-16 rounded-lg bg-zinc-800 border border-white/5 overflow-hidden flex-shrink-0">
                                        <img src="{{ $item['image'] ? asset('storage/' . $item['image']) : 'https://placehold.co/100' }}"
                                            class="object-cover size-full">
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-medium text-sm line-clamp-1">{{ $item['name'] }}</p>
                                        <p class="text-xs text-zinc-500">{{ $item['quantity'] }} x
                                            ${{ number_format($item['price'], 2) }}</p>
                                    </div>
                                    <p class="font-bold text-sm text-orange-500">
                                        ${{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                                </div>
                            @endforeach
                        </div>

                        <div class="border-t border-white/10 pt-6 space-y-3">
                            <div class="flex justify-between text-zinc-400 text-sm">
                                <span>Subtotal</span>
                                <span>${{ number_format($total, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-zinc-400 text-sm">
                                <span>Shipping</span>
                                <span class="text-green-500">Free</span>
                            </div>
                            <div class="flex justify-between text-xl font-bold pt-2">
                                <span>Total</span>
                                <span class="text-orange-500">${{ number_format($total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
