<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'GymWithin - Premium Fitness')</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/motion@11.11.13/dist/motion.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">

    <style>
        /* This class is applied immediately, preventing the flash */
        .loading-shield {
            opacity: 0 !important;
            transform: translateY(20px);
        }

        /* Smooth transition for when the JS kicks in */
        .hero-transition {
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
        }
    </style>
    <script>
        // Safety Valve: If the animation doesn't run in 2 seconds, show everything
        setTimeout(() => {
            document.querySelectorAll('.js-hide').forEach(el => {
                el.style.opacity = '1';
                el.style.transform = 'none';
            });
        }, 2000);
    </script>
</head>

<body class="bg-black text-white overflow-x-hidden">
    @include('partials.nav')

    <main>
        @yield('content')
    </main>

    @include('partials.footer')
    @include('partials.chatbot')
    
    <!-- Scroll to Top Button -->
    <button id="scrollToTop" class="scroll-to-top" aria-label="Scroll to top">
        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
        </svg>
    </button>
    
    @if (Route::has('login'))
        <div class="h-14.5 hidden lg:block"></div>
    @endif

    <script src="{{ asset('js/animation.js') }}"></script>
</body>

</html>