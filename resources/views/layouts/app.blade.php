<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Rock & Rolls') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <nav class="bg-white shadow-sm">
            <div class="container mx-auto px-4">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ route('home') }}" class="text-2xl font-bold text-primary">
                                Rock & Rolls
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <a href="{{ route('menu') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-700 hover:text-primary">
                                Menu
                            </a>
                            @auth
                                <a href="{{ route('orders') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-700 hover:text-primary">
                                    My Orders
                                </a>
                                <a href="{{ route('cart') }}" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-700 hover:text-primary">
                                    <i class="fas fa-shopping-cart mr-1"></i> Cart
                                </a>
                            @endauth
                        </div>
                    </div>

                    <!-- Settings Dropdown -->
                    <div class="flex items-center">
                        @guest
                            <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-primary mr-4">Login</a>
                            <a href="{{ route('register') }}" class="text-sm text-gray-700 hover:text-primary">Register</a>
                        @else
                            <div class="relative">
                                <button class="flex items-center text-sm font-medium text-gray-700 hover:text-primary focus:outline-none transition duration-150 ease-in-out">
                                    <span>{{ Auth::user()->name }}</span>
                                    <i class="fas fa-chevron-down ml-1"></i>
                                </button>

                                <!-- Dropdown menu -->
                                <div class="absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endguest
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white shadow-sm mt-8">
            <div class="container mx-auto px-4 py-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div>
                        <h3 class="text-lg font-semibold mb-4">About Us</h3>
                        <p class="text-gray-600">Rock & Rolls serves the finest Indian cuisine with authentic flavors and fresh ingredients.</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                        <ul class="space-y-2">
                            <li><a href="{{ route('menu') }}" class="text-gray-600 hover:text-primary">Menu</a></li>
                            @auth
                                <li><a href="{{ route('orders') }}" class="text-gray-600 hover:text-primary">My Orders</a></li>
                            @endauth
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Contact Us</h3>
                        <ul class="space-y-2 text-gray-600">
                            <li><i class="fas fa-phone mr-2"></i> +91 123-456-7890</li>
                            <li><i class="fas fa-envelope mr-2"></i> info@rockandrolls.com</li>
                            <li><i class="fas fa-map-marker-alt mr-2"></i> 123 Food Street, Foodie City</li>
                        </ul>
                    </div>
                </div>
                <div class="border-t mt-8 pt-6 text-center text-gray-600">
                    <p>&copy; {{ date('Y') }} Rock & Rolls. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
