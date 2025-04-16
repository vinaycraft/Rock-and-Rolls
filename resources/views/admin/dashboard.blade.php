@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1>Dashboard</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-light" onclick="window.print()">
                <i class="fas fa-print me-1"></i>Print
            </button>
            <button type="button" class="btn btn-sm btn-outline-light">
                <i class="fas fa-download me-1"></i>Export
            </button>
        </div>
    </div>
</div>

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<!-- Dashboard Content -->
<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card bg-primary text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase small mb-1">Today's Orders</div>
                        <div class="h3 mb-0">{{ $todayOrders ?? 0 }}</div>
                    </div>
                    <div>
                        <i class="fas fa-calendar fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card bg-success text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase small mb-1">Today's Revenue</div>
                        <div class="h3 mb-0">₹{{ number_format($todayRevenue ?? 0, 2) }}</div>
                    </div>
                    <div>
                        <i class="fas fa-dollar-sign fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card bg-info text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase small mb-1">Active Menu Items</div>
                        <div class="h3 mb-0">{{ $activeMenuItems ?? 0 }}</div>
                    </div>
                    <div>
                        <i class="fas fa-utensils fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card bg-warning text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-uppercase small mb-1">Pending Orders</div>
                        <div class="h3 mb-0">{{ $pendingOrders ?? 0 }}</div>
                    </div>
                    <div>
                        <i class="fas fa-clock fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold">Recent Orders</h6>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders ?? [] as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->user->name ?? 'N/A' }}</td>
                                <td>{{ $order->items->count() ?? 0 }} items</td>
                                <td>₹{{ number_format($order->total ?? 0, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-3">No recent orders found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold">Popular Items</h6>
            </div>
            <div class="card-body">
                @forelse($popularItems ?? [] as $item)
                <div class="d-flex align-items-center mb-3">
                    <div class="flex-shrink-0">
                        <img src="{{ asset($item->image_path) }}" alt="{{ $item->name }}" class="rounded" style="width: 48px; height: 48px; object-fit: cover;">
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0">{{ $item->name }}</h6>
                        <small class="text-muted">{{ $item->orders_count }} orders</small>
                    </div>
                </div>
                @empty
                <p class="text-center py-3">No popular items found.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<style>
.status-select {
    background-color: var(--dark);
    color: var(--light);
    border: 1px solid var(--secondary);
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
}

.status-select:focus {
    outline: none;
    border-color: var(--primary);
}

.card {
    background-color: var(--dark);
    border: none;
}

.card-header {
    background-color: rgba(255, 255, 255, 0.05);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.table {
    color: var(--light);
}

.table td, .table th {
    border-color: rgba(255, 255, 255, 0.1);
}

.font-weight-bold {
    color: var(--light);
}
</style>
@endsection
