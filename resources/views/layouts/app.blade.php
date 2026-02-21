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

            /* Sidebar dimensions */
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 72px;
            --topbar-height: 60px;
            --transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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

        /* ===== TOP BAR (always visible on desktop & mobile) ===== */
        .topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: var(--topbar-height);
            background: #fff;
            border-bottom: 1px solid #e8ecf0;
            z-index: 1002;
            display: flex;
            align-items: center;
            padding: 0 24px;
            gap: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            transition: left var(--transition);
        }

        .topbar.sidebar-collapsed {
            left: var(--sidebar-collapsed-width);
        }

        .topbar-toggle {
            width: 38px;
            height: 38px;
            border: none;
            background: #f1f3f9;
            border-radius: 10px;
            color: #4a5568;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
            flex-shrink: 0;
        }

        .topbar-toggle:hover {
            background: #e2e8f0;
        }

        .topbar-title {
            font-size: 15px;
            font-weight: 600;
            color: #2d3748;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 1000;
            transition: width var(--transition), transform var(--transition);
            scrollbar-width: none;
        }

        .sidebar::-webkit-scrollbar { display: none; }

        /* Collapsed state (desktop: icon-only) */
        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        /* ===== SIDEBAR HEADER ===== */
        .sidebar-header {
            padding: 20px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(255,255,255,0.05);
            border-bottom: 1px solid rgba(255,255,255,0.1);
            min-height: 70px;
            overflow: hidden;
            white-space: nowrap;
        }

        .sidebar-logo {
            width: 40px;
            height: 40px;
            background: var(--primary-gradient);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            flex-shrink: 0;
        }

        .sidebar-brand-text {
            overflow: hidden;
            transition: opacity var(--transition), width var(--transition);
        }

        .sidebar-brand-text h4 {
            color: white;
            font-size: 16px;
            font-weight: 700;
            line-height: 1.2;
        }

        .sidebar-brand-text small {
            color: rgba(255,255,255,0.6);
            font-size: 12px;
        }

        .sidebar-brand-text .badge {
            display: inline-block;
            margin-top: 4px;
            padding: 3px 10px;
            background: var(--primary-gradient);
            font-weight: 600;
            font-size: 10px;
            border-radius: 20px;
            color: white;
        }

        .sidebar.collapsed .sidebar-brand-text {
            opacity: 0;
            width: 0;
            pointer-events: none;
        }

        /* ===== NAV SECTIONS ===== */
        .nav-section {
            padding: 16px 10px;
        }

        .nav-section-title {
            color: rgba(255,255,255,0.4);
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            padding: 0 12px;
            margin-bottom: 8px;
            white-space: nowrap;
            overflow: hidden;
            transition: opacity var(--transition);
        }

        .sidebar.collapsed .nav-section-title {
            opacity: 0;
        }

        /* ===== NAV LINKS ===== */
        .sidebar .nav-link {
            color: rgba(255,255,255,0.75);
            padding: 11px 12px;
            margin: 3px 0;
            border-radius: 10px;
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            gap: 12px;
            white-space: nowrap;
            overflow: hidden;
            position: relative;
        }

        .sidebar .nav-link i {
            font-size: 16px;
            width: 20px;
            text-align: center;
            flex-shrink: 0;
        }

        .sidebar .nav-link span {
            transition: opacity var(--transition);
        }

        .sidebar.collapsed .nav-link span {
            opacity: 0;
            pointer-events: none;
        }

        .sidebar .nav-link:hover {
            background: var(--hover-bg);
            color: white;
            transform: none;
        }

        .sidebar .nav-link.active {
            background: var(--primary-gradient);
            color: white;
            box-shadow: 0 4px 12px rgba(102,126,234,0.4);
        }

        /* Tooltip for collapsed state */
        .sidebar.collapsed .nav-link::after {
            content: attr(data-tooltip);
            position: absolute;
            left: calc(var(--sidebar-collapsed-width) - 8px);
            background: #1a1a2e;
            color: white;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 13px;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s;
            z-index: 2000;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        }

        .sidebar.collapsed .nav-link:hover::after {
            opacity: 1;
        }

        /* Divider */
        .logout-link {
            margin-top: 10px;
            border-top: 1px solid rgba(255,255,255,0.1);
            padding-top: 10px;
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            margin-left: var(--sidebar-width);
            padding-top: calc(var(--topbar-height) + 24px);
            padding-left: 24px;
            padding-right: 24px;
            padding-bottom: 24px;
            min-height: 100vh;
            transition: margin-left var(--transition);
        }

        .main-content.sidebar-collapsed {
            margin-left: var(--sidebar-collapsed-width);
        }

        .main-content.full-width {
            margin-left: 0;
        }

        /* ===== OVERLAY (mobile) ===== */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 999;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .sidebar-overlay.active {
            opacity: 1;
        }

        /* ===== MOBILE ===== */
        @media (max-width: 768px) {
            .topbar {
                left: 0 !important;
                right: 0 !important;
                z-index: 1002;
            }

            .sidebar {
                width: var(--sidebar-width) !important;
                transform: translateX(-100%);
                z-index: 1001;
            }

            .sidebar.mobile-open {
                transform: translateX(0);
            }

            .sidebar-overlay {
                display: block;
                z-index: 1000;
            }

            .main-content {
                margin-left: 0 !important;
                padding: calc(var(--topbar-height) + 16px) 16px 16px;
            }

            .topbar-toggle {
                min-width: 44px;
                min-height: 44px;
                width: 44px;
                height: 44px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

@auth

    <!-- Overlay for mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Top Bar -->
    <div class="topbar" id="topbar">
        <button class="topbar-toggle" id="sidebarToggle" title="Toggle Sidebar">
            <i class="fas fa-bars"></i>
        </button>
        <span class="topbar-title">@yield('page-title', 'SBJ Printex')</span>
    </div>

    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">

        <!-- Header -->
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <i class="fas fa-layer-group"></i>
            </div>
            <div class="sidebar-brand-text">
                <h4>SBJ Printex</h4>
                <small>{{ auth()->user()->name }}</small>
                @if(auth()->user()->role)
                    <div>
                        <span class="badge">{{ auth()->user()->role->name }}</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Main Menu -->
        <div class="nav-section">
            <div class="nav-section-title">Main Menu</div>
            <ul class="nav flex-column">

                @if(auth()->user()->hasPermission('dashboard'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                       href="{{ route('dashboard') }}" data-tooltip="Dashboard">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                @endif

                @if(auth()->user()->hasPermission('analytics'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('analytics.*') ? 'active' : '' }}"
                       href="{{ route('analytics.index') }}" data-tooltip="Analytics">
                        <i class="fas fa-chart-line"></i>
                        <span>Analytics</span>
                    </a>
                </li>
                @endif

                @if(auth()->user()->hasPermission('kanban'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('kanban.*') ? 'active' : '' }}"
                       href="{{ route('kanban.index') }}" data-tooltip="Kanban Board">
                        <i class="fas fa-columns"></i>
                        <span>Kanban Board</span>
                    </a>
                </li>
                @endif

                @if(auth()->user()->hasPermission('completed_orders'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('completed-orders.*') ? 'active' : '' }}"
                       href="{{ route('completed-orders.index') }}" data-tooltip="Completed Orders">
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
                    'desain'         => ['Desain','fa-paint-brush','desain.index'],
                    'printing'       => ['Printing','fa-print','printing.index'],
                    'press'          => ['Press','fa-compress','press.index'],
                    'qc'             => ['Quality Control','fa-check-circle','qc.index'],
                    'pengiriman'     => ['Pengiriman','fa-truck','pengiriman.index'],
                ] as $perm => $menu)

                    @if(auth()->user()->hasPermission($perm))
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs(str_replace('.index','.*',$menu[2])) ? 'active' : '' }}"
                           href="{{ route($menu[2]) }}" data-tooltip="{{ $menu[0] }}">
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
                       href="{{ route('users.index') }}" data-tooltip="User Management">
                        <i class="fas fa-users-cog"></i>
                        <span>User Management</span>
                    </a>
                </li>
                @endif

                @if(auth()->user()->hasPermission('role_management'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}"
                       href="{{ route('roles.index') }}" data-tooltip="Role Management">
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
                       href="{{ route('logout') }}" data-tooltip="Logout"
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
<main class="main-content @guest full-width @endguest" id="mainContent">
    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
(function () {
    const sidebar      = document.getElementById('sidebar');
    const topbar       = document.getElementById('topbar');
    const mainContent  = document.getElementById('mainContent');
    const toggleBtn    = document.getElementById('sidebarToggle');
    const overlay      = document.getElementById('sidebarOverlay');

    if (!sidebar || !toggleBtn) return;

    const STORAGE_KEY   = 'sbj_sidebar_collapsed';
    const isMobile      = () => window.innerWidth <= 768;

    /* ---- Restore desktop state ---- */
    let collapsed = localStorage.getItem(STORAGE_KEY) === 'true';
    if (!isMobile() && collapsed) applyDesktopCollapse(true, false);

    function applyDesktopCollapse(state, save = true) {
        collapsed = state;
        sidebar.classList.toggle('collapsed', state);
        topbar.classList.toggle('sidebar-collapsed', state);
        mainContent.classList.toggle('sidebar-collapsed', state);
        if (save) localStorage.setItem(STORAGE_KEY, state);
    }

    function openMobile() {
        sidebar.classList.add('mobile-open');
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeMobile() {
        sidebar.classList.remove('mobile-open');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    toggleBtn.addEventListener('click', () => {
        if (isMobile()) {
            sidebar.classList.contains('mobile-open') ? closeMobile() : openMobile();
        } else {
            applyDesktopCollapse(!collapsed);
        }
    });

    overlay.addEventListener('click', closeMobile);

    /* Close mobile sidebar when a link is clicked */
    sidebar.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', () => {
            if (isMobile()) closeMobile();
        });
    });

    /* Handle resize between mobile/desktop */
    window.addEventListener('resize', () => {
        if (!isMobile()) {
            closeMobile();
            applyDesktopCollapse(collapsed, false);
        }
    });
})();
</script>

@stack('scripts')

</body>
</html>