@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Shopping Cart</h2>

    @if(count($cartItems) > 0)
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cartItems as $item)
                                <tr>
                                    <td>{{ $item['dish']->name }}</td>
                                    <td>₹{{ number_format($item['dish']->price, 2) }}</td>
                                    <td>{{ $item['quantity'] }}</td>
                                    <td>₹{{ number_format($item['dish']->price * $item['quantity'], 2) }}</td>
                                    <td>
                                        <form action="{{ route('cart.remove', $item['dish']->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                <td><strong>₹{{ number_format($total, 2) }}</strong></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <form action="{{ route('orders.store') }}" method="POST" class="mt-3">
                    @csrf
                    <button type="submit" class="btn btn-success">Place Order</button>
                </form>
            </div>
        </div>
    @else
        <div class="alert alert-info">
            Your cart is empty. <a href="{{ route('menu') }}">Continue shopping</a>
        </div>
    @endif
</div>
@endsection
