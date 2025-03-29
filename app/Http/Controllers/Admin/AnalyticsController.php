<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index()
    {
        // Sample data for demonstration
        // In a real application, this would come from your database
        
        // Daily Revenue
        $dailyRevenue = collect([
            ['date' => '2025-03-23', 'revenue' => 1500],
            ['date' => '2025-03-24', 'revenue' => 2200],
            ['date' => '2025-03-25', 'revenue' => 1800],
            ['date' => '2025-03-26', 'revenue' => 2400],
            ['date' => '2025-03-27', 'revenue' => 2100],
            ['date' => '2025-03-28', 'revenue' => 2800],
            ['date' => '2025-03-29', 'revenue' => 2300],
        ]);

        // Monthly Revenue
        $monthlyRevenue = collect([
            ['month' => 'October', 'revenue' => 45000],
            ['month' => 'November', 'revenue' => 52000],
            ['month' => 'December', 'revenue' => 58000],
            ['month' => 'January', 'revenue' => 48000],
            ['month' => 'February', 'revenue' => 54000],
            ['month' => 'March', 'revenue' => 62000],
        ]);

        // Top Dishes
        $topDishes = collect([
            ['name' => 'Butter Chicken', 'total_quantity' => 150],
            ['name' => 'Paneer Tikka', 'total_quantity' => 120],
            ['name' => 'Biryani', 'total_quantity' => 180],
            ['name' => 'Naan', 'total_quantity' => 200],
            ['name' => 'Dal Makhani', 'total_quantity' => 90],
        ]);

        // Weekly Revenue
        $weeklyRevenue = collect([
            ['week' => 'Week 1', 'revenue' => 12000],
            ['week' => 'Week 2', 'revenue' => 14500],
            ['week' => 'Week 3', 'revenue' => 13800],
            ['week' => 'Week 4', 'revenue' => 15200],
        ]);

        return view('admin.analytics', compact(
            'dailyRevenue',
            'monthlyRevenue',
            'topDishes',
            'weeklyRevenue'
        ));
    }
}
