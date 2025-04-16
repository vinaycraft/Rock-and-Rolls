<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dish;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DishController extends Controller
{
    public function index()
    {
        try {
            $dishes = Dish::orderBy('category')
                ->orderBy('name')
                ->paginate(12);

            $categories = Dish::distinct()
                ->pluck('category')
                ->filter()
                ->values()
                ->all();

            return view('admin.dishes.index', compact('dishes', 'categories'));
        } catch (\Exception $e) {
            \Log::error('Error in DishController@index: ' . $e->getMessage());
            return back()->with('error', 'Error loading dishes. Please try again.');
        }
    }

    public function create()
    {
        $categories = Dish::distinct()
            ->pluck('category')
            ->filter()
            ->values()
            ->all();

        return view('admin.dishes.create', compact('categories'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'base_price' => ['required', 'numeric', 'min:0'],
                'price_with_cheese' => ['nullable', 'numeric', 'min:0'],
                'has_cheese_variant' => ['boolean'],
                'category' => ['required', 'string'],
                'image' => ['nullable', 'image', 'max:2048'],
                'is_available' => ['boolean'],
            ]);

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('dishes', 'public');
                $validated['image_path'] = $path;
            }

            $dish = Dish::create($validated);

            return redirect()
                ->route('admin.dishes.index')
                ->with('success', 'Dish created successfully.');
        } catch (\Exception $e) {
            \Log::error('Error in DishController@store: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Error creating dish. Please try again.');
        }
    }

    public function edit(Dish $dish)
    {
        $categories = Dish::distinct()
            ->pluck('category')
            ->filter()
            ->values()
            ->all();

        return view('admin.dishes.edit', compact('dish', 'categories'));
    }

    public function update(Request $request, Dish $dish)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'base_price' => ['required', 'numeric', 'min:0'],
                'price_with_cheese' => ['nullable', 'numeric', 'min:0'],
                'has_cheese_variant' => ['boolean'],
                'category' => ['required', 'string'],
                'image' => ['nullable', 'image', 'max:2048'],
                'is_available' => ['boolean'],
            ]);

            if ($request->hasFile('image')) {
                if ($dish->image_path) {
                    Storage::disk('public')->delete($dish->image_path);
                }
                $path = $request->file('image')->store('dishes', 'public');
                $validated['image_path'] = $path;
            }

            $dish->update($validated);

            return redirect()
                ->route('admin.dishes.index')
                ->with('success', 'Dish updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Error in DishController@update: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Error updating dish. Please try again.');
        }
    }

    public function destroy(Dish $dish)
    {
        try {
            if ($dish->image_path) {
                Storage::disk('public')->delete($dish->image_path);
            }

            $dish->delete();

            return redirect()
                ->route('admin.dishes.index')
                ->with('success', 'Dish deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error in DishController@destroy: ' . $e->getMessage());
            return back()->with('error', 'Error deleting dish. Please try again.');
        }
    }
}
