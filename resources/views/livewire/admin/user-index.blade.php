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
                    <tr class="border-b border-zinc-200 dark:border-zinc-700">
                        <th
                            class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300">
                            Member
                        </th>
                        {{-- New Role Column --}}
                        <th
                            class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300">
                            Role
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
                                {{-- Name now triggers the preview without showing the role underneath --}}
                                <button wire:click="showPreview({{ $user->id }})"
                                    class="flex items-center gap-3 group text-left focus:outline-none">
                                    <flux:avatar :name="$user->name" size="sm"
                                        class="border border-zinc-200 dark:border-zinc-700" />
                                    <span
                                        class="font-medium text-zinc-900 dark:text-zinc-100 group-hover:text-orange-500 transition-colors">
                                        {{ $user->name }}
                                    </span>
                                </button>
                            </td>

                            {{-- Clean Role Column --}}
                            <td class="px-6 py-4">
                                {{-- Displaying only actual account levels --}}
                                <flux:badge size="sm" :color="$user->role === 'admin' ? 'orange' : 'zinc'"
                                    class="uppercase font-bold tracking-tighter">
                                    {{ $user->role === 'admin' ? 'Admin' : 'User' }}
                                </flux:badge>
                            </td>

                            <td class="px-6 py-4 text-zinc-600 dark:text-zinc-400 text-sm">
                                {{ $user->email }}
                            </td>

                            <td class="px-6 py-4">
                                <span
                                    class="status-active inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold">
                                    {{ ucfirst($user->status ?? 'Active') }}
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
                            <td colspan="5" class="px-6 py-12 text-center">
                                {{-- Using standard zinc classes instead of flux:text color="zinc" --}}
                                <p class="text-zinc-500 dark:text-zinc-400 italic text-sm">
                                    No gym members found matching your search.
                                </p>
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

    <!-- User Preview Modal -->
    <flux:modal name="user-preview-drawer" variant="drawer" class="glass-modal-content w-full max-w-md">
        @if($selectedUser)
            <div class="space-y-8 p-4">
                <header class="text-center space-y-4">
                    <flux:avatar :name="$selectedUser->name" class="size-24 mx-auto border-4 border-white/10" />
                    <div>
                        <flux:heading size="xl">{{ $selectedUser->name }}</flux:heading>
                        {{-- Fixed the color error from previous screen --}}
                        <flux:badge color="orange" size="sm" class="uppercase">{{ $selectedUser->role ?? 'User' }}
                        </flux:badge>
                    </div>
                </header>

                <div class="glass-panel rounded-2xl p-6 space-y-4 border border-zinc-200 dark:border-zinc-800">
                    <div class="flex justify-between items-center border-b border-zinc-200/50 dark:border-white/5 pb-3">
                        <span class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Account Status</span>
                        {{-- Added dynamic coloring based on status --}}
                        <span
                            class="px-2 py-1 rounded text-[10px] font-bold uppercase {{ ($selectedUser->status ?? 'active') === 'active' ? 'bg-green-500/10 text-green-500' : 'bg-zinc-500/10 text-zinc-500' }}">
                            {{ $selectedUser->status ?? 'Active' }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center border-b border-zinc-200/50 dark:border-white/5 pb-3">
                        <span class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Last Seen</span>
                        <span class="text-sm text-zinc-900 dark:text-zinc-100 font-semibold">
                            {{-- Shows First Login Pending instead of Never for new users --}}
                            {{ $selectedUser->last_login_at ? $selectedUser->last_login_at->diffForHumans() : 'First Login Pending' }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Member Since</span>
                        <span class="text-sm text-zinc-900 dark:text-zinc-100 font-semibold">
                            {{ $selectedUser->created_at->format('M d, Y') }}
                        </span>
                    </div>
                </div>

                <div class="flex gap-2">
                    <flux:button href="{{ route('admin.users.edit', $selectedUser) }}" variant="primary" class="flex-1"
                        wire:navigate>
                        Edit Profile
                    </flux:button>
                    <flux:modal.close>
                        <flux:button variant="ghost" class="flex-1">Close</flux:button>
                    </flux:modal.close>
                </div>
            </div>
        @endif
    </flux:modal>
</div>
