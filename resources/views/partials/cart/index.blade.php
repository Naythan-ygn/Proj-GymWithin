@extends('layouts.client-app')

@section('content')
    <section class="bg-black text-white min-h-screen py-20">
        <div class="container mx-auto px-4">
            <h1 class="text-4xl font-extrabold mb-10">Your <span class="text-orange-500">Cart</span></h1>

            @if(session('cart'))
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {{-- Item List --}}
                    <div class="lg:col-span-2 space-y-4">
                        @foreach(session('cart') as $id => $details)
                            <div class="glass-panel p-6 rounded-2xl border border-white/10 flex items-center gap-6">
                                <img src="{{ $details['image'] ? asset('storage/' . $details['image']) : 'https://placehold.co/100' }}"
                                    class="size-20 rounded-lg object-cover">
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold">{{ $details['name'] }}</h3>
                                    <p class="text-zinc-500 text-sm">SKU: {{ $details['sku'] }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-xl">${{ number_format($details['price'] * $details['quantity'], 2) }}
                                    </p>

                                    <div class="flex items-center gap-4 bg-zinc-800/50 rounded-xl mt-2 p-2 border border-white/5">
                                        {{-- Decrease Button --}}
                                        <form action="{{ route('cart.update', $id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="action" value="decrease">
                                            <button type="submit"
                                                class="size-8 flex items-center justify-center rounded-lg bg-zinc-700 hover:bg-orange-500 transition {{ $details['quantity'] <= 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                {{ $details['quantity'] <= 1 ? 'disabled' : '' }}>
                                                <i class="fas fa-minus text-xs"></i>
                                            </button>
                                        </form>

                                        <span class="text-lg font-bold w-6 text-center">{{ $details['quantity'] }}</span>

                                        {{-- Increase Button --}}
                                        <form action="{{ route('cart.update', $id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="action" value="increase">
                                            <button type="submit"
                                                class="size-8 flex items-center justify-center rounded-lg bg-zinc-700 hover:bg-orange-500 transition">
                                                <i class="fas fa-plus text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                    <form action="{{ route('cart.remove', $id) }}" method="POST" class="mt-4">
                                        @csrf
                                        <button type="submit"
                                            class="text-zinc-500 hover:text-red-500 text-xs flex items-center justify-end gap-2 transition ml-auto group">
                                            <span class="opacity-0 group-hover:opacity-100 transition-opacity">Remove Item</span>
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Summary Panel --}}
                    <div class="glass-panel p-8 rounded-3xl border border-orange-500/20 h-fit bg-zinc-900/50">
                        <h2 class="text-2xl font-bold mb-6">Order Summary</h2>
                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between">
                                <span class="text-zinc-400">Subtotal</span>
                                <span>${{ number_format($total, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-zinc-400">Shipping</span>
                                <span class="text-green-500">FREE</span>
                            </div>
                            <hr class="border-white/10">
                            <div class="flex justify-between text-xl font-bold">
                                <span>Total</span>
                                <span class="text-orange-500">${{ number_format($total, 2) }}</span>
                            </div>
                        </div>
                        <a href="{{ route('checkout.index') }}"
                            class="w-full py-4 block text-center rounded-full bg-white text-black font-bold hover:bg-orange-500 hover:text-white transition">
                            Proceed to Checkout
                        </a>
                    </div>
                </div>
            @else
                <div class="text-center py-20">
                    <i class="fas fa-shopping-basket text-6xl text-zinc-800 mb-6"></i>
                    <p class="text-zinc-500 text-xl mb-8">Your cart is feeling a bit light.</p>
                    <a href="{{ route('equipment') }}" class="px-8 py-3 bg-orange-500 rounded-full font-bold">Start Shopping</a>
                </div>
            @endif
        </div>
    </section>
@endsection
