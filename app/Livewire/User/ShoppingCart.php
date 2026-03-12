<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\Product;

class ShoppingCart extends Component
{
    public $cart = [];
    public $total = 0;

    public function mount()
    {
        $this->loadCart();
    }

    public function loadCart()
    {
        $this->cart = session()->get('cart', []);
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->total = array_reduce($this->cart, function ($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);
    }

    public function updateQuantity($id, $action)
    {
        if (isset($this->cart[$id])) {
            if ($action === 'increase') {
                $this->cart[$id]['quantity']++;
            } elseif ($action === 'decrease' && $this->cart[$id]['quantity'] > 1) {
                $this->cart[$id]['quantity']--;
            }

            session()->put('cart', $this->cart);
            $this->calculateTotal();

            // Optional: Emit event to update a Navbar cart counter
            $this->dispatch('cart-updated');
        }
    }

    public function removeItem($id)
    {
        unset($this->cart[$id]);
        session()->put('cart', $this->cart);
        $this->calculateTotal();
        $this->dispatch('cart-updated');
    }

    public function render()
    {
        return view('livewire.user.shopping-cart');
    }
}
