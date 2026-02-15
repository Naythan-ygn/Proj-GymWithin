<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    {{-- Header Section --}}
    <div class="flex items-center justify-between px-4 pt-4">
        <div>
            <flux:heading size="xl">Gym Members</flux:heading>
            <flux:subheading>Manage athlete accounts and permissions.</flux:subheading>
        </div>
        <flux:button href="{{ route('admin.users.create') }}" variant="primary" icon="plus" wire:navigate>
            Add Member
        </flux:button>
    </div>

    {{-- Glass Search Bar --}}
    <div class="glass-panel mx-4 p-2 rounded-lg">
        <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass"
            placeholder="Search by name or email..." />
    </div>

    {{-- Manual Glass Table --}}
    <div class="glass-panel flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 mx-4">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    {{-- Applying the glass-header logic with Zinc-specific text colors --}}
                    <tr class="border-b border-zinc-200 dark:border-zinc-700">
                        <th
                            class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300">
                            Member
                        </th>
                        <th
                            class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300">
                            Email Address
                        </th>
                        <th
                            class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300">
                            Status
                        </th>
                        <th
                            class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200/50 dark:divide-white/5">
                    @forelse ($users as $user)
                        <tr class="glass-row-hover transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <flux:avatar :name="$user->name" size="sm"
                                        class="border border-zinc-200 dark:border-zinc-700" />
                                    <span
                                        class="font-medium text-zinc-900 dark:text-zinc-100">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-zinc-600 dark:text-zinc-400 text-sm">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="status-active inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold">
                                    Active
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <flux:button variant="ghost" size="sm" icon="pencil"
                                        href="{{ route('admin.users.edit', $user) }}" wire:navigate />
                                    <flux:button variant="ghost" size="sm" icon="trash" color="red"
                                        wire:click="confirmDelete({{ $user->id }})" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <flux:text color="zinc" class="italic">No gym members found matching your search.
                                </flux:text>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="px-4 pb-4">
        {{ $users->links() }}
    </div>

    {{-- Delete Modal (Keeping your requested Glass Modal) --}}
    <flux:modal name="delete-user-modal" class="glass-modal-content rounded-3xl p-6">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Confirm Removal</flux:heading>
                <flux:subheading>
                    Are you sure you want to remove this athlete?
                    This action is permanent.
                </flux:subheading>
            </div>
            <div class="flex gap-3 justify-end">
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button wire:click="deleteUser" variant="primary" color="red">
                    Delete Member
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
