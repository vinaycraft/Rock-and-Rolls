@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Dashboard</h2>
    
    <div class="row">
        <!-- Revenue Card -->
        <div class="col-md-3 mb-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Total Revenue</h6>
                            <h3 class="mt-2 mb-0">₹15,890</h3>
                        </div>
                        <i class="fas fa-rupee-sign fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Card -->
        <div class="col-md-3 mb-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Total Orders</h6>
                            <h3 class="mt-2 mb-0">156</h3>
                        </div>
                        <i class="fas fa-shopping-bag fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menu Items Card -->
        <div class="col-md-3 mb-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Menu Items</h6>
                            <h3 class="mt-2 mb-0">45</h3>
                        </div>
                        <i class="fas fa-utensils fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customers Card -->
        <div class="col-md-3 mb-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Customers</h6>
                            <h3 class="mt-2 mb-0">289</h3>
                        </div>
                        <i class="fas fa-users fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Orders -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Orders</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Items</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#ORD001</td>
                                    <td>John Doe</td>
                                    <td>3</td>
                                    <td>₹450</td>
                                    <td><span class="badge bg-success">Delivered</span></td>
                                </tr>
                                <tr>
                                    <td>#ORD002</td>
                                    <td>Jane Smith</td>
                                    <td>2</td>
                                    <td>₹280</td>
                                    <td><span class="badge bg-warning">Processing</span></td>
                                </tr>
                                <tr>
                                    <td>#ORD003</td>
                                    <td>Mike Johnson</td>
                                    <td>4</td>
                                    <td>₹620</td>
                                    <td><span class="badge bg-primary">Confirmed</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Popular Items -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Popular Items</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Butter Chicken
                            <span class="badge bg-primary rounded-pill">124 orders</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Paneer Tikka
                            <span class="badge bg-primary rounded-pill">98 orders</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Biryani
                            <span class="badge bg-primary rounded-pill">87 orders</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Naan
                            <span class="badge bg-primary rounded-pill">76 orders</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Dal Makhani
                            <span class="badge bg-primary rounded-pill">65 orders</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
