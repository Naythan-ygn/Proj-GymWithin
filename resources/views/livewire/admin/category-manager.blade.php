<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    {{-- Header Section --}}
    <div class="flex items-center justify-between px-4">
        <div>
            <flux:heading size="xl">Product Categories</flux:heading>
            <flux:subheading>Organize your inventory by apparel, equipment, or supplements.</flux:subheading>
        </div>
    </div>

    {{-- Glass Input Panel (Create/Edit) --}}
    <div class="glass-panel p-6 rounded-2xl border border-neutral-200 dark:border-neutral-700 shadow-lg">
        <form wire:submit="save" class="flex items-end gap-4">
            <div class="flex-1">
                <flux:input wire:model="name" label="{{ $editingId ? 'Modify Category Name' : 'New Category Name' }}"
                    placeholder="e.g., Weightlifting Gear" />
            </div>
            <div class="flex gap-2">
                @if ($editingId)
                    <flux:button variant="ghost" wire:click="cancel">Cancel</flux:button>
                @endif
                <flux:button type="submit" variant="primary">
                    {{ $editingId ? 'Update Category' : 'Add Category' }}
                </flux:button>
            </div>
        </form>
    </div>

    {{-- Glass Table Panel --}}
    <div class="glass-panel overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-zinc-200 dark:border-zinc-700 bg-zinc-50/50 dark:bg-white/5">
                        <th
                            class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300">
                            Category Name
                        </th>
                        <th
                            class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300">
                            URL Slug
                        </th>
                        <th
                            class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300">
                            Created At
                        </th>
                        <th
                            class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300">
                            Updated At
                        </th>
                        <th
                            class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200/50 dark:divide-white/5">
                    @forelse ($categories as $category)
                        <tr class="glass-row-hover transition-colors">
                            <td class="px-6 py-4 font-medium text-zinc-900 dark:text-zinc-100">
                                {{ $category->name }}
                            </td>
                            <td class="px-6 py-4">
                                <code
                                    class="text-xs bg-zinc-400 dark:bg-gray-600 px-2 py-1 rounded text-white-600 dark:text-white-400">
                                    {{ $category->slug }}
                                </code>
                            </td>
                            <td class="px-6 py-4 font-semilbold text-zinc-900 dark:text-zinc-100">
                                {{ $category->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-zinc-900 dark:text-zinc-500">
                                {{ $category->updated_at->diffForHumans() }}
                            </td>   
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <flux:button variant="ghost" size="sm" icon="pencil"
                                        wire:click="edit({{ $category->id }})" />
                                    <flux:button variant="ghost" size="sm" icon="trash" color="red"
                                        wire:click="delete({{ $category->id }})" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center">
                                <p class="text-zinc-500 dark:text-zinc-400 italic text-sm">
                                    No categories defined yet.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
