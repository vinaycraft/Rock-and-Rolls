@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Menu Management</h1>
        <a href="{{ route('admin.dishes.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm me-2"></i>Add New Dish
        </a>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Content -->
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <div class="col">
                    <h6 class="m-0 font-weight-bold text-primary">All Menu Items</h6>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 45%">Dish Details</th>
                        <th style="width: 20%">Price</th>
                        <th style="width: 20%">Status</th>
                        <th style="width: 15%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dishes as $dish)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center py-2">
                                    @if($dish->image_path)
                                        <div class="flex-shrink-0">
                                            <img src="{{ Storage::url($dish->image_path) }}" 
                                                 alt="{{ $dish->name }}" 
                                                 class="rounded-circle border" 
                                                 style="width: 50px; height: 50px; object-fit: cover;">
                                        </div>
                                    @else
                                        <div class="flex-shrink-0 rounded-circle border bg-light d-flex align-items-center justify-content-center" 
                                             style="width: 50px; height: 50px;">
                                            <i class="fas fa-utensils text-secondary"></i>
                                        </div>
                                    @endif
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="fw-bold mb-1">{{ $dish->name }}</h6>
                                        <div class="d-flex align-items-center flex-wrap gap-2">
                                            <span class="badge {{ $dish->category == 'veg' ? 'bg-success' : 'bg-danger' }}">
                                                <i class="fas {{ $dish->category == 'veg' ? 'fa-leaf' : 'fa-drumstick-bite' }} me-1"></i>
                                                {{ ucfirst($dish->category) }}
                                            </span>
                                            @if($dish->description)
                                                <small class="text-muted">{{ Str::limit($dish->description, 60) }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <div class="fw-bold">
                                        <i class="fas fa-rupee-sign me-1 opacity-75"></i>{{ number_format($dish->base_price, 2) }}
                                    </div>
                                    @if($dish->has_cheese_variant)
                                        <div class="small text-muted mt-1">
                                            <i class="fas fa-cheese me-1"></i>
                                            ₹{{ number_format($dish->price_with_cheese, 2) }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="form-check form-switch me-2">
                                        <input class="form-check-input" type="checkbox" 
                                               {{ $dish->is_available ? 'checked' : '' }} disabled>
                                    </div>
                                    <span class="badge bg-{{ $dish->is_available ? 'success' : 'danger' }} bg-opacity-75">
                                        {{ $dish->is_available ? 'Available' : 'Not Available' }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.dishes.edit', $dish) }}" 
                                       class="btn btn-sm btn-outline-primary"
                                       title="Edit Dish">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.dishes.destroy', $dish) }}" 
                                          method="POST" 
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-danger"
                                                title="Delete Dish"
                                                onclick="return confirm('Are you sure you want to delete {{ $dish->name }}?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-utensils fa-3x mb-3 opacity-50"></i>
                                    <p class="mb-0 h6">No dishes found</p>
                                    <p class="small text-muted">Start by adding your first dish to the menu</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-white py-3">
            <div class="row align-items-center">
                <div class="col-md-6 small text-muted">
                    Showing {{ $dishes->firstItem() ?? 0 }} to {{ $dishes->lastItem() ?? 0 }} of {{ $dishes->total() ?? 0 }} dishes
                </div>
                <div class="col-md-6">
                    {{ $dishes->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Card Styles */
.card {
    border: none;
    margin-bottom: 1.5rem;
}

.card-header {
    background-color: #fff;
    border-bottom: 1px solid rgba(0,0,0,.05);
}

/* Table Styles */
.table > :not(:first-child) {
    border-top: none;
}

.table > tbody > tr:hover {
    background-color: rgba(0,0,0,.02);
    transition: background-color 0.2s ease;
}

.table th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    color: #555;
}

.table td {
    vertical-align: middle;
}

/* Button Styles */
.btn-outline-primary, .btn-outline-danger {
    border-width: 1px;
    padding: 0.25rem 0.5rem;
    transition: all 0.2s ease;
}

.btn-outline-primary:hover, .btn-outline-danger:hover {
    color: white;
    transform: translateY(-1px);
}

/* Form Switch Styles */
.form-check-input:checked {
    background-color: var(--bs-success);
    border-color: var(--bs-success);
}

/* Badge Styles */
.badge {
    padding: 0.5em 0.75em;
    font-weight: 500;
}

/* Pagination Styles */
.pagination {
    margin-bottom: 0;
    justify-content: center;
}

@media (min-width: 768px) {
    .pagination {
        justify-content: flex-end;
    }
}

.page-link {
    padding: 0.375rem 0.75rem;
    color: var(--bs-primary);
    background-color: #fff;
    border: 1px solid #dee2e6;
    font-size: 0.875rem;
}

.page-link:hover {
    color: var(--bs-primary);
    background-color: #e9ecef;
    border-color: #dee2e6;
}

.page-item.active .page-link {
    background-color: var(--bs-primary);
    border-color: var(--bs-primary);
}

.page-item.disabled .page-link {
    color: #6c757d;
    background-color: #fff;
    border-color: #dee2e6;
}

/* Alert Styles */
.alert {
    border: none;
    border-radius: 0.5rem;
}

.alert-success {
    background-color: #d1e7dd;
    color: #0f5132;
}

/* Responsive Improvements */
@media (max-width: 576px) {
    .table td {
        padding: 1rem 0.5rem;
    }
    
    .badge {
        font-size: 0.75rem;
    }
    
    .btn-sm {
        padding: 0.2rem 0.4rem;
        font-size: 0.75rem;
    }
}
</style>
@endsection
