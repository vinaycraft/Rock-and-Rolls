<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dish;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Get today's stats
            $today = Carbon::today();
            $todayOrders = Order::whereDate('created_at', $today)->count();
            $todayRevenue = Order::whereDate('created_at', $today)
                ->where('status', 'completed')
                ->sum('total');

            // Get active menu items
            $activeMenuItems = Dish::where('is_available', true)->count();

            // Get pending orders
            $pendingOrders = Order::where('status', 'pending')->count();

            // Get recent orders with users
            $recentOrders = Order::with(['user', 'items.dish'])
                ->latest()
                ->take(5)
                ->get();

            // Get popular items (last 30 days)
            $popularItems = DB::table('dishes')
                ->select([
                    'dishes.id',
                    'dishes.name',
                    'dishes.description',
                    'dishes.base_price',
                    'dishes.price_with_cheese',
                    'dishes.has_cheese_variant',
                    'dishes.category',
                    'dishes.image_path',
                    'dishes.is_available',
                    'dishes.created_at',
                    'dishes.updated_at',
                    DB::raw('COUNT(DISTINCT orders.id) as orders_count')
                ])
                ->leftJoin('order_items', 'dishes.id', '=', 'order_items.dish_id')
                ->leftJoin('orders', function($join) {
                    $join->on('order_items.order_id', '=', 'orders.id')
                        ->whereDate('orders.created_at', '>=', now()->subDays(30))
                        ->where('orders.status', 'completed');
                })
                ->groupBy([
                    'dishes.id',
                    'dishes.name',
                    'dishes.description',
                    'dishes.base_price',
                    'dishes.price_with_cheese',
                    'dishes.has_cheese_variant',
                    'dishes.category',
                    'dishes.image_path',
                    'dishes.is_available',
                    'dishes.created_at',
                    'dishes.updated_at'
                ])
                ->orderByDesc('orders_count')
                ->take(4)
                ->get();

            return view('admin.dashboard', compact(
                'todayOrders',
                'todayRevenue',
                'activeMenuItems',
                'pendingOrders',
                'recentOrders',
                'popularItems'
            ));
        } catch (\Exception $e) {
            \Log::error('Dashboard Error: ' . $e->getMessage());
            return view('admin.dashboard')->with('error', 'Error loading dashboard data.');
        }
    }
}
