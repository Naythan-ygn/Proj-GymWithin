<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $bankDetails = config('bank');

        return view('partials.checkout.index', compact('cart', 'total', 'bankDetails'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string|max:500',
            'payment_screenshot' => 'required|image|max:5120',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
        $bankDetails = config('bank');
        $order = null;

        DB::transaction(function () use ($request, $cart, $total, $bankDetails, &$order) {
            $screenshotPath = $request->file('payment_screenshot')->store('transactions', 'public');

            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => 'GW-' . strtoupper(str()->random(8)),
                'total_price' => $total,
                'shipping_address' => $request->shipping_address,
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => 'bank_transfer',
            ]);

            foreach ($cart as $productId => $details) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'quantity' => $details['quantity'],
                    'price' => $details['price'],
                ]);

                $product = \App\Models\Product::find($productId);
                if ($product) {
                    $product->decrement('stock', $details['quantity']);
                }
            }

            Transaction::create([
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'bank_name' => $bankDetails['bank_name'],
                'account_name' => $bankDetails['account_name'],
                'account_number' => $bankDetails['account_number'],
                'amount' => $total,
                'screenshot_path' => $screenshotPath,
                'status' => 'pending',
            ]);
        });

        session()->forget('cart');

        return redirect()
            ->route('checkout.success', ['order_number' => $order->order_number])
            ->with('success', 'Your payment proof was submitted. Waiting for admin approval.');
    }
}
