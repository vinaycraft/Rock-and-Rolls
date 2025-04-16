<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Dish;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        try {
            $orders = auth()->user()
                ->orders()
                ->with(['items.dish'])
                ->latest()
                ->get();

            return view('orders.index', compact('orders'));
        } catch (\Exception $e) {
            Log::error('Error fetching orders: ' . $e->getMessage());
            return back()->with('error', 'Unable to fetch orders. Please try again.');
        }
    }

    public function show(Order $order)
    {
        try {
            if ($order->user_id !== auth()->id()) {
                abort(403);
            }

            // Ensure the order exists and belongs to the user
            $order = Order::where('id', $order->id)
                ->where('user_id', auth()->id())
                ->with(['items.dish'])
                ->firstOrFail();

            return view('orders.show', compact('order'));
        } catch (\Exception $e) {
            Log::error('Error showing order: ' . $e->getMessage());
            return back()->with('error', 'Unable to view order. Please try again.');
        }
    }

    public function store(Request $request)
    {
        $cartItems = Session::get('cart', []);
        if (empty($cartItems)) {
            return back()->with('error', 'Your cart is empty.');
        }

        try {
            DB::beginTransaction();

            $order = Order::create([
                'user_id' => auth()->id(),
                'status' => 'pending',
                'total' => 0
            ]);

            $total = 0;

            foreach ($cartItems as $cartId => $item) {
                try {
                    $dish = Dish::findOrFail($item['dish_id']);
                    $price = $item['has_cheese'] ? $dish->price_with_cheese : $dish->base_price;

                    $orderItem = new OrderItem([
                        'dish_id' => $dish->id,
                        'quantity' => $item['quantity'],
                        'has_cheese' => $item['has_cheese'],
                        'price' => $price
                    ]);

                    $order->items()->save($orderItem);
                    $total += $price * $item['quantity'];
                } catch (\Exception $e) {
                    Log::error("Failed to add item to order: " . $e->getMessage());
                    continue;
                }
            }

            $order->total = $total;
            $order->save();
            
            DB::commit();
            Session::forget('cart');

            return redirect()->route('orders.show', $order)
                ->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order creation failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to place order. Please try again.');
        }
    }

    public function cancel(Order $order)
    {
        try {
            if ($order->user_id !== auth()->id()) {
                abort(403);
            }

            if ($order->status !== 'pending') {
                return back()->with('error', 'Only pending orders can be cancelled.');
            }

            $order->update(['status' => 'cancelled']);
            return back()->with('success', 'Order cancelled successfully.');
        } catch (\Exception $e) {
            Log::error('Error cancelling order: ' . $e->getMessage());
            return back()->with('error', 'Unable to cancel order. Please try again.');
        }
    }
}