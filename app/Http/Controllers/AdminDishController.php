<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminDishController extends Controller
{
    public function index()
    {
        $dishes = Dish::all();
        return view('admin.dishes.index', compact('dishes'));
    }

    public function create()
    {
        return view('admin.dishes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'is_available' => 'boolean'
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('dishes', 'public');
            $validated['image_path'] = $path;
        }

        Dish::create($validated);
        return redirect()->route('admin.dishes.index')->with('success', 'Dish created successfully!');
    }

    public function edit(Dish $dish)
    {
        return view('admin.dishes.edit', compact('dish'));
    }

    public function update(Request $request, Dish $dish)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'is_available' => 'boolean'
        ]);

        if ($request->hasFile('image')) {
            if ($dish->image_path) {
                Storage::disk('public')->delete($dish->image_path);
            }
            $path = $request->file('image')->store('dishes', 'public');
            $validated['image_path'] = $path;
        }

        $dish->update($validated);
        return redirect()->route('admin.dishes.index')->with('success', 'Dish updated successfully!');
    }

    public function destroy(Dish $dish)
    {
        if ($dish->image_path) {
            Storage::disk('public')->delete($dish->image_path);
        }
        $dish->delete();
        return redirect()->route('admin.dishes.index')->with('success', 'Dish deleted successfully!');
    }
}
