@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body p-5">
                    <h3 class="text-center mb-4" style="color: var(--primary-color)">Welcome Back to Rock & Rolls</h3>
                    <p class="text-center text-muted mb-4">Login to order your favorite dishes!</p>
                    
                    <form method="POST" action="{{ route('customer.login') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="phone_number" class="form-label">Phone Number</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                <input type="tel" class="form-control @error('phone_number') is-invalid @enderror" 
                                       id="phone_number" name="phone_number" value="{{ old('phone_number') }}" 
                                       required placeholder="Enter 10-digit phone number"
                                       pattern="[0-9]{10}">
                            </div>
                            @error('phone_number')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required placeholder="Enter your password">
                            </div>
                            @error('password')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label" for="remember">
                                    Remember me
                                </label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <button type="submit" class="btn btn-primary w-100 py-2">
                                <i class="fas fa-sign-in-alt me-2"></i>Login & Start Ordering
                            </button>
                        </div>

                        <div class="text-center">
                            <p class="mb-2">
                                <a href="{{ route('password.request') }}" class="text-muted">
                                    Forgot your password?
                                </a>
                            </p>
                            <p class="mb-0">
                                Don't have an account? 
                                <a href="{{ route('register') }}" style="color: var(--primary-color)">Register here</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
