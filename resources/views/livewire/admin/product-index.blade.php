<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    {{-- Header Section --}}
    <div class="flex items-center justify-between px-4 pt-4">
        <div>
            <flux:heading size="xl">Store Equipment</flux:heading>
            <flux:subheading>Manage gym apparel, supplements, and equipment.</flux:subheading>
        </div>
        <flux:button href="{{ route('admin.products.create') }}" variant="primary" icon="plus" wire:navigate>
            Add Product
        </flux:button>
    </div>

    {{-- Glass Search & Filter Bar --}}
    <div class="glass-panel mx-4 p-2 rounded-lg flex gap-2">
        <flux:input class="flex-1" wire:model.live.debounce.300ms="search" icon="magnifying-glass"
            placeholder="Search products by name or SKU..." />
        <flux:select wire:model.live="categoryFilter" placeholder="All Categories" class="max-w-xs">
            <flux:select.option value="">All Categories</flux:select.option>
            <flux:select.option value="apparel">Apparel</flux:select.option>
            <flux:select.option value="supplements">Supplements</flux:select.option>
            <flux:select.option value="gear">Equipment</flux:select.option>
        </flux:select>
    </div>

    {{-- Glass Table --}}
    <div class="glass-panel flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 mx-4">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-zinc-200 dark:border-zinc-700">
                        <th
                            class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300">
                            Product</th>
                        <th
                            class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300">
                            Category</th>
                        <th
                            class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300">
                            Price</th>
                        <th
                            class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300">
                            Stock Status</th>
                        <th
                            class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300">
                            Created At</th>
                        <th
                            class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300">
                            Updated At</th>
                        <th
                            class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200/50 dark:divide-white/5">
                    @forelse ($products as $product)
                        <tr class="glass-row-hover transition-colors">
                            <td class="px-6 py-4">
                                <button wire:click="showPreview({{ $product->id }})"
                                    class="flex items-center gap-4 group text-left focus:outline-none">
                                    <div
                                        class="size-12 rounded-lg bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 overflow-hidden">
                                        <img src="{{ $product->image_path ? asset('storage/' . $product->image_path) : 'https://placehold.co/100?text=No+Image' }}"
                                            alt="{{ $product->name }}" class="object-cover size-full">
                                    </div>
                                    <div class="flex flex-col">
                                        <span
                                            class="font-medium text-zinc-900 dark:text-zinc-100 group-hover:text-orange-500 transition-colors">{{ $product->name }}</span>
                                        <span class="text-xs text-zinc-500">SKU: {{ $product->sku }}</span>
                                    </div>
                                </button>
                            </td>
                            <td class="px-6 py-4">
                                <flux:badge size="sm" inset="top bottom" class="capitalize">
                                    {{ $product->category->name ?? 'Uncategorized' }}
                                </flux:badge>
                            </td>
                            <td class="px-6 py-4 font-semibold text-zinc-900 dark:text-zinc-100">
                                ${{ number_format($product->price, 2) }}
                            </td>
                            <td class="px-6 py-4">
                                @if ($product->stock <= 0)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-500/10 text-red-500 border border-red-500/20">
                                        Out of Stock
                                    </span>
                                @elseif($product->stock <= 5)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-orange-500/10 text-orange-500 border border-orange-500/20">
                                        Low Stock ({{ $product->stock }})
                                    </span>
                                @else
                                    <span
                                        class="status-active inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold">
                                        In Stock ({{ $product->stock }})
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-semilbold text-zinc-900 dark:text-zinc-100">
                                {{ $product->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-zinc-900 dark:text-zinc-500">
                                {{ $product->updated_at->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <flux:button variant="ghost" size="sm" icon="pencil"
                                        href="{{ route('admin.products.edit', $product) }}" wire:navigate />
                                    <flux:button variant="ghost" size="sm" icon="trash" color="red"
                                        wire:click="confirmDelete({{ $product->id }})" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <p class="text-zinc-500 dark:text-zinc-400 italic text-sm">No products found in the
                                    warehouse.</p>
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

    {{-- Product Preview Drawer --}}
    <flux:modal name="product-preview-drawer" variant="drawer" class="glass-modal-content w-full max-w-md">
        @if ($selectedProduct)
            <div class="space-y-8 p-4">
                <div
                    class="aspect-square w-full rounded-2xl bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 overflow-hidden">
                    <img src="{{ $selectedProduct->image_url ?? 'https://placehold.co/400' }}" alt=""
                        class="object-cover size-full">
                </div>

                <header class="space-y-2">
                    <flux:heading size="xl">{{ $selectedProduct->name }}</flux:heading>
                    <flux:badge color="orange" size="sm" class="uppercase">
                        {{ $selectedProduct->category->name ?? 'Uncategorized' }}
                    </flux:badge>
                </header>

                <div class="glass-panel rounded-2xl p-6 space-y-4 border border-zinc-200 dark:border-zinc-800">
                    <div class="flex justify-between items-center border-b border-zinc-200/50 dark:border-white/5 pb-3">
                        <span class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Unit Price</span>
                        <span
                            class="text-lg font-bold text-zinc-900 dark:text-zinc-100">${{ number_format($selectedProduct->price, 2) }}</span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Description</span>
                        <p class="text-sm text-zinc-600 dark:text-zinc-300 leading-relaxed">
                            {{ $selectedProduct->description ?? 'No description provided.' }}
                        </p>
                    </div>
                </div>

                <div class="flex gap-2">
                    <flux:button href="{{ route('admin.products.edit', $selectedProduct) }}" variant="primary"
                        class="flex-1" wire:navigate>
                        Edit Product
                    </flux:button>
                    <flux:modal.close>
                        <flux:button variant="ghost" class="flex-1">Close</flux:button>
                    </flux:modal.close>
                </div>
            </div>
        @endif
    </flux:modal>

    {{-- Delete Product Modal --}}
    <flux:modal name="delete-product-modal" class="glass-modal-content rounded-3xl p-6">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Confirm Removal</flux:heading>
                <flux:subheading>
                    Are you sure you want to remove
                    <b>{{ $productToDelete ? \App\Models\Product::find($productToDelete)->name : 'this item' }}</b>?
                    This will remove it from the store catalog permanently.
                </flux:subheading>
            </div>
            <div class="flex gap-3 justify-end">
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button wire:click="deleteProduct" variant="primary" color="red">
                    Confirm Delete
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
