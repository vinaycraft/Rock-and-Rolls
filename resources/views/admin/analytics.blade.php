@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Analytics</h2>

    <div class="row">
        <!-- Daily Revenue Chart -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Daily Revenue (Last 7 Days)</h5>
                </div>
                <div class="card-body">
                    <canvas id="dailyRevenueChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Monthly Revenue Chart -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Monthly Revenue (Last 6 Months)</h5>
                </div>
                <div class="card-body">
                    <canvas id="monthlyRevenueChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Selling Dishes -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Top Selling Dishes</h5>
                </div>
                <div class="card-body">
                    <canvas id="topDishesChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Weekly Revenue Chart -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Weekly Revenue (Last 4 Weeks)</h5>
                </div>
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
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            }
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
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            }
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
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right',
                }
            }
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
                borderColor: 'rgb(153, 102, 255)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });
</script>
@endpush
@endsection
