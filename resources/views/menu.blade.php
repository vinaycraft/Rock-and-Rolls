@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        @foreach($dishes as $dish)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    @if($dish->image_path)
                        <img src="{{ asset('storage/' . $dish->image_path) }}" 
                             class="card-img-top" 
                             alt="{{ $dish->name }}"
                             style="height: 200px; object-fit: cover;">
                    @else
                        <div class="bg-light text-center p-4">
                            <i class="fas fa-utensils fa-3x text-muted"></i>
                        </div>
                    @endif
                    
                    <div class="card-body">
                        <h5 class="card-title">{{ $dish->name }}</h5>
                        <p class="card-text text-muted">{{ $dish->description }}</p>
                        
                        <div class="mb-3">
                            <span class="badge bg-{{ $dish->category === 'veg' ? 'success' : 'danger' }}">
                                {{ ucfirst($dish->category) }}
                            </span>
                        </div>

                        <form action="{{ route('cart.add', $dish) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Price:</label>
                                @if($dish->has_cheese_variant)
                                    <div class="form-check">
                                        <input type="radio" name="has_cheese" value="0" 
                                               class="form-check-input" id="regular_{{ $dish->id }}" 
                                               checked>
                                        <label class="form-check-label" for="regular_{{ $dish->id }}">
                                            Regular - ₹{{ number_format($dish->base_price, 2) }}
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input type="radio" name="has_cheese" value="1" 
                                               class="form-check-input" id="cheese_{{ $dish->id }}">
                                        <label class="form-check-label" for="cheese_{{ $dish->id }}">
                                            Extra Cheese - ₹{{ number_format($dish->price_with_cheese, 2) }}
                                        </label>
                                    </div>
                                @else
                                    <p class="mb-0">₹{{ number_format($dish->base_price, 2) }}</p>
                                    <input type="hidden" name="has_cheese" value="0">
                                @endif
                            </div>

                            <div class="d-flex align-items-center">
                                <div class="input-group me-2" style="width: 120px;">
                                    <input type="number" name="quantity" value="1" 
                                           min="1" max="10" class="form-control">
                                </div>
                                <button type="submit" class="btn btn-primary flex-grow-1">
                                    <i class="fas fa-cart-plus me-2"></i>Add to Cart
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
