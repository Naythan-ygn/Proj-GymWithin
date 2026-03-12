<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
    {
        // Redirect guests to register if they try to access checkout
        if (!Auth::check()){
            return redirect()->route('register');
        }

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index');
        }

        $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));

        // ENSURE THIS POINTS TO THE NEW FILE
        return view('partials.checkout.index', compact('cart', 'total'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string|max:500',
        ]);

        $cart = session()->get('cart', []);
        $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));

        // 1. Create the Order Header
        $order = Order::create([
            'user_id' => Auth::id(),
            'order_number' => 'GW-' . strtoupper(str()->random(8)),
            'total_price' => $total,
            'shipping_address' => $request->shipping_address,
            'status' => 'pending',
        ]);

        // 2. Move Cart Items to OrderItems table
        foreach ($cart as $productId => $details) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'quantity' => $details['quantity'],
                'price' => $details['price'],
            ]);

            // Optional: Reduce stock level in Product model
            $product = \App\Models\Product::find($productId);
            $product->decrement('stock', $details['quantity']);
        }

        // 3. Clear the Cart
        session()->forget('cart');

        return redirect()->route('checkout.success', ['order_number' => $order->order_number]);
    }
}
