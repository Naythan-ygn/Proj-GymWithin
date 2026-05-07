<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    <div class="px-4 pt-4">
        <flux:heading size="xl">Payment Tracsactions</flux:heading>
        <flux:subheading>Review uploaded banking receipts and approve or reject customer payments.</flux:subheading>
    </div>

    <div class="glass-panel mx-4 p-2 rounded-lg flex gap-2">
        <flux:input class="flex-1" wire:model.live.debounce.300ms="search" icon="magnifying-glass"
            placeholder="Search by order # or customer..." />
        <flux:select wire:model.live="statusFilter" placeholder="All Statuses" class="max-w-xs">
            <flux:select.option value="">All Statuses</flux:select.option>
            <flux:select.option value="pending">Pending</flux:select.option>
            <flux:select.option value="approved">Approved</flux:select.option>
            <flux:select.option value="rejected">Rejected</flux:select.option>
        </flux:select>
    </div>

    <div class="glass-panel flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 mx-4">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-zinc-200 dark:border-zinc-700">
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300">Order #</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300">Customer</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300">Amount</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300">Status</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300">Receipt</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300">Uploaded</th>
                    <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200/50 dark:divide-white/5">
                @forelse ($transactions as $transaction)
                    <tr class="glass-row-hover transition-colors cursor-pointer"
                        wire:click="showTransaction({{ $transaction->id }})">
                        <td class="px-6 py-4 font-mono font-bold text-orange-500">{{ $transaction->order->order_number }}</td>
                        <td class="px-6 py-4">{{ $transaction->user->name }}</td>
                        <td class="px-6 py-4 font-semibold">${{ number_format($transaction->amount, 2) }}</td>
                        <td class="px-6 py-4">
                            <flux:badge size="sm"
                                color="{{ $transaction->status === 'approved' ? 'green' : ($transaction->status === 'rejected' ? 'red' : 'orange') }}">
                                {{ ucfirst($transaction->status) }}
                            </flux:badge>
                        </td>
                        <td class="px-6 py-4">
                            <img src="{{ asset('storage/' . $transaction->screenshot_path) }}" alt="Receipt"
                                class="h-12 w-12 rounded-lg object-cover border border-white/10">
                        </td>
                        <td class="px-6 py-4 text-zinc-500 text-xs">
                            <span x-data="{
                                localTime: new Intl.DateTimeFormat(undefined, {
                                    month: 'short',
                                    day: '2-digit',
                                    year: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit',
                                    hour12: true
                                }).format(new Date('{{ $transaction->created_at->toIso8601String() }}'))
                            }" x-text="localTime">
                                {{ $transaction->created_at->format('M d, Y h:i A') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right" onclick="event.stopPropagation()">
                            <div class="flex items-center justify-end gap-2">
                                <flux:button size="sm" variant="primary"
                                    wire:click="reviewTransaction({{ $transaction->id }}, 'approved')">
                                    Approve
                                </flux:button>
                                <flux:button size="sm" variant="filled"
                                    class="bg-red-600 hover:bg-red-700 text-white"
                                    wire:click="reviewTransaction({{ $transaction->id }}, 'rejected')">
                                    Reject
                                </flux:button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center italic text-zinc-500">No payment transactions found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-4 pb-4">{{ $transactions->links() }}</div>

    <flux:modal name="transaction-detail-modal" class="glass-modal-content w-full max-w-4xl">
        @if ($selectedTransaction)
            <div class="space-y-6" x-data="{ scale: 1 }">
                <header class="flex justify-between items-start gap-4">
                    <div>
                        <flux:heading size="lg">Transaction for Order #{{ $selectedTransaction->order->order_number }}</flux:heading>
                        <flux:subheading>{{ $selectedTransaction->user->name }} · {{ $selectedTransaction->user->email }}</flux:subheading>
                    </div>
                    <flux:badge
                        color="{{ $selectedTransaction->status === 'approved' ? 'green' : ($selectedTransaction->status === 'rejected' ? 'red' : 'orange') }}">
                        {{ ucfirst($selectedTransaction->status) }}
                    </flux:badge>
                </header>

                <div class="grid gap-6 lg:grid-cols-[1.2fr,0.8fr]">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <p class="text-xs text-zinc-500 uppercase font-bold">Uploaded Screenshot</p>
                            <div class="flex items-center gap-2">
                                <flux:button size="sm" variant="ghost" x-on:click="scale = Math.max(0.5, scale - 0.25)">-</flux:button>
                                <span class="text-xs text-zinc-500" x-text="`${Math.round(scale * 100)}%`"></span>
                                <flux:button size="sm" variant="ghost" x-on:click="scale = Math.min(3, scale + 0.25)">+</flux:button>
                            </div>
                        </div>

                        <div class="overflow-auto rounded-2xl border border-white/10 bg-black/40 p-4">
                            <img src="{{ asset('storage/' . $selectedTransaction->screenshot_path) }}" alt="Transaction screenshot"
                                class="mx-auto max-h-[32rem] origin-top transition-transform duration-200 rounded-xl"
                                x-bind:style="`transform: scale(${scale})`">
                        </div>
                    </div>

                    <div class="space-y-4 rounded-2xl border border-white/10 bg-white/5 p-5">
                        <div>
                            <p class="text-xs text-zinc-500 uppercase font-bold mb-1">Bank Name</p>
                            <p>{{ $selectedTransaction->bank_name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-zinc-500 uppercase font-bold mb-1">Account Name</p>
                            <p>{{ $selectedTransaction->account_name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-zinc-500 uppercase font-bold mb-1">Account Number</p>
                            <p>{{ $selectedTransaction->account_number }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-zinc-500 uppercase font-bold mb-1">Amount</p>
                            <p>${{ number_format($selectedTransaction->amount, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-zinc-500 uppercase font-bold mb-1">Uploaded At</p>
                            <p x-data="{
                                localTime: new Intl.DateTimeFormat(undefined, {
                                    month: 'short',
                                    day: '2-digit',
                                    year: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit',
                                    hour12: true
                                }).format(new Date('{{ $selectedTransaction->created_at->toIso8601String() }}'))
                            }" x-text="localTime">
                                {{ $selectedTransaction->created_at->format('M d, Y h:i A') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-zinc-500 uppercase font-bold mb-1">Reviewed By</p>
                            <p>{{ $selectedTransaction->reviewer?->name ?? 'Not reviewed yet' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-zinc-500 uppercase font-bold mb-1">Admin Note</p>
                            <p>{{ $selectedTransaction->admin_notes ?? 'Pending review.' }}</p>
                        </div>
                        <div class="flex gap-3 pt-2">
                            <flux:button class="flex-1" variant="primary"
                                wire:click="reviewTransaction({{ $selectedTransaction->id }}, 'approved')">
                                Approve
                            </flux:button>
                            <flux:button class="flex-1 bg-red-600 hover:bg-red-700 text-white" variant="filled"
                                wire:click="reviewTransaction({{ $selectedTransaction->id }}, 'rejected')">
                                Reject
                            </flux:button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </flux:modal>
</div>
