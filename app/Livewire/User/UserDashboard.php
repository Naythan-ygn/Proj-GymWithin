<?php

namespace App\Livewire\User;

use App\Models\Order;
use App\Models\Product; // Make sure this matches your actual Product model!
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.client-app')]
#[Title('Member Dashboard - GymWithin')]
class UserDashboard extends Component
{
    public $paymentToasts = [];

    public function mount(): void
    {
        $unseenOrders = Order::query()
            ->where('user_id', Auth::id())
            ->whereIn('payment_status', ['approved', 'rejected'])
            ->whereNotNull('payment_reviewed_at')
            ->whereNull('payment_notification_seen_at')
            ->latest('payment_reviewed_at')
            ->take(3)
            ->get();

        $this->paymentToasts = $unseenOrders
            ->map(fn($order) => [
                'order_number' => $order->order_number,
                'payment_status' => $order->payment_status,
                'payment_notes' => $order->payment_notes,
            ])
            ->values()
            ->all();

        if ($unseenOrders->isNotEmpty()) {
            Order::whereIn('id', $unseenOrders->pluck('id'))
                ->update(['payment_notification_seen_at' => now()]);
        }
    }

    public function render()
    {
        // Safely fetch 4 random products (with a fallback if the model isn't ready)
        $products = class_exists(Product::class)
            ? Product::inRandomOrder()->take(6)->get()
            : collect([]);

        $categories = [
            ['name' => 'Strength', 'icon' => 'fas fa-dumbbell'],
            ['name' => 'Cardio', 'icon' => 'fas fa-running'],
            ['name' => 'Accessories', 'icon' => 'fas fa-check-square'],
            ['name' => 'Recovery', 'icon' => 'fas fa-heartbeat'],
        ];

        return view('livewire.user.user-dashboard', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }
}
