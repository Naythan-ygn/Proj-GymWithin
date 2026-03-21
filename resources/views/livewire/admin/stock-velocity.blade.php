<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    {{-- Header Section --}}
    <div class="flex items-center justify-between px-4 pt-4">
        <div>
            <flux:heading size="xl">Stock Velocity Analysis</flux:heading>
            <flux:subheading>Monitor inventory depletion rates and estimate runway.</flux:subheading>
        </div>
        
        {{-- Export Button --}}
        <flux:button wire:click="exportXLSX" variant="primary" icon="document-arrow-down" wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="exportXLSX">Export to Excel</span>
            <span wire:loading wire:target="exportXLSX">Generating XLSX...</span>
        </flux:button>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mx-4">
        <div class="glass-panel p-5 rounded-xl border border-red-500/20 bg-red-500/5">
            <h3 class="text-xs font-bold uppercase tracking-wider text-red-500 mb-1">Critical Stockouts</h3>
            <p class="text-3xl font-black text-zinc-900 dark:text-zinc-100">{{ $criticalCount }}</p>
            <p class="text-xs text-zinc-500 mt-1">Items running out in &lt; 7 days</p>
        </div>
        <div class="glass-panel p-5 rounded-xl border border-neutral-200 dark:border-neutral-700">
            <h3 class="text-xs font-bold uppercase tracking-wider text-zinc-500 mb-1">Tracking Period</h3>
            <p class="text-3xl font-black text-zinc-900 dark:text-zinc-100">{{ $this->trackingDays }} Days</p>
            <p class="text-xs text-zinc-500 mt-1">Rolling average used for velocity</p>
        </div>
        <div class="glass-panel p-5 rounded-xl border border-neutral-200 dark:border-neutral-700">
            <h3 class="text-xs font-bold uppercase tracking-wider text-zinc-500 mb-1">Dead Stock Risk</h3>
            <p class="text-3xl font-black text-zinc-900 dark:text-zinc-100">{{ $deadStockCount }}</p>
            <p class="text-xs text-zinc-500 mt-1">High stock, very low movement</p>
        </div>
    </div>

    {{-- Glass Search & Filter Bar --}}
    <div class="glass-panel mx-4 p-2 rounded-lg flex gap-2">
        <flux:input class="flex-1" wire:model.live.debounce.300ms="search" icon="magnifying-glass"
            placeholder="Search products by name or SKU..." />
        <flux:select wire:model.live="categoryFilter" placeholder="All Categories" class="max-w-xs">
            <flux:select.option value="">All Categories</flux:select.option>
            <flux:select.option value="apparel">Apparel</flux:select.option>
            <flux:select.option value="supplements">Supplements</flux:select.option>
            <flux:select.option value="equipment">Equipment</flux:select.option>
        </flux:select>
    </div>

    {{-- Glass Table --}}
    <div class="glass-panel flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 mx-4">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-zinc-200 dark:border-zinc-700">
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300">Product</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300">Category</th>
                        <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300">Current Stock</th>
                        <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300">30D Sales</th>
                        <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300">Velocity (Per Day)</th>
                        <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300">Est. Runway</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200/50 dark:divide-white/5">
                    @forelse ($products as $product)
                        @php
                            $velocity = $product->sold_period / $this->trackingDays;
                            $runway = $velocity > 0 ? floor($product->stock / $velocity) : 999;
                            
                            $statusColor = 'green';
                            $statusText = $runway . ' Days';
                            
                            if ($product->stock == 0) {
                                $statusColor = 'red';
                                $statusText = 'Out of Stock';
                            } elseif ($runway <= 7) {
                                $statusColor = 'red';
                            } elseif ($runway <= 30) {
                                $statusColor = 'orange';
                            } elseif ($velocity == 0) {
                                $statusColor = 'zinc';
                                $statusText = 'Infinite (No Sales)';
                            }
                        @endphp
                        <tr class="glass-row-hover transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="size-10 rounded-lg bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 overflow-hidden">
                                        <img src="{{ $product->image_path ? asset('storage/' . $product->image_path) : 'https://placehold.co/100?text=No+Image' }}"
                                            alt="{{ $product->name }}" class="object-cover size-full">
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-medium text-zinc-900 dark:text-zinc-100">{{ $product->name }}</span>
                                        <span class="text-xs text-zinc-500">SKU: {{ $product->sku }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <flux:badge size="sm" inset="top bottom" class="capitalize">
                                    {{ $product->category->name ?? 'Uncategorized' }}
                                </flux:badge>
                            </td>
                            <td class="px-6 py-4 text-right font-semibold text-zinc-900 dark:text-zinc-100">
                                {{ number_format($product->stock) }}
                            </td>
                            <td class="px-6 py-4 text-right text-zinc-600 dark:text-zinc-400">
                                {{ number_format($product->sold_period) }} units
                            </td>
                            <td class="px-6 py-4 text-right font-mono text-sm">
                                {{ number_format($velocity, 1) }} / day
                            </td>
                            <td class="px-6 py-4 text-center">
                                <flux:badge size="sm" color="{{ $statusColor }}" class="font-bold">
                                    {{ $statusText }}
                                </flux:badge>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <p class="text-zinc-500 dark:text-zinc-400 italic text-sm">No products found to analyze.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="px-4 pb-4">
        {{ $products->links() }}
    </div>
</div>