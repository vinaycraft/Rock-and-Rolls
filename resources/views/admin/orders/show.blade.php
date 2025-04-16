@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1>Order #{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-light" onclick="window.print()">
                <i class="fas fa-print me-1"></i>Print
            </button>
        </div>
        <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="d-inline">
            @csrf
            <select name="status" class="form-select form-select-sm status-select" onchange="this.form.submit()" style="width: auto;">
                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="preparing" {{ $order->status === 'preparing' ? 'selected' : '' }}>Preparing</option>
                <option value="ready" {{ $order->status === 'ready' ? 'selected' : '' }}>Ready</option>
                <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold">Order Items</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items ?? [] as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if(isset($item['image_path']))
                                            <img src="{{ Storage::url($item['image_path']) }}" 
                                                 alt="{{ $item['name'] }}"
                                                 class="rounded me-2"
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center"
                                                 style="width: 40px; height: 40px;">
                                                <i class="fas fa-utensils text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <h6 class="mb-0">{{ $item['name'] ?? 'Unknown Item' }}</h6>
                                            @if(isset($item['has_cheese']) && $item['has_cheese'])
                                                <small class="text-warning">
                                                    <i class="fas fa-cheese me-1"></i>Extra Cheese
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $item['quantity'] ?? 1 }}</td>
                                <td>₹{{ number_format($item['price'] ?? 0, 2) }}</td>
                                <td>₹{{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1), 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Total Amount:</strong></td>
                                <td><strong>₹{{ number_format($order->total_amount, 2) }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold">Customer Details</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-muted mb-1">Name</label>
                    <p class="mb-0">{{ $order->user->name }}</p>
                </div>
                <div class="mb-3">
                    <label class="text-muted mb-1">Email</label>
                    <p class="mb-0">{{ $order->user->email }}</p>
                </div>
                <div class="mb-3">
                    <label class="text-muted mb-1">Order Date</label>
                    <p class="mb-0">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                </div>
                <div>
                    <label class="text-muted mb-1">Status</label>
                    <p class="mb-0">
                        <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'info') }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.status-select {
    background-color: var(--dark);
    color: white;
    border-color: rgba(255, 255, 255, 0.1);
}
.status-select:focus {
    background-color: var(--dark);
    color: white;
    border-color: rgba(255, 255, 255, 0.2);
    box-shadow: none;
}
.status-select option {
    background-color: var(--dark);
    color: white;
}

@media print {
    .btn-toolbar {
        display: none !important;
    }
}
</style>
@endsection
