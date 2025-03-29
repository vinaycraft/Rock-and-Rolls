<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Dish;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = auth()->user()->orders()->latest()->get();
        return view('orders.index', compact('orders'));
    }

    public function store(Request $request)
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Your cart is empty!');
        }

        $total = 0;
        foreach ($cart as $id => $details) {
            $dish = Dish::find($id);
            if ($dish) {
                $total += $dish->price * $details['quantity'];
            }
        }

        $order = Order::create([
            'user_id' => auth()->id(),
            'total_amount' => $total,
            'status' => 'pending'
        ]);

        foreach ($cart as $id => $details) {
            $dish = Dish::find($id);
            if ($dish) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'dish_id' => $dish->id,
                    'quantity' => $details['quantity'],
                    'price' => $dish->price
                ]);
            }
        }

        session()->forget('cart');
        return redirect()->route('my-orders')->with('success', 'Order placed successfully!');
    }
}
