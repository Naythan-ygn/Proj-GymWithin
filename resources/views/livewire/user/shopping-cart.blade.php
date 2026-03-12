<section class="bg-black text-white min-h-screen py-20">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl font-extrabold mb-10">Your <span class="text-orange-500">Cart</span></h1>

        @if(count($cart) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Item List --}}
                <div class="lg:col-span-2 space-y-4">
                    @foreach($cart as $id => $details)
                        <div class="glass-panel p-6 rounded-2xl border border-white/10 flex items-center gap-6"
                            wire:key="item-{{ $id }}">
                            <img src="{{ $details['image'] ? asset('storage/' . $details['image']) : 'https://placehold.co/100' }}"
                                class="size-20 rounded-lg object-cover">

                            <div class="flex-1">
                                <h3 class="text-lg font-bold">{{ $details['name'] }}</h3>
                                <p class="text-zinc-500 text-sm">SKU: {{ $details['sku'] }}</p>

                                {{-- Quantity Selector --}}
                                <div
                                    class="flex items-center gap-4 mt-4 bg-zinc-900 w-fit rounded-xl p-1 border border-white/5">
                                    <button wire:click="updateQuantity({{ $id }}, 'decrease')"
                                        class="size-8 flex items-center justify-center rounded-lg hover:bg-orange-500 transition disabled:opacity-50"
                                        {{ $details['quantity'] <= 1 ? 'disabled' : '' }}>
                                        <i class="fas fa-minus text-xs"></i>
                                    </button>

                                    <span class="text-md font-bold w-6 text-center">{{ $details['quantity'] }}</span>

                                    <button wire:click="updateQuantity({{ $id }}, 'increase')"
                                        class="size-8 flex items-center justify-center rounded-lg hover:bg-orange-500 transition">
                                        <i class="fas fa-plus text-xs"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="text-right">
                                <p class="font-bold text-xl text-orange-500">
                                    ${{ number_format($details['price'] * $details['quantity'], 2) }}</p>
                                <button wire:click="removeItem({{ $id }})" class="text-red-500 text-xs hover:underline mt-2">
                                    <i class="fas fa-trash-alt mr-1"></i> Remove
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Summary Panel --}}
                <div class="glass-panel p-8 rounded-3xl border border-orange-500/20 h-fit bg-zinc-900/50">
                    <h2 class="text-2xl font-bold mb-6">Order Summary</h2>
                    <div class="space-y-4 mb-6 text-sm">
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

                    {{-- Checkout Link --}}
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
