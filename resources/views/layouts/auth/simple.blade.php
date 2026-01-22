<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white antialiased dark:bg-zinc-950">
    <div
        class="flex min-h-svh flex-col items-center justify-center gap-6 {{ request()->routeIs('login') ? '' : 'p-6 md:p-10' }}">
        <div class="flex w-full {{ request()->routeIs('login') ? 'max-w-none' : 'max-w-sm flex-col gap-2' }}">
            @if(!request()->routeIs('login'))
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-2 font-medium" wire:navigate>
                    <span class="flex items-center justify-center rounded-md">
                        <img src="{{ asset('Treadmill_Images/logo.png') }}" alt="Logo" class="h-20 w-auto">
                    </span>
                    <span class="sr-only">{{ config('app.name', 'GymWithin') }}</span>
                </a>
            @endif

            <div class="flex flex-col w-full">
                {{ $slot }}
            </div>
        </div>
    </div>
    @fluxScripts
</body>

</html>