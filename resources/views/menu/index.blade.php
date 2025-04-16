@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Our Menu</h2>

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

    <div class="row">
        @foreach($dishes as $dish)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    @if($dish->image_path)
                        <img src="{{ Storage::url($dish->image_path) }}" 
                             class="card-img-top" 
                             alt="{{ $dish->name }}"
                             style="height: 200px; object-fit: cover;">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center" 
                             style="height: 200px;">
                            <i class="fas fa-pizza-slice fa-3x text-muted"></i>
                        </div>
                    @endif

                    <div class="card-body">
                        <h5 class="card-title">{{ $dish->name }}</h5>
                        <p class="card-text text-muted">{{ $dish->description }}</p>

                        <form action="{{ route('cart.add', $dish) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               name="has_cheese" id="cheese_{{ $dish->id }}"
                                               onchange="updatePrice(this, {{ $dish->id }}, {{ $dish->base_price }}, {{ $dish->price_with_cheese }})">
                                        <label class="form-check-label" for="cheese_{{ $dish->id }}">
                                            Extra Cheese
                                        </label>
                                    </div>
                                    <span class="text-primary" id="price_{{ $dish->id }}">
                                        ₹{{ number_format($dish->base_price, 2) }}
                                    </span>
                                </div>

                                <div class="input-group">
                                    <input type="number" class="form-control" name="quantity" 
                                           value="1" min="1" max="10">
                                    <button type="submit" class="btn btn-primary">
                                        Add to Cart
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@push('scripts')
<script>
function updatePrice(checkbox, dishId, basePrice, priceWithCheese) {
    const priceElement = document.getElementById('price_' + dishId);
    const price = checkbox.checked ? priceWithCheese : basePrice;
    priceElement.textContent = '₹' + price.toFixed(2);
}
</script>
@endpush
@endsection
