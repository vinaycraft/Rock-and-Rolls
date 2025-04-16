@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Track Order #{{ $order->id }}</h2>
                <a href="{{ route('orders.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-1"></i>Back to Orders
                </a>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="tracking-progress">
                        <div class="tracking-step {{ in_array($order->status, ['pending', 'preparing', 'ready', 'delivered']) ? 'completed' : '' }}">
                            <div class="step-icon">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="step-content">
                                <h6>Order Placed</h6>
                                <p class="text-muted small mb-0">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>

                        <div class="tracking-step {{ in_array($order->status, ['preparing', 'ready', 'delivered']) ? 'completed' : '' }}">
                            <div class="step-icon">
                                <i class="fas fa-utensils"></i>
                            </div>
                            <div class="step-content">
                                <h6>Preparing</h6>
                                <p class="text-muted small mb-0">Your order is being prepared</p>
                            </div>
                        </div>

                        <div class="tracking-step {{ in_array($order->status, ['ready', 'delivered']) ? 'completed' : '' }}">
                            <div class="step-icon">
                                <i class="fas fa-bell"></i>
                            </div>
                            <div class="step-content">
                                <h6>Ready for Pickup</h6>
                                <p class="text-muted small mb-0">Your order is ready to be picked up</p>
                            </div>
                        </div>

                        <div class="tracking-step {{ $order->status === 'delivered' ? 'completed' : '' }}">
                            <div class="step-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="step-content">
                                <h6>Delivered</h6>
                                <p class="text-muted small mb-0">Order has been delivered</p>
                            </div>
                        </div>
                    </div>

                    <div class="order-summary mt-5">
                        <h5 class="mb-3">Order Summary</h5>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-end">Price</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
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
                                                        <h6 class="mb-0">{{ $item['name'] }}</h6>
                                                        @if(isset($item['has_cheese']) && $item['has_cheese'])
                                                            <small class="text-warning">
                                                                <i class="fas fa-cheese me-1"></i>Extra Cheese
                                                            </small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">{{ $item['quantity'] }}</td>
                                            <td class="text-end">₹{{ number_format($item['price'], 2) }}</td>
                                            <td class="text-end">₹{{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-group-divider">
                                    <tr>
                                        <td colspan="3" class="text-end">
                                            <strong>Total Amount:</strong>
                                        </td>
                                        <td class="text-end">
                                            <strong>₹{{ number_format($order->total_amount, 2) }}</strong>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.tracking-progress {
    position: relative;
    padding: 0 1rem;
}

.tracking-progress::before {
    content: '';
    position: absolute;
    top: 30px;
    left: 50px;
    width: 2px;
    height: calc(100% - 60px);
    background-color: #e9ecef;
}

.tracking-step {
    position: relative;
    display: flex;
    align-items: flex-start;
    padding-bottom: 2.5rem;
}

.tracking-step:last-child {
    padding-bottom: 0;
}

.step-icon {
    position: relative;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #e9ecef;
    margin-right: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1;
    transition: all 0.3s ease;
}

.step-icon i {
    color: #adb5bd;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.step-content {
    flex: 1;
    padding-top: 0.5rem;
}

.step-content h6 {
    margin-bottom: 0.25rem;
    color: #495057;
    transition: all 0.3s ease;
}

.tracking-step.completed .step-icon {
    background-color: var(--bs-primary);
}

.tracking-step.completed .step-icon i {
    color: white;
}

.tracking-step.completed .step-content h6 {
    color: var(--bs-primary);
}

.tracking-step.completed + .tracking-step::before {
    background-color: var(--bs-primary);
}

.table > :not(caption) > * > * {
    padding: 1rem;
}

.table tbody tr:hover {
    background-color: rgba(0,0,0,.02);
}
</style>
@endpush
@endsection
