<?php

namespace App\Livewire\Admin;

use App\Models\Transaction;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class TransactionIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $selectedTransaction;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function showTransaction(int $id): void
    {
        $this->selectedTransaction = Transaction::with(['user', 'order', 'reviewer'])
            ->find($id);

        $this->modal('transaction-detail-modal')->show();
    }

    public function reviewTransaction(int $id, string $status): void
    {
        if (!in_array($status, ['approved', 'rejected'], true)) {
            return;
        }

        $transaction = Transaction::with('order.items.product')->findOrFail($id);
        $previousStatus = $transaction->status;

        if ($status === 'rejected' && $previousStatus !== 'rejected') {
            foreach ($transaction->order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                }
            }
        }

        if ($status === 'approved' && $previousStatus === 'rejected') {
            foreach ($transaction->order->items as $item) {
                if ($item->product) {
                    $item->product->decrement('stock', $item->quantity);
                }
            }
        }

        $transaction->update([
            'status' => $status,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'admin_notes' => $status === 'approved'
                ? 'Payment approved by admin.'
                : 'Payment rejected by admin. Please upload a valid transaction screenshot.',
        ]);

        $transaction->order->update([
            'payment_status' => $status,
            'payment_reviewed_at' => now(),
            'payment_notes' => $transaction->admin_notes,
        ]);

        $this->selectedTransaction = Transaction::with(['user', 'order', 'reviewer'])
            ->find($id);

        Flux::toast(
            text: "Transaction for order #{$transaction->order->order_number} {$status}.",
            variant: $status === 'approved' ? 'success' : 'warning',
        );
    }

    public function render()
    {
        return view('livewire.admin.transaction-index', [
            'transactions' => Transaction::query()
                ->with(['user', 'order', 'reviewer'])
                ->when($this->search, function ($query) {
                    $query->where(function ($subQuery) {
                        $subQuery
                            ->whereHas('order', fn($orderQuery) => $orderQuery->where('order_number', 'like', "%{$this->search}%"))
                            ->orWhereHas('user', fn($userQuery) => $userQuery->where('name', 'like', "%{$this->search}%"));
                    });
                })
                ->when($this->statusFilter, fn($query) => $query->where('status', $this->statusFilter))
                ->latest()
                ->paginate(10),
        ]);
    }
}
