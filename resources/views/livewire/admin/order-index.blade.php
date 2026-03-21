<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    {{-- Header Section --}}
    <div class="px-4 pt-4">
        <flux:heading size="xl">Sales Orders</flux:heading>
        <flux:subheading>Monitor gym equipment sales and customer deliveries.</flux:subheading>
    </div>

    {{-- Search & Filter --}}
    <div class="glass-panel mx-4 p-2 rounded-lg flex gap-2">
        <flux:input class="flex-1" wire:model.live.debounce.300ms="search" icon="magnifying-glass"
            placeholder="Search by Order #..." />
        <flux:select wire:model.live="statusFilter" placeholder="All Statuses" class="max-w-xs">
            <flux:select.option value="">All Statuses</flux:select.option>
            <flux:select.option value="pending">Pending</flux:select.option>
            <flux:select.option value="shipped">Shipped</flux:select.option>
            <flux:select.option value="completed">Completed</flux:select.option>
        </flux:select>
    </div>

    {{-- Orders Table --}}
    <div class="glass-panel flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 mx-4">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-zinc-200 dark:border-zinc-700">
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300">
                        Order #</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300">
                        Customer</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300">
                        Total</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300">
                        Status</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300">
                        Date</th>
                    <th
                        class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300">
                        Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200/50 dark:divide-white/5">
                @forelse ($orders as $order)
                    <tr class="glass-row-hover transition-colors cursor-pointer"
                        wire:click="showOrder({{ $order->id }})">
                        <td class="px-6 py-4 font-mono font-bold text-orange-500">{{ $order->order_number }}</td>
                        <td class="px-6 py-4">{{ $order->user->name }}</td>
                        <td class="px-6 py-4 font-semibold">${{ number_format($order->total_price, 2) }}</td>
                        <td class="px-6 py-4">
                            <flux:badge size="sm"
                                color="{{ $order->status === 'pending' ? 'orange' : ($order->status === 'shipped' ? 'blue' : 'green') }}">
                                {{ ucfirst($order->status) }}
                            </flux:badge>
                        </td>
                        <td class="px-6 py-4 text-zinc-500 text-xs">
                            <span x-data="{
                                localTime: new Intl.DateTimeFormat(undefined, {
                                    month: 'short',
                                    day: '2-digit',
                                    hour: '2-digit',
                                    minute: '2-digit',
                                    hour12: true
                                }).format(new Date('{{ $order->created_at->toIso8601String() }}'))
                            }" x-text="localTime">
                                {{-- Fallback for SEO or slow JS --}}
                                {{ $order->created_at->format('M d, h:i A') }} UTC
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right" onclick="event.stopPropagation()">
                            <flux:dropdown>
                                <flux:button variant="ghost" icon="ellipsis-horizontal" size="sm" />
                                <flux:menu>
                                    <flux:menu.item wire:click="updateStatus({{ $order->id }}, 'shipped')"
                                        icon="truck">Mark Shipped</flux:menu.item>
                                    <flux:menu.item wire:click="updateStatus({{ $order->id }}, 'completed')"
                                        icon="check">Mark Completed</flux:menu.item>
                                </flux:menu>
                            </flux:dropdown>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center italic text-zinc-500">No orders received yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-4 pb-4">{{ $orders->links() }}</div>

    {{-- Order Detail Modal --}}
    <flux:modal name="order-detail-modal" class="glass-modal-content w-full max-w-2xl">
        @if ($selectedOrder)
            <div class="space-y-6">
                <header class="flex justify-between items-start">
                    <div>
                        <flux:heading size="lg">Order #{{ $selectedOrder->order_number }}</flux:heading>
                        <flux:subheading>{{ $selectedOrder->user->email }}</flux:subheading>
                    </div>
                    <flux:badge color="orange">{{ ucfirst($selectedOrder->status) }}</flux:badge>
                </header>

                <div class="grid grid-cols-2 gap-6 bg-white/5 p-4 rounded-xl border border-white/10">
                    <div>
                        <p class="text-xs text-zinc-500 uppercase font-bold mb-1">Shipping Address</p>
                        <p class="text-sm leading-relaxed">{{ $selectedOrder->shipping_address }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-zinc-500 uppercase font-bold mb-1">Order Date</p>
                        <p class="text-sm">{{ $selectedOrder->created_at->format('F d, Y') }}</p>
                    </div>
                </div>

                <div class="space-y-3">
                    <p class="text-xs text-zinc-500 uppercase font-bold">Items Purchased</p>
                    @foreach ($selectedOrder->items as $item)
                        <div class="flex justify-between items-center bg-white/5 p-3 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="size-10 rounded border border-white/10 overflow-hidden">
                                    <img src="{{ $item->product->image_path ? asset('storage/' . $item->product->image_path) : 'https://placehold.co/100' }}"
                                        class="object-cover size-full">
                                </div>
                                <span class="text-sm font-medium">{{ $item->product->name }}
                                    (x{{ $item->quantity }})
                                </span>
                            </div>
                            <span class="font-bold">${{ number_format($item->price * $item->quantity, 2) }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="flex justify-between items-center pt-4 border-t border-white/10">
                    <span class="text-xl font-bold">Grand Total</span>
                    <span
                        class="text-2xl font-black text-orange-500">${{ number_format($selectedOrder->total_price, 2) }}</span>
                </div>
            </div>
        @endif
    </flux:modal>
</div>
