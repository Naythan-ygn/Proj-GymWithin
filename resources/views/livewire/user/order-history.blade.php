<section class="bg-black text-white min-h-auto py-20 relative">
    {{-- Global Loading Spinner: Shows when ANY wire:target "showOrder" is active --}}
    <div wire:loading wire:target="showOrder"
        class="fixed inset-0 z-[10001] flex items-center justify-center bg-black/60 backdrop-blur-md">
        <div class="flex flex-col items-center">
            <div class="size-16 border-4 border-orange-500 border-t-transparent rounded-full animate-spin mb-4"></div>
            <p class="text-orange-500 font-bold tracking-widest uppercase text-xs">Fetching Order Details...</p>
        </div>
    </div>

    <div class="container mx-auto mt-12 px-4 maxg-w-5xl">
        <header class="mb-12">
            <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight">
                My <span class="text-orange-500">Orders</span>
            </h1>
        </header>

        <div class="space-y-6">
            @forelse ($orders as $order)
                <div class="glass-panel p-6 md:p-8 rounded-3xl border border-white/10 hover:border-orange-500/30 transition-all duration-300 group cursor-pointer shadow-xl"
                    wire:click="showOrder({{ $order->id }})" wire:key="order-{{ $order->id }}">

                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div class="flex items-center gap-6">
                            <div
                                class="size-16 rounded-2xl bg-orange-500/10 border border-orange-500/20 flex items-center justify-center text-orange-500">
                                <i class="fas fa-box-open text-2xl"></i>
                            </div>
                            <div class="space-y-1">
                                <h3 class="font-bold text-xl text-white group-hover:text-orange-500 transition-colors">
                                    #{{ $order->order_number }}</h3>
                                <p class="text-zinc-500 text-sm">Placed on {{ $order->created_at->format('M d, Y') }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between md:justify-end gap-10">
                            <div class="text-right">
                                <p class="text-[10px] text-zinc-500 uppercase tracking-widest mb-1">Total</p>
                                <p class="font-black text-2xl text-white">${{ number_format($order->total_price, 2) }}
                                </p>
                            </div>
                            <div
                                class="size-12 rounded-full bg-white/5 border border-white/10 flex items-center justify-center group-hover:bg-orange-500 transition-all">
                                <i class="fas fa-chevron-right text-sm text-zinc-400 group-hover:text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-20 border border-dashed border-white/10 rounded-[3rem]">
                    <p class="text-zinc-500">No orders found.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-12">
            {{ $orders->links() }}
        </div>
    </div>

    {{-- Modal Overlay --}}
    @if ($this->selectedOrder)
        <div x-data="{ show: true }" x-init="$watch('show', value => { if (!value) $wire.set('selectedOrderId', null) })"
            class="fixed inset-0 z-[9999] flex items-center justify-center p-4">

            {{-- 1. Dark Backdrop --}}
            <div class="absolute inset-0 bg-black/90 backdrop-blur-md" x-on:click="show = false">
            </div>

            {{-- 2. Modal Container --}}
            <div class="relative z-[10000] bg-gray-950 border border-white/10 w-full max-w-2xl rounded-[2rem] shadow-2xl flex flex-col max-h-[90vh]"
                x-show="show" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">

                {{-- Header --}}
                <div class="p-8 pb-4 flex justify-between items-start">
                    <div>
                        <h2 class="text-2xl font-black text-white">Order Details</h2>
                        <p class="text-orange-500 font-bold">#{{ $this->selectedOrder->order_number }}</p>
                    </div>
                    <button type="button" x-on:click="show = false"
                        class="size-10 rounded-full bg-white/5 flex items-center justify-center hover:bg-orange-500 transition-colors group cursor-pointer">
                        <i class="fas fa-times text-zinc-400 group-hover:text-white"></i>
                    </button>
                </div>

                {{-- Body --}}
                <div class="p-8 pt-0 overflow-y-auto custom-scrollbar">
                    <div class="space-y-4">
                        @foreach ($this->selectedOrder->items as $item)
                            <div class="flex items-center gap-4 p-4 rounded-2xl bg-white/5 border border-white/5">
                                <div
                                    class="size-12 rounded-lg bg-zinc-800 flex items-center justify-center text-zinc-500 text-xs">
                                    IMG
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-white text-sm">{{ $item->product->name ?? 'Product' }}
                                    </h4>
                                    <p class="text-zinc-500 text-xs">Qty: {{ $item->quantity }}</p>
                                </div>
                                <div class="text-right font-bold text-white text-sm">
                                    ${{ number_format($item->price * $item->quantity, 2) }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8 pt-6 border-t border-white/10">
                        <div class="flex justify-between text-white text-xl font-black">
                            <span>Total Paid</span>
                            <span
                                class="text-orange-500">${{ number_format($this->selectedOrder->total_price, 2) }}</span>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="p-8 pt-0">
                    <button type="button" x-on:click="show = false"
                        class="w-full py-4 bg-white/5 border border-white/10 rounded-xl font-bold hover:bg-white/10 transition-all text-white cursor-pointer">
                        Back to Orders
                    </button>
                </div>
            </div>
        </div>
    @endif
</section>
