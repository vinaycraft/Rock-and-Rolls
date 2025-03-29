@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Our Menu</h2>

    <div class="row">
        @foreach($dishes as $dish)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    @if($dish->image_path)
                        <img src="{{ asset($dish->image_path) }}" class="card-img-top" alt="{{ $dish->name }}">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $dish->name }}</h5>
                        <p class="card-text">{{ $dish->description }}</p>
                        <p class="card-text"><strong>₹{{ number_format($dish->price, 2) }}</strong></p>
                        <form action="{{ route('cart.add', $dish->id) }}" method="POST">
                            @csrf
                            <div class="input-group">
                                <button type="button" class="btn btn-outline-secondary decrease-quantity" onclick="decreaseQuantity(this)">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" name="quantity" class="form-control text-center quantity-input" 
                                       value="1" min="1" max="10" readonly>
                                <button type="button" class="btn btn-outline-secondary increase-quantity" onclick="increaseQuantity(this)">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-cart-plus"></i> Add
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
function decreaseQuantity(button) {
    const input = button.parentElement.querySelector('.quantity-input');
    const currentValue = parseInt(input.value);
    if (currentValue > 1) {
        input.value = currentValue - 1;
    }
}

function increaseQuantity(button) {
    const input = button.parentElement.querySelector('.quantity-input');
    const currentValue = parseInt(input.value);
    if (currentValue < 10) {
        input.value = currentValue + 1;
    }
}
</script>

<style>
.quantity-input {
    max-width: 60px;
    border-radius: 0;
}

.input-group .btn {
    z-index: 0;
}

.decrease-quantity, .increase-quantity {
    padding: 0.375rem 0.75rem;
}
</style>
@endsection
