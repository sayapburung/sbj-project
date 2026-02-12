<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SBJ Printex')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" href="{{ asset('images/page1.png') }}" type="image/png">

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

        /* Mobile Header */
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
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .sidebar-header {
            padding: 30px 20px;
            text-align: center;
            background: rgba(255, 255, 255, 0.05);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header h4 {
            color: white;
            font-size: 18px;
            font-weight: 700;
        }

        .sidebar-header small {
            color: rgba(255, 255, 255, 0.7);
        }

        .sidebar-header .badge {
            margin-top: 8px;
            padding: 6px 14px;
            background: var(--primary-gradient);
            font-weight: 600;
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
        }

        .sidebar .nav-link:hover {
            background: var(--hover-bg);
            transform: translateX(5px);
        }

        .sidebar .nav-link.active {
            background: var(--primary-gradient);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        /* Logout */
        .logout-link {
            margin-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 20px;
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            min-height: 100vh;
            padding: 30px;
        }

        .main-content.full-width {
            margin-left: 0;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .mobile-header {
                display: flex;
            }

            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding: 90px 15px 20px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

@auth

    <!-- Mobile Header -->
    <div class="mobile-header">
        <div class="brand">
            <i class="fas fa-layer-group me-2"></i>SBJ Printex
        </div>
        <button class="menu-toggle" id="menuToggle">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">

        <!-- Sidebar Header -->
        <div class="sidebar-header">
            <h4>SBJ Printex</h4>
            <small>{{ auth()->user()->name }}</small>

            @if(auth()->user()->role)
                <div>
                    <span class="badge">{{ auth()->user()->role->name }}</span>
                </div>
            @endif
        </div>

        <!-- Main Menu -->
        <div class="nav-section">
            <div class="nav-section-title">Main Menu</div>
            <ul class="nav flex-column">

                {{-- Dashboard --}}
                @if(auth()->user()->hasPermission('dashboard'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                       href="{{ route('dashboard') }}">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                @endif

                {{-- Analytics --}}
                @if(auth()->user()->hasPermission('analytics'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('analytics.*') ? 'active' : '' }}"
                       href="{{ route('analytics.index') }}">
                        <i class="fas fa-chart-line"></i>
                        <span>Analytics</span>
                    </a>
                </li>
                @endif

                {{-- Kanban --}}
                @if(auth()->user()->hasPermission('kanban'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('kanban.*') ? 'active' : '' }}"
                       href="{{ route('kanban.index') }}">
                        <i class="fas fa-columns"></i>
                        <span>Kanban Board</span>
                    </a>
                </li>
                @endif

                {{-- Completed Orders --}}
                @if(auth()->user()->hasPermission('completed_orders'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('completed-orders.*') ? 'active' : '' }}"
                       href="{{ route('completed-orders.index') }}">
                        <i class="fas fa-check-double"></i>
                        <span>Completed Orders</span>
                    </a>
                </li>
                @endif

            </ul>
        </div>

        <!-- Workflow Stages -->
        @if(
            auth()->user()->hasPermission('purchase_order') ||
            auth()->user()->hasPermission('desain') ||
            auth()->user()->hasPermission('printing') ||
            auth()->user()->hasPermission('press') ||
            auth()->user()->hasPermission('qc') ||
            auth()->user()->hasPermission('pengiriman')
        )
        <div class="nav-section">
            <div class="nav-section-title">Workflow Stages</div>
            <ul class="nav flex-column">

                @foreach([
                    'purchase_order' => ['Purchase Order','fa-file-invoice','purchase-orders.index'],
                    'desain' => ['Desain','fa-paint-brush','desain.index'],
                    'printing' => ['Printing','fa-print','printing.index'],
                    'press' => ['Press','fa-compress','press.index'],
                    'qc' => ['Quality Control','fa-check-circle','qc.index'],
                    'pengiriman' => ['Pengiriman','fa-truck','pengiriman.index'],
                ] as $perm => $menu)

                    @if(auth()->user()->hasPermission($perm))
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs(str_replace('.index','.*',$menu[2])) ? 'active' : '' }}"
                           href="{{ route($menu[2]) }}">
                            <i class="fas {{ $menu[1] }}"></i>
                            <span>{{ $menu[0] }}</span>
                        </a>
                    </li>
                    @endif

                @endforeach

            </ul>
        </div>
        @endif

        <!-- Administration -->
        @if(auth()->user()->hasPermission('user_management') || auth()->user()->hasPermission('role_management'))
        <div class="nav-section">
            <div class="nav-section-title">Administration</div>
            <ul class="nav flex-column">

                @if(auth()->user()->hasPermission('user_management'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}"
                       href="{{ route('users.index') }}">
                        <i class="fas fa-users-cog"></i>
                        <span>User Management</span>
                    </a>
                </li>
                @endif

                @if(auth()->user()->hasPermission('role_management'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}"
                       href="{{ route('roles.index') }}">
                        <i class="fas fa-user-shield"></i>
                        <span>Role Management</span>
                    </a>
                </li>
                @endif

            </ul>
        </div>
        @endif

        <!-- Logout -->
        <div class="nav-section logout-link">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link text-danger"
                       href="{{ route('logout') }}"
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
    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const menuToggle = document.getElementById("menuToggle");
    const sidebar = document.getElementById("sidebar");

    if(menuToggle){
        menuToggle.addEventListener("click", function(){
            sidebar.classList.toggle("active");
        });
    }
</script>

@stack('scripts')

</body>
</html>
