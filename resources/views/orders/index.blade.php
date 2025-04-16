@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">My Orders</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if($orders->count() > 0)
        <div class="row">
            @foreach($orders as $order)
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="card-title mb-0">Order #{{ $order->id }}</h5>
                                <span class="badge bg-{{ $order->status === 'pending' ? 'warning' : ($order->status === 'completed' ? 'success' : 'danger') }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>

                            <p class="text-muted mb-3">
                                Placed on: {{ $order->created_at->format('M d, Y H:i A') }}
                            </p>

                            <div class="mb-3">
                                @foreach($order->items as $item)
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>{{ $item->dish->name }} x {{ $item->quantity }}</span>
                                        <span>₹{{ number_format($item->subtotal, 2) }}</span>
                                    </div>
                                @endforeach
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">Total:</h6>
                                <h6 class="mb-0">₹{{ number_format($order->total, 2) }}</h6>
                            </div>

                            <div class="d-flex gap-2">
                                <a href="{{ route('orders.show', $order) }}" class="btn btn-primary flex-grow-1">
                                    View Details
                                </a>
                                @if($order->status === 'pending')
                                    <form action="{{ route('orders.cancel', $order) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-danger">
                                            Cancel
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
            <h3>No orders yet</h3>
            <p class="text-muted">You haven't placed any orders yet.</p>
            <a href="{{ route('menu') }}" class="btn btn-primary">
                Browse Menu
            </a>
        </div>
    @endif
</div>
@endsection
