<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;
use Flux\Flux;

class OrderIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $selectedOrder; // For the Detail Modal

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function showOrder($id)
    {
        $this->selectedOrder = Order::with(['user', 'items.product'])->find($id);
        $this->modal('order-detail-modal')->show();
    }

    public function updateStatus($id, $status)
    {
        $order = Order::find($id);
        $order->update(['status' => $status]);
        Flux::toast("Order #{$order->order_number} marked as {$status}.", variant: 'success');
    }

    public function render()
    {
        return view('livewire.admin.order-index', [
            'orders' => Order::query()
                ->with('user')
                ->when($this->search, fn($q) => $q->where('order_number', 'like', "%{$this->search}%"))
                ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
                ->latest()
                ->paginate(10),
        ]);
    }
}
