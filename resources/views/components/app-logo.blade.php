{{-- resources/views/components/app-logo.blade.php --}}
@props([
    'sidebar' => false,
])

@php
    // Define your classes here so you only have to edit them in one place
    $nameClasses = 'text-2xl font-extrabold tracking-tight'; 
@endphp

@if($sidebar)
    <flux:sidebar.brand {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-16 items-center justify-center">
            <x-app-logo-icon class="size-full" />
        </x-slot>

        {{-- Use the name slot to customize text --}}
        <x-slot name="name">
            <span class="{{ $nameClasses }}">GymWithin</span>
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-16 items-center justify-center">
            <x-app-logo-icon class="size-full" />
        </x-slot>

        <x-slot name="name">
            <span class="{{ $nameClasses }}">GymWithin</span>
        </x-slot>
    </flux:brand>
@endif