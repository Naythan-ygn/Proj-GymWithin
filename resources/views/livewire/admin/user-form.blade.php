<div class="p-6 max-w-2xl mx-auto">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    <flux:button variant="ghost" icon="chevron-left" href="{{ route('admin.users.index') }}" wire:navigate class="mb-4">
        Back to Members
    </flux:button>

    <div class="glass-panel p-8 rounded-3xl border border-neutral-200 dark:border-neutral-700 shadow-2xl">
        <div class="mb-8">
            <flux:heading size="lg">{{ $user ? 'Edit Member Profile' : 'Add New Athlete' }}</flux:heading>
            <flux:subheading>Update account details and access permissions.</flux:subheading>
        </div>

        <form wire:submit="save" class="space-y-6">
            <flux:input wire:model="name" label="Full Name" icon="user" />

            <flux:input wire:model="email" label="Email Address" type="email" icon="envelope" />

            {{-- Fixed: Using flux:select instead of the unsupported cards variant --}}
            <flux:select wire:model="role" label="Account Access Level" placeholder="Select a role...">
                <flux:select.option value="user">User (Standard Access)</flux:select.option>
                <flux:select.option value="admin">Admin (Full Panel Access)</flux:select.option>
            </flux:select>

            <flux:input wire:model="password" label="Password" type="password" icon="key"
                placeholder="{{ $user ? 'Leave blank to keep current' : 'Min. 8 characters' }}" />

            <div class="flex justify-end gap-3 pt-6">
                <flux:button variant="ghost" href="{{ route('admin.users.index') }}" wire:navigate>Cancel</flux:button>
                <flux:button type="submit" variant="primary">
                    {{ $user ? 'Save Changes' : 'Create Account' }}
                </flux:button>
            </div>
        </form>
    </div>
</div>
