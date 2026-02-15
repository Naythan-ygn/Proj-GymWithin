{{-- resources/views/components/app-logo-icon.blade.php --}}
<img 
    src="{{ asset('Treadmill_Images/logo.png') }}" 
    alt="Logo" 
    {{ $attributes->merge(['class' => 'object-contain dark:brightness-125 dark:contrast-150']) }}
>