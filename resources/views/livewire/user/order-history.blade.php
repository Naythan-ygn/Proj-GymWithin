<section class="bg-black text-white min-h-auto py-20 relative">
    @if (!empty($paymentToasts))
        <div x-data="{ toasts: @js($paymentToasts) }"
            class="fixed right-4 top-20 z-[10050] space-y-3 w-[min(24rem,calc(100vw-2rem))]">
            <template x-for="(toast, index) in toasts" :key="`${toast.order_number}-${index}`">
                <div x-data="{ show: true }"
                    x-init="setTimeout(() => show = false, 4500); setTimeout(() => { toasts.splice(index, 1) }, 5000)"
                    x-show="show" x-transition class="rounded-2xl border px-5 py-4 shadow-2xl" :class="toast.payment_status === 'approved'
                            ? 'border-green-500/30 bg-green-500/10 text-green-100'
                            : 'border-red-500/30 bg-red-500/10 text-red-100'">
                    <p class="font-semibold" x-text="`Order #${toast.order_number} payment ${toast.payment_status}.`"></p>
                    <p class="mt-1 text-sm opacity-90" x-text="toast.payment_notes || (toast.payment_status === 'approved'
                                ? 'Your payment was accepted.'
                                : 'Your uploaded receipt was rejected.')"></p>
                </div>
            </template>
        </div>
    @endif

    {{-- Global Loading Spinner: Shows when ANY wire:target "showOrder" is active --}}
    <div wire:loading wire:target="showOrder"
        class="fixed inset-0 z-[10001] flex items-center justify-center bg-black/60 backdrop-blur-md">
        <div class="flex flex-col items-center">
            <div class="size-16 border-4 border-orange-500 border-t-transparent rounded-full animate-spin mb-4"></div>
            <p class="text-orange-500 font-bold tracking-widest uppercase text-xs">Fetching Order Details...</p>
        </div>
    </div>

    <div class="container mx-auto mt-12 px-4 max-w-5xl">
        <header class="mb-12">
            <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight">
                My <span class="text-orange-500">Orders</span>
            </h1>
        </header>

        <div class="space-y-6">
            @forelse ($orders as $order)
                <button type="button"
                    class="glass-panel w-full text-left p-6 md:p-8 rounded-3xl border border-white/10 hover:border-orange-500/30 focus:outline-none focus:ring-2 focus:ring-orange-500/70 transition-all duration-300 group cursor-pointer shadow-xl"
                    wire:click="showOrder({{ $order->id }})" wire:key="order-{{ $order->id }}"
                    aria-label="Preview details for order {{ $order->order_number }}">

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
                            <div class="text-right">
                                <p class="text-[10px] text-zinc-500 uppercase tracking-widest mb-1">Payment</p>
                                <span
                                    class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $order->payment_status === 'approved' ? 'bg-green-500/15 text-green-300' : ($order->payment_status === 'rejected' ? 'bg-red-500/15 text-red-300' : 'bg-amber-500/15 text-amber-300') }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </div>
                            <div class="hidden sm:inline-flex items-center gap-2 rounded-full bg-white/5 border border-white/10 px-4 py-2 text-xs font-bold uppercase tracking-wider text-zinc-300 group-hover:bg-orange-500 group-hover:text-white transition-all">
                                <i class="fas fa-eye text-sm"></i>
                                Preview
                            </div>
                        </div>
                    </div>
                </button>
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
    @if ($selectedOrder)
        <div x-data="{ show: true, close() { this.show = false; setTimeout(() => $wire.closeOrder(), 200) } }"
            x-on:keydown.escape.window="close()"
            class="fixed inset-0 z-[9999] flex items-center justify-center p-4">

            {{-- 1. Dark Backdrop --}}
            <div class="absolute inset-0 bg-black/90 backdrop-blur-md" x-on:click="close()">
            </div>

            {{-- 2. Modal Container --}}
            <div class="relative z-[10000] bg-gray-950 border border-white/10 w-full max-w-2xl rounded-[2rem] shadow-2xl flex flex-col max-h-[90vh]"
                x-show="show" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">

                {{-- Header --}}
                <div class="p-8 pb-4 flex justify-between items-start">
                    <div>
                        <h2 class="text-2xl font-black text-white">Order Preview</h2>
                        <p class="text-orange-500 font-bold">#{{ $selectedOrder->order_number }}</p>
                    </div>
                    <button type="button" x-on:click="close()"
                        class="size-10 rounded-full bg-white/5 flex items-center justify-center hover:bg-orange-500 transition-colors group cursor-pointer">
                        <i class="fas fa-times text-zinc-400 group-hover:text-white"></i>
                    </button>
                </div>

                {{-- Body --}}
                <div class="p-8 pt-0 overflow-y-auto custom-scrollbar">
                    <div class="space-y-4">
                        @foreach ($selectedOrder->items as $item)
                            <div class="flex items-center gap-4 p-4 rounded-2xl bg-white/5 border border-white/5">
                                <div class="size-14 shrink-0 overflow-hidden rounded-xl bg-zinc-800 border border-white/10">
                                    <img src="{{ $item->product?->image_path ? asset('storage/' . $item->product->image_path) : 'https://placehold.co/120?text=No+Image' }}"
                                        alt="{{ $item->product?->name ?? 'Product image' }}"
                                        class="size-full object-cover">
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-white text-sm">{{ $item->product->name ?? 'Product' }}
                                    </h4>
                                    <p class="text-zinc-500 text-xs">Qty: {{ $item->quantity }} x
                                        ${{ number_format($item->price, 2) }}</p>
                                </div>
                                <div class="text-right font-bold text-white text-sm">
                                    ${{ number_format($item->price * $item->quantity, 2) }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8 pt-6 border-t border-white/10">
                        <div class="mb-4 grid gap-4 sm:grid-cols-2">
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                <p class="mb-1 text-[10px] font-bold uppercase tracking-widest text-zinc-500">Order Date
                                </p>
                                <p class="text-sm text-white">{{ $selectedOrder->created_at->format('F d, Y') }}</p>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                <p class="mb-1 text-[10px] font-bold uppercase tracking-widest text-zinc-500">Order Status
                                </p>
                                <p class="text-sm font-semibold text-white">{{ ucfirst($selectedOrder->status) }}</p>
                            </div>
                        </div>
                        <div class="mb-4 rounded-2xl border border-white/10 bg-white/5 p-4">
                            <p class="mb-1 text-[10px] font-bold uppercase tracking-widest text-zinc-500">Shipping Address</p>
                            <p class="text-sm leading-relaxed text-zinc-300">
                                {{ $selectedOrder->shipping_address ?: 'No shipping address provided.' }}</p>
                        </div>
                        <div class="mb-4 flex items-center justify-between">
                            <span class="text-zinc-400">Payment Status</span>
                            <span
                                class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $selectedOrder->payment_status === 'approved' ? 'bg-green-500/15 text-green-300' : ($selectedOrder->payment_status === 'rejected' ? 'bg-red-500/15 text-red-300' : 'bg-amber-500/15 text-amber-300') }}">
                                {{ ucfirst($selectedOrder->payment_status) }}
                            </span>
                        </div>
                        @if ($selectedOrder->payment_notes)
                            <div class="mb-4 rounded-2xl border border-white/10 bg-white/5 p-4 text-sm text-zinc-300">
                                {{ $selectedOrder->payment_notes }}
                            </div>
                        @endif
                        <div class="flex justify-between text-white text-xl font-black">
                            <span>Total Paid</span>
                            <span class="text-orange-500">${{ number_format($selectedOrder->total_price, 2) }}</span>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="p-8 pt-0">
                    <button type="button" x-on:click="close()"
                        class="w-full py-4 bg-white/5 border border-white/10 rounded-xl font-bold hover:bg-white/10 transition-all text-white cursor-pointer">
                        Back to Orders
                    </button>
                </div>
            </div>
        </div>
    @endif
</section>
