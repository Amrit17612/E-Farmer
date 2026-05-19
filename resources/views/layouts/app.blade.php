<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'eFarmar') - Empowering Farmers</title>
    <meta name="description" content="@yield('meta_description', 'eFarmar - Modern agriculture platform for crop management, market prices, and government schemes.')">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <style>
        :root {
            --green-dark:   #2E7D32;
            --green-mid:    #388E3C;
            --green-light:  #66BB6A;
            --green-pale:   #E8F5E9;
            --green-accent: #A5D6A7;
            --white:        #FFFFFF;
            --grey-light:   #F5F5F5;
            --grey-mid:     #EEEEEE;
            --grey-border:  #E0E0E0;
            --text-dark:    #1A2E1A;
            --text-mid:     #4A4A4A;
            --text-light:   #757575;
            --sidebar-w:    260px;
            --shadow-sm:    0 2px 8px rgba(46,125,50,0.08);
            --shadow-md:    0 4px 20px rgba(46,125,50,0.12);
            --shadow-lg:    0 8px 40px rgba(46,125,50,0.15);
            --radius-sm:    8px;
            --radius-md:    12px;
            --radius-lg:    16px;
            --radius-xl:    24px;
            --transition:   all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body.dark-mode {
            --white:        #1A1A1A;
            --grey-light:   #121212;
            --grey-mid:     #2A2A2A;
            --grey-border:  #333333;
            --text-dark:    #E0E0E0;
            --text-mid:     #B0B0B0;
            --text-light:   #888888;
            --green-pale:   rgba(46,125,50,0.15);
            --shadow-sm:    0 2px 8px rgba(0,0,0,0.4);
            --shadow-md:    0 4px 20px rgba(0,0,0,0.5);
            --shadow-lg:    0 8px 40px rgba(0,0,0,0.6);
        }
        
        /* Dark Mode Specific Overrides */
        body.dark-mode .topbar { background: var(--white); box-shadow: var(--shadow-sm); }
        body.dark-mode .sidebar { border-right: 1px solid rgba(255,255,255,0.05); }
        body.dark-mode .alert { border: 1px solid rgba(255,255,255,0.1); }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--grey-light);
            color: var(--text-dark);
            display: flex;
            min-height: 100vh;
        }

        /* ── SIDEBAR ── */
        .sidebar {
            width: var(--sidebar-w);
            background: linear-gradient(180deg, var(--green-dark) 0%, #1B5E20 100%);
            min-height: 100vh;
            position: fixed;
            top: 0; left: 0;
            display: flex;
            flex-direction: column;
            z-index: 1000;
            transition: var(--transition);
        }

        .sidebar-logo {
            padding: 28px 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-logo .logo-text {
            font-size: 24px;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.5px;
        }

        .sidebar-logo .logo-text span {
            color: var(--green-light);
        }

        .sidebar-logo .logo-sub {
            font-size: 11px;
            color: rgba(255,255,255,0.5);
            margin-top: 2px;
            letter-spacing: 0.5px;
        }

        .sidebar-nav {
            padding: 20px 0;
            flex: 1;
            overflow-y: auto;
        }

        .nav-label {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 1.5px;
            color: rgba(255,255,255,0.4);
            padding: 12px 24px 6px;
            text-transform: uppercase;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 24px;
            color: rgba(255,255,255,0.75);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: var(--transition);
            border-left: 3px solid transparent;
            position: relative;
        }

        .nav-item:hover {
            color: #fff;
            background: rgba(255,255,255,0.1);
            border-left-color: var(--green-light);
        }

        .nav-item.active {
            color: #fff;
            background: rgba(255,255,255,0.15);
            border-left-color: var(--green-light);
            font-weight: 600;
        }

        .nav-item i { font-size: 18px; width: 22px; }

        .sidebar-footer {
            padding: 16px 24px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .user-mini {
            display: flex;
            align-items: center;
            gap: 10px;
            color: rgba(255,255,255,0.85);
            font-size: 14px;
        }

        .user-mini img, .user-mini .avatar-placeholder {
            width: 36px; height: 36px;
            border-radius: 50%;
            object-fit: cover;
            background: var(--green-light);
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 14px; color: var(--green-dark);
        }

        /* ── MAIN CONTENT ── */
        .main-content {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        /* ── TOPBAR ── */
        .topbar {
            background: var(--white);
            padding: 16px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: var(--shadow-sm);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .topbar-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-dark);
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .topbar-actions .btn-icon {
            width: 38px; height: 38px;
            border-radius: 50%;
            background: var(--grey-light);
            border: none;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            color: var(--text-mid);
            font-size: 18px;
        }

        .topbar-actions .btn-icon:hover {
            background: var(--green-pale);
            color: var(--green-dark);
        }

        .topbar-actions .btn-icon img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        /* ── PAGE CONTENT ── */
        .page-content {
            padding: 32px;
            flex: 1;
        }

        /* ── CARDS ── */
        .card {
            background: var(--white);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--grey-border);
        }

        .card-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--grey-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-dark);
        }

        .card-body {
            padding: 24px;
        }

        /* ── STAT CARDS ── */
        .stat-card {
            background: var(--white);
            border-radius: var(--radius-md);
            padding: 24px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--grey-border);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 4px; height: 100%;
            background: var(--green-dark);
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }

        .stat-card .stat-icon {
            width: 52px; height: 52px;
            border-radius: var(--radius-sm);
            background: var(--green-pale);
            display: flex; align-items: center; justify-content: center;
            font-size: 24px;
            color: var(--green-dark);
            margin-bottom: 16px;
        }

        .stat-card .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-dark);
            line-height: 1;
        }

        .stat-card .stat-label {
            font-size: 13px;
            color: var(--text-light);
            margin-top: 6px;
        }

        .stat-card .stat-change {
            font-size: 12px;
            font-weight: 600;
            margin-top: 8px;
        }

        .stat-change.up   { color: var(--green-dark); }
        .stat-change.down { color: #c62828; }

        /* ── BUTTONS ── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: var(--radius-sm);
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: var(--transition);
            white-space: nowrap;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--green-dark), var(--green-mid));
            color: #fff;
            box-shadow: 0 4px 12px rgba(46,125,50,0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--green-mid), var(--green-dark));
            box-shadow: 0 6px 20px rgba(46,125,50,0.4);
            transform: translateY(-1px);
            color: #fff;
        }

        .btn-outline {
            background: transparent;
            color: var(--green-dark);
            border: 2px solid var(--green-dark);
        }

        .btn-outline:hover {
            background: var(--green-dark);
            color: #fff;
        }

        .btn-danger {
            background: #ef5350;
            color: #fff;
        }

        .btn-danger:hover {
            background: #c62828;
            color: #fff;
        }

        .btn-sm {
            padding: 6px 14px;
            font-size: 13px;
        }

        /* ── TABLES ── */
        .table-wrapper {
            overflow-x: auto;
            border-radius: var(--radius-md);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead th {
            background: var(--grey-light);
            padding: 14px 16px;
            text-align: left;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-light);
            border-bottom: 2px solid var(--grey-border);
        }

        tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--grey-border);
            font-size: 14px;
            color: var(--text-mid);
            vertical-align: middle;
        }

        tbody tr:last-child td { border-bottom: none; }

        tbody tr:hover { background: var(--grey-light); }

        /* ── BADGES ── */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        .badge-success  { background: #E8F5E9; color: #2E7D32; }
        .badge-danger   { background: #FFEBEE; color: #c62828; }
        .badge-warning  { background: #FFF8E1; color: #F57F17; }
        .badge-info     { background: #E3F2FD; color: #1565C0; }
        .badge-secondary{ background: var(--grey-mid); color: var(--text-light); }
        .badge-primary  { background: #E8EAF6; color: #283593; }

        /* ── FORMS ── */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-mid);
            margin-bottom: 6px;
        }

        .form-control {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid var(--grey-border);
            border-radius: var(--radius-sm);
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            color: var(--text-dark);
            background: var(--white);
            transition: var(--transition);
            outline: none;
        }

        .form-control:focus {
            border-color: var(--green-dark);
            box-shadow: 0 0 0 3px rgba(46,125,50,0.1);
        }

        select.form-control { cursor: pointer; }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }

        /* ── ALERTS ── */
        .alert {
            padding: 14px 18px;
            border-radius: var(--radius-sm);
            font-size: 14px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success { background: #E8F5E9; color: #2E7D32; border-left: 4px solid #2E7D32; }
        .alert-danger  { background: #FFEBEE; color: #c62828; border-left: 4px solid #c62828; }
        .alert-warning { background: #FFF8E1; color: #F57F17; border-left: 4px solid #F57F17; }
        .alert-info    { background: #E3F2FD; color: #1565C0; border-left: 4px solid #1565C0; }

        /* ── PAGINATION ── */
        .pagination {
            display: flex;
            gap: 6px;
            justify-content: center;
            margin-top: 24px;
        }

        .page-link {
            padding: 8px 14px;
            border-radius: var(--radius-sm);
            border: 1.5px solid var(--grey-border);
            color: var(--text-mid);
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: var(--transition);
        }

        .page-link:hover, .page-link.active {
            background: var(--green-dark);
            border-color: var(--green-dark);
            color: #fff;
        }

        /* ── GRID UTILS ── */
        .grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; }
        .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
        .grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; }

        /* ── SEARCH BAR ── */
        .search-bar {
            display: flex;
            align-items: center;
            gap: 12px;
            background: var(--white);
            border: 1.5px solid var(--grey-border);
            border-radius: var(--radius-sm);
            padding: 10px 16px;
            transition: var(--transition);
        }

        .search-bar:focus-within {
            border-color: var(--green-dark);
            box-shadow: 0 0 0 3px rgba(46,125,50,0.1);
        }

        .search-bar input {
            border: none;
            outline: none;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            flex: 1;
            background: transparent;
        }

        .search-bar i { color: var(--text-light); }

        /* ── MOBILE ── */
        .hamburger {
            display: none;
            background: none;
            border: none;
            font-size: 22px;
            color: var(--text-dark);
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
            .grid-2, .grid-3, .grid-4 {
                grid-template-columns: 1fr;
            }
            .hamburger { display: block; }
            .page-content { padding: 16px; }
            .topbar { padding: 14px 16px; }
        }

        /* ── OVERLAY ── */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }

        .sidebar-overlay.show { display: block; }
    </style>

    @stack('styles')
</head>
<body>
    {{-- Sidebar --}}
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    <aside class="sidebar" id="sidebar">
        <div class="sidebar-logo" style="text-align: center; padding: 20px;">
            <img src="/images/logo.png" alt="Logo" style="height: 100px; width: auto; mix-blend-mode: multiply;">
            <div class="logo-sub" style="margin-top: -10px;">Agriculture Platform</div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-label">Main</div>
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>

            @if(Auth::user()->isFarmer())
            <div class="nav-label">Farm</div>
            <a href="{{ route('crops.index') }}" class="nav-item {{ request()->routeIs('crops.*') ? 'active' : '' }}">
                <i class="bi bi-flower2"></i> My Crops
            </a>
            <a href="{{ route('orders.index') }}" class="nav-item {{ request()->routeIs('orders.*') && !request()->routeIs('orders.sell') ? 'active' : '' }}">
                <i class="bi bi-bag-check-fill"></i> Sales Orders
            </a>
            @elseif(Auth::user()->isBuyer())
            <div class="nav-label">Buyer</div>
            <a href="{{ route('orders.sell') }}" class="nav-item {{ request()->routeIs('orders.sell') ? 'active' : '' }}">
                <i class="bi bi-shop"></i> Browse Crops
            </a>
            <a href="{{ route('orders.index') }}" class="nav-item {{ request()->routeIs('orders.*') && !request()->routeIs('orders.sell') ? 'active' : '' }}">
                <i class="bi bi-bag-check-fill"></i> My Purchases
            </a>
            @else
            <div class="nav-label">Admin</div>
            <a href="{{ route('crops.index') }}" class="nav-item {{ request()->routeIs('crops.*') ? 'active' : '' }}">
                <i class="bi bi-flower2"></i> Crop Listings
            </a>
            <a href="{{ route('orders.index') }}" class="nav-item {{ request()->routeIs('orders.*') && !request()->routeIs('orders.sell') ? 'active' : '' }}">
                <i class="bi bi-bag-check-fill"></i> All Orders
            </a>
            @endif

            <div class="nav-label">Market</div>
            <a href="{{ route('market.index') }}" class="nav-item {{ request()->routeIs('market.*') ? 'active' : '' }}">
                <i class="bi bi-graph-up-arrow"></i> Market Prices
            </a>
            @if(!Auth::user()->isAdmin())
            <a href="{{ route('schemes.index') }}" class="nav-item {{ request()->routeIs('schemes.*') ? 'active' : '' }}">
                <i class="bi bi-bank2"></i> Govt Schemes
            </a>
            <a href="{{ route('weather.index') }}" class="nav-item {{ request()->routeIs('weather.*') ? 'active' : '' }}">
                <i class="bi bi-cloud-sun-fill"></i> Weather
            </a>
            @else
            <a href="{{ route('admin.users') }}" class="nav-item {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i> Manage Users
            </a>
            <a href="{{ route('schemes.index') }}" class="nav-item {{ request()->routeIs('schemes.*') ? 'active' : '' }}">
                <i class="bi bi-bank2"></i> Schemes
            </a>
            @endif

            <div class="nav-label">Account</div>
            <a href="{{ route('profile.show') }}" class="nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <i class="bi bi-person-circle"></i> Profile
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-item" style="width:100%;background:none;border:none;cursor:pointer;text-align:left;">
                    <i class="bi bi-box-arrow-left"></i> Logout
                </button>
            </form>
        </nav>

        <div class="sidebar-footer">
            <div class="user-mini">
                @if(Auth::user()->avatar && !str_starts_with(Auth::user()->avatar, 'http'))
                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="avatar">
                @else
                    <div class="avatar-placeholder">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                @endif
                <div>
                    <div style="font-weight:600;font-size:13px;">{{ Str::limit(Auth::user()->name, 18) }}</div>
                    <div style="font-size:11px;opacity:0.5;">{{ ucfirst(Auth::user()->role) }}{{ Auth::user()->location ? ' · ' . Auth::user()->location : '' }}</div>
                </div>
            </div>
        </div>
    </aside>

    {{-- Main Content --}}
    <div class="main-content">
        <header class="topbar">
            <div style="display:flex;align-items:center;gap:16px;">
                <button class="hamburger" onclick="toggleSidebar()"><i class="bi bi-list"></i></button>
                <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
            </div>
            <div class="topbar-actions">
                <button class="btn-icon" id="theme-toggle" title="Toggle Dark Mode">
                    <i class="bi bi-moon"></i>
                </button>
                <a href="{{ route('weather.index') }}" class="btn-icon" title="Weather">
                    <i class="bi bi-cloud-sun"></i>
                </a>
                <a href="{{ route('profile.show') }}" class="btn-icon" title="Profile" style="overflow:hidden; padding:0;">
                    @if(Auth::user()->avatar && !str_starts_with(Auth::user()->avatar, 'http'))
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="profile">
                    @else
                        <i class="bi bi-person"></i>
                    @endif
                </a>
            </div>
        </header>

        <main class="page-content">
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="bi bi-x-circle-fill"></i> {{ session('error') }}
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <ul style="margin:0;padding-left:16px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('sidebarOverlay').classList.toggle('show');
        }
        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('open');
            document.getElementById('sidebarOverlay').classList.remove('show');
        }

        // Auto-hide alerts
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(el => {
                el.style.transition = 'opacity 0.5s';
                el.style.opacity = '0';
                setTimeout(() => el.remove(), 500);
            });
        }, 4000);

        // Dark Mode Logic
        const themeToggle = document.getElementById('theme-toggle');
        const dBody = document.body;
        
        if (localStorage.getItem('theme') === 'dark') {
            dBody.classList.add('dark-mode');
            themeToggle.innerHTML = '<i class="bi bi-sun"></i>';
        }

        themeToggle.addEventListener('click', () => {
            dBody.classList.toggle('dark-mode');
            if (dBody.classList.contains('dark-mode')) {
                localStorage.setItem('theme', 'dark');
                themeToggle.innerHTML = '<i class="bi bi-sun"></i>';
            } else {
                localStorage.setItem('theme', 'light');
                themeToggle.innerHTML = '<i class="bi bi-moon"></i>';
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
