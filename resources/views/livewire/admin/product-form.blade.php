<div class="p-6 max-w-4xl mx-auto">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    <flux:button variant="ghost" icon="chevron-left" href="{{ route('admin.products.index') }}" wire:navigate
        class="mb-4">
        Back to Inventory
    </flux:button>

    <div class="glass-panel p-8 rounded-3xl border border-neutral-200 dark:border-neutral-700 shadow-2xl">
        <div class="mb-8">
            <flux:heading size="lg">{{ $product ? 'Modify Product' : 'Add New Inventory Item' }}</flux:heading>
            <flux:subheading>Set pricing, stock levels, and product categorization.</flux:subheading>
        </div>

        <form wire:submit="save" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-6">
                    <flux:input wire:model="name" label="Product Name" placeholder="e.g. Whey Protein Isolate" />

                    <div class="grid grid-cols-2 gap-4">
                        <flux:input wire:model="sku" label="SKU / Barcode" placeholder="GW-PRO-001" />
                        <flux:select wire:model="category" label="Category">
                            <flux:select.option value="">Select Category</flux:select.option>
                            @foreach(\App\Models\Category::all() as $cat)
                                <flux:select.option value="{{ $cat->slug }}">{{ $cat->name }}</flux:select.option>
                            @endforeach
                        </flux:select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <flux:input type="number" step="0.01" wire:model="price" label="Price ($)"
                            icon="currency-dollar" />
                        <flux:input type="number" wire:model="stock" label="Initial Stock" icon="archive-box" />
                    </div>
                </div>

                <div class="space-y-6">
                    {{-- Product Image Upload Section --}}
                    <div class="flex flex-col gap-2">
                        <label class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Product Image</label>

                        <div x-data="{ uploading: false, progress: 0 }" x-on:livewire-upload-start="uploading = true"
                            x-on:livewire-upload-finish="uploading = false"
                            x-on:livewire-upload-error="uploading = false"
                            x-on:livewire-upload-progress="progress = $event.detail.progress" class="relative">
                            {{-- Clicking this div triggers the hidden file input below --}}
                            <div onclick="document.getElementById('image-upload').click()"
                                class="border-2 border-dashed border-zinc-200 dark:border-zinc-700 rounded-2xl h-48 flex flex-col items-center justify-center bg-zinc-50/50 dark:bg-zinc-900/50 group hover:border-orange-500/50 transition-all cursor-pointer overflow-hidden">
                                @if ($image)
                                    {{-- Temporary Preview of the uploaded image --}}
                                    <img src="{{ $image->temporaryUrl() }}" class="object-cover size-full">
                                @elseif ($existingImage)
                                    {{-- Show existing image if editing --}}
                                    <img src="{{ asset('storage/' . $existingImage) }}" class="object-cover size-full">
                                @else
                                    <flux:icon name="cloud-arrow-up"
                                        class="size-8 text-zinc-400 group-hover:text-orange-500" />
                                    <span class="text-xs text-zinc-500 mt-2">Click to upload image</span>
                                @endif

                                {{-- Progress Bar for Uploading --}}
                                <div x-show="uploading"
                                    class="absolute inset-0 bg-zinc-900/60 flex items-center justify-center p-4">
                                    <div class="w-full bg-zinc-700 rounded-full h-1.5">
                                        <div class="bg-orange-500 h-1.5 rounded-full"
                                            :style="'width: ' + progress + '%'"></div>
                                    </div>
                                </div>
                            </div>

                            {{-- Hidden Real File Input --}}
                            <input type="file" id="image-upload" wire:model="image" class="hidden" accept="image/*">
                        </div>

                        @error('image') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <flux:textarea wire:model="description" label="Product Description" rows="5"
                        placeholder="Describe the benefits, ingredients, or materials..." />
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-6 border-t border-zinc-200/50 dark:border-white/5">
                <flux:button variant="ghost" href="{{ route('admin.products.index') }}" wire:navigate>Cancel
                </flux:button>
                <flux:button type="submit" variant="primary">
                    {{ $product ? 'Update Inventory' : 'Add to Catalog' }}
                </flux:button>
            </div>
        </form>
    </div>
</div>
