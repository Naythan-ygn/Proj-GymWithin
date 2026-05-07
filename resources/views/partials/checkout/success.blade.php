@extends('layouts.client-app')

@section('content')
    <section class="bg-black text-white min-h-screen flex items-center justify-center">
        <div class="glass-panel p-12 rounded-3xl border border-orange-500/30 text-center max-w-xl">
            <div class="size-20 bg-amber-500 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-clock text-4xl text-white"></i>
            </div>
            <h1 class="text-3xl font-extrabold mb-2">Payment Proof Submitted</h1>
            <p class="text-zinc-400 mb-6">
                Your order <span class="text-white font-mono">{{ $order->order_number }}</span> is waiting for admin review.
                You will see a green success notice if payment is approved, or a red danger notice if it is rejected.
            </p>

            <div class="mb-8 rounded-2xl border border-white/10 bg-white/5 p-5 text-left">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-zinc-400 text-sm">Order Total</span>
                    <span class="text-xl font-bold text-orange-500">${{ number_format($order->total_price, 2) }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-zinc-400 text-sm">Payment Status</span>
                    <span class="rounded-full bg-amber-500/15 px-3 py-1 text-sm font-semibold text-amber-300">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('user.home') }}"
                    class="inline-block px-8 py-3 bg-orange-500 rounded-full font-bold hover:bg-orange-600 transition">
                    Back to Dashboard
                </a>
                <a href="{{ route('user.orders') }}"
                    class="inline-block px-8 py-3 bg-white/5 border border-white/10 rounded-full font-bold hover:bg-white/10 transition">
                    View My Orders
                </a>
            </div>
        </div>
    </section>
@endsection
