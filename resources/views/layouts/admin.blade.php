<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - {{ config('app.name', 'Rock & Rolls') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Custom Styles -->
    <style>
        :root {
            --primary: #ff4b3e;
            --primary-hover: #ff6459;
            --secondary: #252836;
            --dark: #1f1d2b;
            --light: #f8f9fa;
            --white: #ffffff;
            --navbar-height: 56px;
            --sidebar-width: 250px;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light);
            min-height: 100vh;
        }
        
        /* Navbar Styles */
        .navbar {
            background-color: var(--dark);
            padding: 1rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            z-index: 1030;
        }

        .navbar-brand {
            color: white;
            font-weight: 600;
        }

        .navbar-brand:hover {
            color: rgba(255, 255, 255, 0.8);
        }

        .navbar-toggler {
            color: white;
            border: none;
            padding: 0.5rem;
            border-radius: 0.35rem;
            background: rgba(255, 255, 255, 0.1);
        }

        .navbar-toggler:focus {
            box-shadow: none;
            outline: none;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.5rem 1rem;
            border-radius: 0.35rem;
            display: flex;
            align-items: center;
        }

        .nav-link:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
        }

        .dropdown-menu {
            min-width: 200px;
            padding: 0.5rem;
            margin-top: 0.5rem;
            background: white;
            border: none;
            border-radius: 0.35rem;
        }

        .dropdown-item {
            color: var(--dark);
            padding: 0.5rem 1rem;
            display: flex;
            align-items: center;
            transition: all 0.2s;
        }

        .dropdown-item:hover {
            background: var(--primary);
            color: white;
            border-radius: 0.25rem;
        }

        @media (max-width: 991.98px) {
            .navbar-collapse {
                background: var(--dark);
                padding: 1rem;
                margin: 0.5rem -1rem -1rem;
                border-top: 1px solid rgba(255, 255, 255, 0.1);
            }

            .navbar-nav {
                padding: 0.5rem 0;
            }

            .nav-item {
                width: 100%;
            }

            .nav-link {
                padding: 0.75rem 1rem;
            }

            .dropdown-menu {
                margin-top: 0;
                background: rgba(255, 255, 255, 0.05);
                border: none;
                padding: 0;
            }

            .dropdown-item {
                color: rgba(255, 255, 255, 0.8);
                padding: 0.75rem 1rem;
            }

            .dropdown-item:hover {
                background: rgba(255, 255, 255, 0.1);
                color: white;
            }
        }
        
        /* Sidebar Styles */
        .sidebar-wrapper {
            position: fixed;
            top: var(--navbar-height);
            bottom: 0;
            left: 0;
            z-index: 1025;
            width: var(--sidebar-width);
            transition: transform 0.3s ease;
        }
        
        .sidebar {
            background-color: var(--secondary);
            height: 100%;
            padding: 1rem;
            overflow-y: auto;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.8rem 1rem;
            border-radius: 0.25rem;
            margin: 0.2rem 0;
            transition: all 0.3s ease;
            white-space: nowrap;
        }
        
        .sidebar .nav-link:hover {
            color: var(--white);
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar .nav-link.active {
            color: var(--white);
            background-color: var(--primary);
        }
        
        .sidebar .nav-link.active:hover {
            background-color: var(--primary-hover);
        }
        
        .sidebar .nav-link i {
            width: 1.5rem;
            text-align: center;
            margin-right: 0.5rem;
            font-size: 1.1rem;
        }
        
        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--navbar-height);
            padding: 2rem;
            min-height: calc(100vh - var(--navbar-height));
            transition: margin-left 0.3s ease;
        }
        
        /* Mobile Responsive */
        @media (max-width: 991.98px) {
            .sidebar-wrapper {
                transform: translateX(-100%);
            }
            
            .sidebar-wrapper.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .sidebar-backdrop {
                position: fixed;
                top: var(--navbar-height);
                right: 0;
                bottom: 0;
                left: 0;
                z-index: 1024;
                background-color: rgba(0, 0, 0, 0.5);
                display: none;
            }
            
            .sidebar-backdrop.show {
                display: block;
            }
        }
        
        /* Card Styles */
        .card {
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(31, 29, 43, 0.15);
        }
        
        /* Button Styles */
        .btn-outline-light {
            border-color: rgba(255, 255, 255, 0.2);
        }
        
        .btn-outline-light:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.3);
        }
        
        /* Responsive Table */
        .table-responsive {
            -webkit-overflow-scrolling: touch;
        }
        
        @media (max-width: 767.98px) {
            .main-content {
                padding: 1rem;
            }
            
            .card-body {
                padding: 1rem;
            }
            
            .table > :not(caption) > * > * {
                padding: 0.5rem;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <button class="navbar-toggler me-2" type="button" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-pizza-slice me-2"></i>Rock & Rolls
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-ellipsis-v"></i>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-shield me-2"></i>
                            <span>{{ Auth::guard('admin')->user()->name ?? 'Admin' }}</span>
                        </a>
                        <ul class="dropdown-menu shadow" aria-labelledby="navbarDropdown">
                            <li>
                                <form action="{{ route('admin.logout') }}" method="POST" class="px-2">
                                    @csrf
                                    <button type="submit" class="dropdown-item rounded">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="sidebar-wrapper">
        <div class="sidebar">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                       href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.analytics') ? 'active' : '' }}" 
                       href="{{ route('admin.analytics') }}">
                        <i class="fas fa-chart-line"></i>Analytics
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dishes.*') ? 'active' : '' }}" 
                       href="{{ route('admin.dishes.index') }}">
                        <i class="fas fa-utensils"></i>Menu Items
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" 
                       href="{{ route('admin.orders.index') }}">
                        <i class="fas fa-shopping-bag"></i>Orders
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="sidebar-backdrop"></div>
    
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarWrapper = document.querySelector('.sidebar-wrapper');
            const sidebarBackdrop = document.querySelector('.sidebar-backdrop');
            const sidebar = document.querySelector('.sidebar');
            
            function toggleSidebar() {
                sidebarWrapper.classList.toggle('show');
                sidebarBackdrop.classList.toggle('show');
                document.body.style.overflow = sidebarWrapper.classList.contains('show') ? 'hidden' : '';
            }
            
            sidebarToggle.addEventListener('click', toggleSidebar);
            sidebarBackdrop.addEventListener('click', toggleSidebar);
            
            // Close sidebar when clicking a nav link on mobile
            sidebar.querySelectorAll('.nav-link').forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth < 992) {
                        toggleSidebar();
                    }
                });
            });
            
            // Handle resize
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 992) {
                    sidebarWrapper.classList.remove('show');
                    sidebarBackdrop.classList.remove('show');
                    document.body.style.overflow = '';
                }
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>
