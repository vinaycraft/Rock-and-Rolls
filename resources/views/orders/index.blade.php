@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">My Orders</h2>

    @if($orders->count() > 0)
        @foreach($orders as $order)
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Order #{{ $order->id }}</span>
                        <span class="badge bg-{{ $order->status === 'delivered' ? 'success' : 
                            ($order->status === 'preparing' ? 'warning' : 
                            ($order->status === 'ready' ? 'info' : 'secondary')) }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>{{ $item->dish->name }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>₹{{ number_format($item->price, 2) }}</td>
                                        <td>₹{{ number_format($item->price * $item->quantity, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                    <td><strong>₹{{ number_format($order->total_amount, 2) }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="text-muted">
                        Ordered on: {{ $order->created_at->format('M d, Y H:i A') }}
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="alert alert-info">
            You haven't placed any orders yet. <a href="{{ route('menu') }}">Start ordering</a>
        </div>
    @endif
</div>
@endsection
