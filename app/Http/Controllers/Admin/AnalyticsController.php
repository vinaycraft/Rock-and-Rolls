<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Dish;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index()
    {
        try {
            // Get the current date and 7 days ago
            $now = Carbon::now();
            $sevenDaysAgo = $now->copy()->subDays(6);
            
            // Daily Revenue for the last 7 days
            $dailyRevenueQuery = Order::where('status', 'completed')
                ->whereBetween('created_at', [$sevenDaysAgo->startOfDay(), $now->endOfDay()])
                ->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('COALESCE(SUM(total), 0) as revenue')
                )
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->keyBy('date');

            // Fill in missing dates with zero revenue
            $dailyRevenue = collect();
            for ($date = $sevenDaysAgo->copy(); $date <= $now; $date->addDay()) {
                $dateStr = $date->format('Y-m-d');
                $dailyRevenue->push((object)[
                    'date' => $dateStr,
                    'revenue' => $dailyRevenueQuery->has($dateStr) ? $dailyRevenueQuery->get($dateStr)->revenue : 0
                ]);
            }

            // Monthly Revenue for the last 6 months
            $sixMonthsAgo = $now->copy()->subMonths(5)->startOfMonth();
            $monthlyRevenue = Order::where('status', 'completed')
                ->whereBetween('created_at', [$sixMonthsAgo, $now->endOfMonth()])
                ->select(
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('MONTHNAME(created_at) as month_name'),
                    DB::raw('COALESCE(SUM(total), 0) as revenue')
                )
                ->groupBy('month', 'month_name')
                ->orderBy('month')
                ->get();

            // Top 5 Dishes by Order Quantity
            $topDishes = DB::table('order_items')
                ->join('dishes', 'order_items.dish_id', '=', 'dishes.id')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->where('orders.status', 'completed')
                ->select(
                    'dishes.name',
                    DB::raw('COALESCE(SUM(order_items.quantity), 0) as total_quantity'),
                    DB::raw('COALESCE(SUM(order_items.price * order_items.quantity), 0) as total_revenue')
                )
                ->groupBy('dishes.id', 'dishes.name')
                ->orderByDesc('total_quantity')
                ->limit(5)
                ->get();

            // Weekly Revenue for the last 4 weeks
            $fourWeeksAgo = $now->copy()->subWeeks(3)->startOfWeek();
            $weeklyRevenue = Order::where('status', 'completed')
                ->whereBetween('created_at', [$fourWeeksAgo, $now->endOfWeek()])
                ->select(
                    DB::raw('YEARWEEK(created_at) as yearweek'),
                    DB::raw('CONCAT("Week ", WEEK(created_at)) as week'),
                    DB::raw('COALESCE(SUM(total), 0) as revenue')
                )
                ->groupBy('yearweek', 'week')
                ->orderBy('yearweek')
                ->get();

            // Get order statistics
            $totalOrders = Order::count();
            $pendingOrders = Order::where('status', 'pending')->count();
            $deliveredOrders = Order::where('status', 'completed')->count();
            $totalRevenue = Order::where('status', 'completed')->sum('total') ?? 0;

            return view('admin.analytics', compact(
                'dailyRevenue',
                'monthlyRevenue',
                'topDishes',
                'weeklyRevenue',
                'totalOrders',
                'pendingOrders',
                'deliveredOrders',
                'totalRevenue'
            ));
        } catch (\Exception $e) {
            \Log::error('Analytics Error: ' . $e->getMessage());
            return back()->with('error', 'Error loading analytics data. Please try again.');
        }
    }
}
