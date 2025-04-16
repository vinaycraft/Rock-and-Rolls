@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Shopping Cart</h2>

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

    @if(count($cart) > 0)
        <div class="row">
            <div class="col-md-8">
                @foreach($cart as $id => $item)
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row align-items-center">
                                @if($item['dish']->image_path)
                                    <div class="col-md-2">
                                        <img src="{{ asset('storage/' . $item['dish']->image_path) }}" 
                                             class="img-fluid rounded" 
                                             alt="{{ $item['dish']->name }}">
                                    </div>
                                @endif
                                
                                <div class="col">
                                    <h5 class="card-title">{{ $item['dish']->name }}</h5>
                                    <p class="card-text mb-1">
                                        @if($item['has_cheese'])
                                            With Extra Cheese
                                        @else
                                            Regular
                                        @endif
                                    </p>
                                    <p class="card-text">₹{{ number_format($item['price'], 2) }} x {{ $item['quantity'] }}</p>
                                </div>

                                <div class="col-md-3 text-end">
                                    <p class="h5 mb-3">₹{{ number_format($item['subtotal'], 2) }}</p>
                                    <form action="{{ route('cart.remove', $id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i> Remove
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Order Summary</h5>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Total:</span>
                            <span class="h5">₹{{ number_format($total, 2) }}</span>
                        </div>

                        <form action="{{ route('orders.store') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100">
                                Place Order
                            </button>
                        </form>

                        <form action="{{ route('cart.clear') }}" method="POST" class="mt-2">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary w-100">
                                Clear Cart
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
            <h3>Your cart is empty</h3>
            <p class="text-muted">Add some delicious items from our menu!</p>
            <a href="{{ route('menu') }}" class="btn btn-primary">
                Browse Menu
            </a>
        </div>
    @endif
</div>
@endsection
