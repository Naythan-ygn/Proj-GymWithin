<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:sidebar sticky collapsible="mobile"
        class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.header>
            <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
            <flux:sidebar.collapse class="lg:hidden" />
        </flux:sidebar.header>

        <flux:sidebar.group>
            {{-- Platform Management --}}
            <flux:sidebar.group :heading="__('Platform')" class="grid">
                <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')"
                    wire:navigate>
                    {{ __('Dashboard') }}
                </flux:sidebar.item>

                <flux:sidebar.item icon="users" :href="route('admin.users.index')"
                    :current="request()->routeIs('admin.users.*')" wire:navigate>
                    {{ __('Manage Users') }}
                </flux:sidebar.item>
            </flux:sidebar.group>

            {{-- Store Management --}}
            <flux:sidebar.group :heading="__('Store')" class="grid">
                <flux:sidebar.item icon="archive-box" :href="route('admin.inventory.index')"
                    :current="request()->routeIs('admin.inventory.*')" wire:navigate>
                    {{ __('Inventory') }}
                </flux:sidebar.item>

                <flux:sidebar.item icon="tag" :href="route('admin.categories.index')" wire:navigate>
                    {{ __('Categories') }}
                </flux:sidebar.item>

                <flux:sidebar.item icon="wrench" :href="route('admin.products.index')"
                    :current="request()->routeIs('admin.products.*')" wire:navigate>
                    {{ __('Products') }}
                </flux:sidebar.item>

                <flux:sidebar.item icon="shopping-cart" :href="route('admin.orders.index')" wire:navigate>
                    {{ __('Orders') }}
                    @php
                        $pendingCount = \App\Models\Order::where('status', 'pending')->count();
                    @endphp
                    @if ($pendingCount > 0)
                        <flux:badge size="sm" color="orange" inset="top bottom" class="ml-auto">
                            {{ $pendingCount }}
                        </flux:badge>
                    @endif
                </flux:sidebar.item>
            </flux:sidebar.group>

            {{-- AI Analytics Dashboard --}}
            <flux:sidebar.group :heading="__('Analytics')" class="grid">
                <flux:sidebar.item icon="chart-bar" :href="route('admin.ai-analytics')"
                    :current="request()->routeIs('admin.ai-analytics')" wire:navigate>
                    {{ __('AI Analytics') }}
                </flux:sidebar.item>
            </flux:sidebar.group>
            
        </flux:sidebar.group>
        <flux:spacer />

        <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />

        <div class="px-6 space-y-4">
            <div
                class="text-[10px] uppercase tracking-widest text-zinc-500/50 font-medium border-t border-zinc-200/50 dark:border-zinc-700/50 pt-4">
                &copy; 2026 GymWithin. <br> All rights reserved.
            </div>
        </div>

    </flux:sidebar>

    <!-- Mobile User Menu -->
    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:spacer />

        <flux:dropdown position="top" align="end">
            <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <flux:avatar :name="auth()->user()->name" :initials="auth()->user()->initials()" />

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                        {{ __('Settings') }}
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                        class="w-full cursor-pointer" data-test="logout-button">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    {{ $slot }}

    @fluxScripts
</body>

</html>
