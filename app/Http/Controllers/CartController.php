<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $cartItems = Session::get('cart', []);
        $total = 0;
        $cart = [];

        if (!empty($cartItems)) {
            foreach ($cartItems as $id => $item) {
                try {
                    $dish = Dish::findOrFail($item['dish_id']);
                    $price = $item['has_cheese'] ? $dish->price_with_cheese : $dish->base_price;
                    $cart[$id] = [
                        'dish' => $dish,
                        'quantity' => $item['quantity'],
                        'has_cheese' => $item['has_cheese'],
                        'price' => $price,
                        'subtotal' => $price * $item['quantity']
                    ];
                    $total += $cart[$id]['subtotal'];
                } catch (\Exception $e) {
                    // If dish not found, remove it from cart
                    unset($cartItems[$id]);
                    Session::put('cart', $cartItems);
                    continue;
                }
            }
        }

        return view('cart.index', compact('cart', 'total'));
    }

    public function addToCart(Request $request, Dish $dish)
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:10'],
            'has_cheese' => ['boolean'],
        ]);

        $cartItems = Session::get('cart', []);
        $cartItemId = uniqid();
        $hasCheese = $request->boolean('has_cheese', false);

        $cartItems[$cartItemId] = [
            'dish_id' => $dish->id,
            'quantity' => $request->quantity,
            'has_cheese' => $hasCheese,
        ];

        Session::put('cart', $cartItems);

        return back()->with('success', 'Item added to cart successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:10'],
            'has_cheese' => ['boolean'],
        ]);

        $cartItems = Session::get('cart', []);

        if (isset($cartItems[$id])) {
            $cartItems[$id]['quantity'] = $request->quantity;
            $cartItems[$id]['has_cheese'] = $request->boolean('has_cheese', false);
            Session::put('cart', $cartItems);
            return back()->with('success', 'Cart updated successfully!');
        }

        return back()->with('error', 'Item not found in cart.');
    }

    public function remove($id)
    {
        $cartItems = Session::get('cart', []);

        if (isset($cartItems[$id])) {
            unset($cartItems[$id]);
            Session::put('cart', $cartItems);
            return back()->with('success', 'Item removed from cart successfully!');
        }

        return back()->with('error', 'Item not found in cart.');
    }

    public function clear()
    {
        Session::forget('cart');
        return back()->with('success', 'Cart cleared successfully!');
    }

    public function checkout()
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

            foreach ($cartItems as $item) {
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
            return back()->with('error', 'Failed to place order. Please try again.');
        }
    }
}
