<div class="p-6 max-w-2xl mx-auto">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    
    <flux:button variant="ghost" icon="chevron-left" href="{{ route('admin.users.index') }}" wire:navigate class="mb-4">
        Back to Members
    </flux:button>

    <div class="glass-panel p-8 rounded-3xl border border-neutral-200 dark:border-neutral-700 shadow-2xl">
        <div class="mb-8">
            <flux:heading size="lg">{{ $user ? 'Edit Member' : 'Add New Customer' }}</flux:heading>
            <flux:subheading>Enter the member details to update the GymWithin database.</flux:subheading>
        </div>

        <form wire:submit="save" class="space-y-6">
            <flux:input wire:model="name" label="Full Name" icon="user" placeholder="e.g. John Doe" />
            
            <flux:input wire:model="email" label="Email Address" type="email" icon="envelope" placeholder="john@gymwithin.com" />

            <flux:input wire:model="password" label="Password" type="password" icon="key" placeholder="Min. 8 characters" />

            <div class="flex justify-end gap-3 pt-6">
                <flux:button variant="ghost" href="{{ route('admin.users.index') }}" wire:navigate>Cancel</flux:button>
                <flux:button type="submit" variant="primary">
                    {{ $user ? 'Update Member' : 'Create Member' }}
                </flux:button>
            </div>
        </form>
    </div>
</div>