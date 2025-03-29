@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Analytics</h2>

    <div class="row">
        <!-- Daily Revenue Chart -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">Daily Revenue (Last 7 Days)</div>
                <div class="card-body">
                    <canvas id="dailyRevenueChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Monthly Revenue Chart -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">Monthly Revenue (Last 6 Months)</div>
                <div class="card-body">
                    <canvas id="monthlyRevenueChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Selling Dishes -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">Top Selling Dishes</div>
                <div class="card-body">
                    <canvas id="topDishesChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Weekly Revenue Chart -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">Weekly Revenue (Last 4 Weeks)</div>
                <div class="card-body">
                    <canvas id="weeklyRevenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Daily Revenue Chart
    new Chart(document.getElementById('dailyRevenueChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($dailyRevenue->pluck('date')) !!},
            datasets: [{
                label: 'Daily Revenue',
                data: {!! json_encode($dailyRevenue->pluck('revenue')) !!},
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        }
    });

    // Monthly Revenue Chart
    new Chart(document.getElementById('monthlyRevenueChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($monthlyRevenue->pluck('month')) !!},
            datasets: [{
                label: 'Monthly Revenue',
                data: {!! json_encode($monthlyRevenue->pluck('revenue')) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.5)'
            }]
        }
    });

    // Top Dishes Chart
    new Chart(document.getElementById('topDishesChart'), {
        type: 'pie',
        data: {
            labels: {!! json_encode($topDishes->pluck('name')) !!},
            datasets: [{
                data: {!! json_encode($topDishes->pluck('total_quantity')) !!},
                backgroundColor: [
                    'rgba(255, 99, 132, 0.5)',
                    'rgba(54, 162, 235, 0.5)',
                    'rgba(255, 206, 86, 0.5)',
                    'rgba(75, 192, 192, 0.5)',
                    'rgba(153, 102, 255, 0.5)'
                ]
            }]
        }
    });

    // Weekly Revenue Chart
    new Chart(document.getElementById('weeklyRevenueChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($weeklyRevenue->pluck('week')) !!},
            datasets: [{
                label: 'Weekly Revenue',
                data: {!! json_encode($weeklyRevenue->pluck('revenue')) !!},
                borderColor: 'rgb(255, 99, 132)',
                tension: 0.1
            }]
        }
    });
</script>
@endpush
@endsection
