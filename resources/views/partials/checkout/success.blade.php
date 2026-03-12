@extends('layouts.client-app')

@section('content')
    <section class="bg-black text-white min-h-screen flex items-center justify-center">
        <div class="glass-panel p-12 rounded-3xl border border-orange-500/30 text-center max-w-lg">
            <div class="size-20 bg-orange-500 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-check text-4xl text-white"></i>
            </div>
            <h1 class="text-3xl font-extrabold mb-2">Order Confirmed!</h1>
            <p class="text-zinc-400 mb-6">Your order <span class="text-white font-mono">{{ $order_number }}</span> has been
                placed successfully. We'll notify you when your equipment ships.</p>

            <a href="{{ route('user.home') }}"
                class="inline-block px-8 py-3 bg-orange-500 rounded-full font-bold hover:bg-orange-600 transition">
                Back to Dashboard
            </a>
        </div>
    </section>
@endsection
