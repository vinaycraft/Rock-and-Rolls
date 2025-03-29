<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminAnalyticsController extends Controller
{
    public function index()
    {
        // Daily revenue for the last 7 days
        $dailyRevenue = Order::where('status', 'delivered')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get([
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as revenue')
            ]);

        // Weekly revenue for the last 4 weeks
        $weeklyRevenue = Order::where('status', 'delivered')
            ->where('created_at', '>=', Carbon::now()->subWeeks(4))
            ->groupBy('week')
            ->orderBy('week')
            ->get([
                DB::raw('YEARWEEK(created_at) as week'),
                DB::raw('SUM(total_amount) as revenue')
            ]);

        // Monthly revenue for the last 6 months
        $monthlyRevenue = Order::where('status', 'delivered')
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get([
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('SUM(total_amount) as revenue')
            ]);

        // Top selling dishes
        $topDishes = DB::table('order_items')
            ->join('dishes', 'order_items.dish_id', '=', 'dishes.id')
            ->select('dishes.name', DB::raw('SUM(order_items.quantity) as total_quantity'))
            ->groupBy('dishes.id', 'dishes.name')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();

        return view('admin.analytics', compact(
            'dailyRevenue',
            'weeklyRevenue',
            'monthlyRevenue',
            'topDishes'
        ));
    }
}
