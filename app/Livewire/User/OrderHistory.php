<?php

namespace App\Livewire\User;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.client-app')]
#[Title('My Orders - GymWithin')]

class OrderHistory extends Component
{
    use WithPagination;

    public $selectedOrderId = null; // Store just the ID
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

    public function showOrder($id)
    {
        $this->selectedOrderId = $id;
    }

    // Add a Computed Property for the view
    #[Computed]
    public function selectedOrder()
    {
        return \App\Models\Order::with(['items.product', 'transaction'])->find($this->selectedOrderId);
    }

    public function render()
    {
        return view('livewire.user.order-history', [
            'orders' => Order::where('user_id', Auth::id())
                ->with('transaction')
                ->latest()
                ->paginate(5),
        ]);
    }
}
