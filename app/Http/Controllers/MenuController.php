<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $dishes = Dish::where('is_available', true)
                     ->orderBy('category')
                     ->get();

        return view('menu.index', compact('dishes'));
    }
}
