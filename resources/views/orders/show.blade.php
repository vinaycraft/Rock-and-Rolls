@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="mb-4">
        <h2>Order #{{ $order->id }}</h2>
        <p class="text-muted mb-0">Placed on: {{ $order->created_at->format('M d, Y H:i A') }}</p>
        <p class="mb-0">
            Status: 
            <span class="badge bg-{{ $order->status === 'pending' ? 'warning' : ($order->status === 'completed' ? 'success' : 'danger') }}">
                {{ ucfirst($order->status) }}
            </span>
        </p>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Order Items</h5>

                    @forelse($order->items as $item)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="mb-1">{{ $item->dish_name }}</h6>
                                <p class="text-muted mb-0">
                                    {{ $item->has_cheese ? 'With Extra Cheese' : 'Regular' }}
                                </p>
                                <p class="mb-0">
                                    ₹{{ number_format($item->price, 2) }} x {{ $item->quantity }}
                                </p>
                            </div>
                            <div class="text-end">
                                <p class="h6 mb-0">₹{{ number_format($item->subtotal, 2) }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">No items found in this order.</p>
                    @endforelse

                    <hr>

                    <div class="d-flex justify-content-between">
                        <h5>Total</h5>
                        <h5>₹{{ number_format($order->total, 2) }}</h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Actions</h5>

                    @if($order->status === 'pending')
                        <form action="{{ route('orders.cancel', $order) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-danger w-100">
                                Cancel Order
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                        Back to Orders
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
