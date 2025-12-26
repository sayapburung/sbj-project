<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Workflow Management')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --sidebar-bg: linear-gradient(180deg, #1a1a2e 0%, #16213e 100%);
            --text-light: #ecf0f1;
            --hover-bg: rgba(102, 126, 234, 0.1);
            --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f8f9fc;
            overflow-x: hidden;
        }

        /* Mobile Menu Toggle */
        .mobile-header {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 70px;
            background: var(--sidebar-bg);
            z-index: 1001;
            padding: 0 20px;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        .mobile-header .brand {
            color: white;
            font-size: 20px;
            font-weight: 700;
        }

        .menu-toggle {
            width: 45px;
            height: 45px;
            background: rgba(255, 255, 255, 0.1);
            border: none;
            border-radius: 12px;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .menu-toggle:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .menu-toggle i {
            font-size: 20px;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 280px;
            height: 100vh;
            background: var(--sidebar-bg);
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 1000;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
        }

        .sidebar-header {
            padding: 30px 20px;
            text-align: center;
            background: rgba(255, 255, 255, 0.05);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header .logo {
            width: 60px;
            height: 60px;
            background: var(--primary-gradient);
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }

        .sidebar-header .logo i {
            font-size: 28px;
            color: white;
        }

        .sidebar-header h4 {
            color: white;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .sidebar-header small {
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
        }

        .sidebar-header .badge {
            margin-top: 8px;
            padding: 6px 14px;
            background: var(--primary-gradient);
            border: none;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        /* Navigation */
        .nav-section {
            padding: 20px 15px;
        }

        .nav-section-title {
            color: rgba(255, 255, 255, 0.5);
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 0 15px;
            margin-bottom: 10px;
        }

        .sidebar .nav-link {
            color: var(--text-light);
            padding: 14px 20px;
            margin: 4px 0;
            border-radius: 12px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 14px;
            font-size: 15px;
            font-weight: 500;
            position: relative;
            overflow: hidden;
        }

        .sidebar .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 4px;
            height: 100%;
            background: var(--primary-gradient);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .sidebar .nav-link i {
            width: 20px;
            font-size: 18px;
            text-align: center;
        }

        .sidebar .nav-link:hover {
            background: var(--hover-bg);
            color: #fff;
            transform: translateX(5px);
        }

        .sidebar .nav-link.active {
            background: var(--primary-gradient);
            color: #fff;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .sidebar .nav-link.active::before {
            transform: scaleY(1);
        }

        .logout-link {
            margin-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 20px;
        }

        .logout-link .nav-link {
            color: #ff6b6b !important;
            background: rgba(255, 107, 107, 0.1);
        }

        .logout-link .nav-link:hover {
            background: rgba(255, 107, 107, 0.2);
            transform: translateX(5px);
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            min-height: 100vh;
            padding: 30px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .main-content.full-width {
            margin-left: 0;
        }

        /* Page Header */
        .page-header {
            background: white;
            border-radius: 16px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: var(--card-shadow);
        }

        /* Alert Styling */
        .alert {
            border: none;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
        }

        .alert-danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
        }

        /* Cards */
        .stage-card {
            border-left: 4px solid;
            border-image: var(--primary-gradient) 1;
            transition: all 0.3s ease;
            border-radius: 12px;
            background: white;
            box-shadow: var(--card-shadow);
        }

        .stage-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
        }

        .kanban-column {
            background: linear-gradient(135deg, #f8f9fc 0%, #f1f3f8 100%);
            border-radius: 16px;
            padding: 20px;
            min-height: 400px;
            box-shadow: var(--card-shadow);
        }

        .kanban-card {
            background: white;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            border-left: 4px solid;
            border-image: var(--primary-gradient) 1;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .kanban-card:hover {
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            transform: translateY(-3px);
        }

        .badge-custom {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            background: var(--primary-gradient);
            border: none;
        }

        /* Overlay for mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-overlay.active {
            display: block;
            opacity: 1;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .mobile-header {
                display: flex;
            }

            .sidebar {
                transform: translateX(-100%);
                top: 0;
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding: 90px 15px 20px;
            }

            .page-header {
                padding: 20px;
            }

            .nav-section {
                padding: 15px 10px;
            }

            .sidebar-header {
                padding: 90px 20px 25px;
            }
        }

        @media (max-width: 480px) {
            .sidebar {
                width: 85vw;
                max-width: 300px;
            }

            .main-content {
                padding: 80px 10px 15px;
            }
        }

        /* Animations */
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .main-content > * {
            animation: slideInRight 0.6s ease;
        }

        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Mobile Header -->
    @auth
    <div class="mobile-header">
        <div class="brand">
            <i class="fas fa-layer-group me-2"></i>Workflow
        </div>
        <button class="menu-toggle" id="menuToggle">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <i class="fas fa-layer-group"></i>
            </div>
            <h4>Workflow System</h4>
            <small>{{ auth()->user()->name ?? 'User' }}</small>
            @if(auth()->user()->role)
                <div>
                    <span class="badge">{{ auth()->user()->role->name }}</span>
                </div>
            @endif
        </div>

        <div class="nav-section">
            <div class="nav-section-title">Main Menu</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('analytics.*') ? 'active' : '' }}" 
                    href="{{ route('analytics.index') }}">
                        <i class="fas fa-chart-line"></i>
                        <span>Analytics</span>
                    </a>
                </li>
                @if(auth()->user()->hasPermission('kanban'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('kanban.*') ? 'active' : '' }}" href="{{ route('kanban.index') }}">
                        <i class="fas fa-columns"></i>
                        <span>Kanban Board</span>
                    </a>
                </li>
                @endif

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('completed-orders.*') ? 'active' : '' }}" href="{{ route('completed-orders.index') }}">
                        <i class="fas fa-check-double"></i>
                        <span>Completed Orders</span>
                    </a>
                </li>
            </ul>
        </div>

        @if(auth()->user()->hasPermission('purchase_order') || auth()->user()->hasPermission('desain') || auth()->user()->hasPermission('printing') || auth()->user()->hasPermission('press') || auth()->user()->hasPermission('qc') || auth()->user()->hasPermission('pengiriman'))
        <div class="nav-section">
            <div class="nav-section-title">Workflow Stages</div>
            <ul class="nav flex-column">
                @if(auth()->user()->hasPermission('purchase_order'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('purchase-orders.*') ? 'active' : '' }}" href="{{ route('purchase-orders.index') }}">
                        <i class="fas fa-file-invoice"></i>
                        <span>Purchase Order</span>
                    </a>
                </li>
                @endif

                @if(auth()->user()->hasPermission('desain'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('desain.*') ? 'active' : '' }}" href="{{ route('desain.index') }}">
                        <i class="fas fa-paint-brush"></i>
                        <span>Desain</span>
                    </a>
                </li>
                @endif

                @if(auth()->user()->hasPermission('printing'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('printing.*') ? 'active' : '' }}" href="{{ route('printing.index') }}">
                        <i class="fas fa-print"></i>
                        <span>Printing</span>
                    </a>
                </li>
                @endif

                @if(auth()->user()->hasPermission('press'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('press.*') ? 'active' : '' }}" href="{{ route('press.index') }}">
                        <i class="fas fa-compress"></i>
                        <span>Press</span>
                    </a>
                </li>
                @endif

                @if(auth()->user()->hasPermission('qc'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('qc.*') ? 'active' : '' }}" href="{{ route('qc.index') }}">
                        <i class="fas fa-check-circle"></i>
                        <span>Quality Control</span>
                    </a>
                </li>
                @endif

                @if(auth()->user()->hasPermission('pengiriman'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('pengiriman.*') ? 'active' : '' }}" href="{{ route('pengiriman.index') }}">
                        <i class="fas fa-truck"></i>
                        <span>Pengiriman</span>
                    </a>
                </li>
                @endif
            </ul>
        </div>
        @endif

        @if(auth()->user()->hasPermission('user_management'))
        <div class="nav-section">
            <div class="nav-section-title">Administration</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                        <i class="fas fa-users-cog"></i>
                        <span>User Management</span>
                    </a>
                </li>
            </ul>
        </div>
        @endif

        <div class="nav-section logout-link">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </nav>
    @endauth

    <!-- Main Content -->
    <main class="main-content @guest full-width @endguest">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mobile Menu Toggle
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        if (menuToggle) {
            menuToggle.addEventListener('click', function() {
                sidebar.classList.toggle('active');
                sidebarOverlay.classList.toggle('active');
                
                // Toggle icon
                const icon = this.querySelector('i');
                if (sidebar.classList.contains('active')) {
                    icon.classList.remove('fa-bars');
                    icon.classList.add('fa-times');
                } else {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            });

            sidebarOverlay.addEventListener('click', function() {
                sidebar.classList.remove('active');
                this.classList.remove('active');
                const icon = menuToggle.querySelector('i');
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            });
        }

        // Close sidebar when clicking on a link (mobile)
        if (window.innerWidth <= 768) {
            const sidebarLinks = document.querySelectorAll('.sidebar .nav-link');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function() {
                    sidebar.classList.remove('active');
                    sidebarOverlay.classList.remove('active');
                    const icon = menuToggle.querySelector('i');
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                });
            });
        }
    </script>
    @stack('scripts')
</body>
</html>