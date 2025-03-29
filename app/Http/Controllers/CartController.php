<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        $cartItems = [];

        foreach ($cart as $id => $details) {
            $dish = Dish::find($id);
            if ($dish) {
                $cartItems[] = [
                    'dish' => $dish,
                    'quantity' => $details['quantity']
                ];
                $total += $dish->price * $details['quantity'];
            }
        }

        return view('cart.index', compact('cartItems', 'total'));
    }

    public function add(Request $request, Dish $dish)
    {
        $cart = session()->get('cart', []);
        $quantity = $request->input('quantity', 1);

        if (isset($cart[$dish->id])) {
            $cart[$dish->id]['quantity'] += $quantity;
        } else {
            $cart[$dish->id] = [
                'quantity' => $quantity
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Item added to cart successfully!');
    }

    public function remove(Dish $dish)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$dish->id])) {
            unset($cart[$dish->id]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Item removed from cart successfully!');
    }
}
