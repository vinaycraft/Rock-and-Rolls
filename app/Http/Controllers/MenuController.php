<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MenuController extends Controller
{
    public function index()
    {
        $dishes = Dish::where('is_available', true)
            ->orderBy('name')
            ->get();

        return view('menu.index', compact('dishes'));
    }

    public function addToCart(Request $request, Dish $dish)
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:10'],
            'has_cheese' => ['boolean'],
        ]);

        $cart = Session::get('cart', []);
        $cartItemId = uniqid();
        $hasCheese = $request->boolean('has_cheese', false);

        $cart[$cartItemId] = [
            'dish_id' => $dish->id,
            'quantity' => $request->quantity,
            'has_cheese' => $hasCheese,
        ];

        Session::put('cart', $cart);

        return back()->with('success', 'Item added to cart successfully!');
    }
}
