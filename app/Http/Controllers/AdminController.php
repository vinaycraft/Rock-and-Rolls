<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Dish;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        // Auth middleware is already applied in routes
        // Admin middleware is already applied in routes
    }

    public function dashboard()
    {
        try {
            $stats = [
                'total_orders' => Order::count(),
                'total_dishes' => Dish::count(),
                'total_users' => User::where('is_admin', false)->count(),
                'recent_orders' => Order::with('user')
                    ->latest()
                    ->take(5)
                    ->get()
            ];

            return view('admin.dashboard', compact('stats'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading dashboard data.');
        }
    }

    public function orders()
    {
        try {
            $orders = Order::with('user')
                ->latest()
                ->paginate(10);

            return view('admin.orders.index', compact('orders'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading orders.');
        }
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        try {
            $validated = $request->validate([
                'status' => ['required', 'string', 'in:pending,preparing,ready,delivered,cancelled']
            ]);

            $order->update(['status' => $validated['status']]);
            return back()->with('success', 'Order status updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating order status.');
        }
    }

    public function dishes()
    {
        try {
            $dishes = Dish::latest()->paginate(10);
            return view('admin.dishes.index', compact('dishes'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading dishes.');
        }
    }

    public function storeDish(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'price' => ['required', 'numeric', 'min:0'],
                'category' => ['required', 'string'],
                'is_available' => ['boolean']
            ]);

            Dish::create($validated);
            return redirect()->route('admin.dishes')->with('success', 'Dish created successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error creating dish.');
        }
    }

    public function updateDish(Request $request, Dish $dish)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'price' => ['required', 'numeric', 'min:0'],
                'category' => ['required', 'string'],
                'is_available' => ['boolean']
            ]);

            $dish->update($validated);
            return back()->with('success', 'Dish updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating dish.');
        }
    }

    public function deleteDish(Dish $dish)
    {
        try {
            $dish->delete();
            return back()->with('success', 'Dish deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting dish.');
        }
    }
}
