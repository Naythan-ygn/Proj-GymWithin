<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'GymWithin - Premium Fitness')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/motion@11.11.13/dist/motion.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
</head>

<body class="bg-black text-white overflow-x-hidden">
    @include('partials.nav')

    <main>
        @yield('content')
    </main>

    @include('partials.footer')
    @include('partials.chatbot')

    <script src="{{ asset('js/animation.js') }}"></script>
</body>

</html>