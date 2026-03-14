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

    public function showOrder($id)
    {
        $this->selectedOrderId = $id;
    }

    // Add a Computed Property for the view
    #[Computed]
    public function selectedOrder()
    {
        return \App\Models\Order::with(['items.product'])->find($this->selectedOrderId);
    }

    public function render()
    {
        return view('livewire.user.order-history', [
            'orders' => Order::where('user_id', Auth::id())
                ->latest()
                ->paginate(5),
        ]);
    }
}
