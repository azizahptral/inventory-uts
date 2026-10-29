<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#3d6f46">

    <title>@yield('title', 'Diesel Parts - Inventory Management')</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            /* Matcha Theme Color Palette */
            --matcha-bg: #f3f7f4;
            --matcha-surface: #ffffff;
            --matcha-sidebar: #15291a;
            --matcha-sidebar-card: #1c3623;
            --matcha-sidebar-hover: #23432b;
            --matcha-sidebar-active: #35623f;
            --matcha-sidebar-text: #d9e8dc;
            --matcha-sidebar-muted: #8cae93;

            --matcha-primary: #3d6f46;
            --matcha-primary-hover: #315a39;
            --matcha-primary-dark: #224328;
            --matcha-primary-light: #eaf3ed;
            --matcha-accent: #659a43;
            --matcha-accent-hover: #538135;

            --text-main: #18281d;
            --text-secondary: #4a5e50;
            --text-muted: #738879;

            --border-color: #dbe6dd;
            --border-light: #edf3ee;

            --success-bg: #e6f6eb;
            --success-text: #1d6a33;
            --warning-bg: #fef7e0;
            --warning-text: #925f05;
            --danger-bg: #fde8e8;
            --danger-text: #b81c1c;
            --info-bg: #e7f2fa;
            --info-text: #175482;

            --shadow-xs: 0 1px 2px rgba(21, 41, 26, 0.04);
            --shadow-sm: 0 2px 6px rgba(21, 41, 26, 0.05);
            --shadow-md: 0 4px 16px rgba(21, 41, 26, 0.07);
            --shadow-lg: 0 12px 32px rgba(21, 41, 26, 0.09);

            --radius-sm: 6px;
            --radius-md: 10px;
            --radius-lg: 14px;
            --radius-xl: 18px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: var(--matcha-bg);
            color: var(--text-main);
            min-height: 100vh;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }

        /* SIDEBAR */
        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            width: 260px;
            padding: 24px 18px;
            background: linear-gradient(180deg, var(--matcha-sidebar) 0%, #102014 100%);
            color: white;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            z-index: 100;
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.12);
        }

        .brand-container {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 6px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            margin-bottom: 16px;
        }

        .brand-logo-icon {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, #4fa15c, #2e5936);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 20px;
            box-shadow: 0 4px 12px rgba(79, 161, 92, 0.35);
            flex-shrink: 0;
        }

        .brand-text-wrap {
            overflow: hidden;
        }

        .brand {
            font-size: 19px;
            font-weight: 800;
            letter-spacing: -0.3px;
            color: #ffffff;
            line-height: 1.2;
        }

        .brand-subtitle {
            color: var(--matcha-sidebar-muted);
            font-size: 10.5px;
            font-weight: 600;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            margin-top: 3px;
        }

        /* User Profile in Sidebar */
        .sidebar-user {
            background: var(--matcha-sidebar-card);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: var(--radius-md);
            padding: 12px 14px;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 11px;
        }

        .sidebar-user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #3e6d47;
            color: white;
            font-weight: 700;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border: 2px solid rgba(255, 255, 255, 0.15);
        }

        .sidebar-user-info {
            flex: 1;
            min-width: 0;
        }

        .sidebar-user-name {
            font-size: 13px;
            font-weight: 600;
            color: #ffffff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-user-role {
            font-size: 10.5px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 7px;
            border-radius: 20px;
            margin-top: 3px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .role-badge-admin {
            background: rgba(101, 154, 67, 0.25);
            color: #a7df85;
            border: 1px solid rgba(167, 223, 133, 0.3);
        }

        .role-badge-staff {
            background: rgba(255, 255, 255, 0.1);
            color: #cbd5e1;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .nav-label {
            color: var(--matcha-sidebar-muted);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.2px;
            margin: 16px 10px 8px;
            text-transform: uppercase;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 14px;
            margin: 4px 0;
            color: var(--matcha-sidebar-text);
            text-decoration: none;
            border-radius: var(--radius-md);
            font-size: 13.5px;
            font-weight: 500;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .nav-link:hover {
            background: var(--matcha-sidebar-hover);
            color: #ffffff;
            transform: translateX(2px);
        }

        .nav-link.active {
            background: var(--matcha-sidebar-active);
            color: #ffffff;
            font-weight: 700;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.2);
        }

        .nav-link.active::before {
            content: "";
            position: absolute;
            left: -18px;
            top: 6px;
            bottom: 6px;
            width: 4px;
            background: #a7df85;
            border-radius: 0 4px 4px 0;
        }

        .nav-icon {
            font-size: 17px;
            width: 22px;
            text-align: center;
            opacity: 0.9;
        }

        .nav-admin-tag {
            margin-left: auto;
            font-size: 9.5px;
            font-weight: 700;
            background: #a7df85;
            color: #102014;
            padding: 2px 6px;
            border-radius: 4px;
            letter-spacing: 0.4px;
            text-transform: uppercase;
        }

        .sidebar-logout {
            margin-top: auto;
            padding-top: 24px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .logout-button {
            width: 100%;
            padding: 11px 14px;
            border: 1px solid rgba(248, 113, 113, 0.2);
            border-radius: var(--radius-md);
            background: rgba(239, 68, 68, 0.1);
            color: #fca5a5;
            font-weight: 600;
            font-size: 13.5px;
            cursor: pointer;
            text-align: left;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.2s;
        }

        .logout-button:hover {
            background: rgba(239, 68, 68, 0.2);
            color: #fee2e2;
            border-color: rgba(248, 113, 113, 0.35);
        }

        /* MAIN CONTENT AREA */
        .main {
            margin-left: 260px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: var(--matcha-bg);
        }

        .topbar {
            min-height: 72px;
            background: var(--matcha-surface);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            padding: 14px 36px;
            position: sticky;
            top: 0;
            z-index: 40;
            box-shadow: var(--shadow-xs);
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .topbar h2 {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-main);
            letter-spacing: -0.3px;
            margin: 0;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .user-pill {
            display: flex;
            align-items: center;
            gap: 10px;
            background: var(--matcha-primary-light);
            padding: 6px 14px 6px 8px;
            border-radius: 30px;
            border: 1px solid var(--border-color);
        }

        .user-pill-avatar {
            width: 30px;
            height: 30px;
            background: var(--matcha-primary);
            color: white;
            font-weight: 700;
            font-size: 12px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .user-pill-text {
            font-size: 13px;
            font-weight: 600;
            color: var(--matcha-primary-dark);
        }

        .user-pill-role {
            font-size: 10.5px;
            font-weight: 700;
            background: var(--matcha-primary);
            color: white;
            padding: 2px 7px;
            border-radius: 12px;
            text-transform: uppercase;
        }

        .date-badge {
            font-size: 12.5px;
            color: var(--text-muted);
            font-weight: 500;
        }

        .content {
            padding: 32px 36px 60px;
            flex: 1;
        }

        /* TYPOGRAPHY */
        .page-title {
            margin: 0 0 6px;
            font-size: 26px;
            font-weight: 800;
            color: var(--text-main);
            letter-spacing: -0.4px;
        }

        .page-subtitle {
            margin: 0 0 24px;
            color: var(--text-secondary);
            font-size: 14px;
            line-height: 1.6;
        }

        /* CARDS */
        .card {
            background: var(--matcha-surface);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 24px;
            box-shadow: var(--shadow-sm);
            margin-bottom: 20px;
            transition: box-shadow 0.2s;
        }

        .card:hover {
            box-shadow: var(--shadow-md);
        }

        /* BUTTONS */
        .btn,
        .product-btn,
        .sales-btn,
        .detail-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: none;
            border-radius: var(--radius-md);
            padding: 10px 18px;
            background: var(--matcha-primary);
            color: white;
            text-decoration: none;
            cursor: pointer;
            font-size: 13.5px;
            font-weight: 600;
            font-family: inherit;
            box-shadow: 0 2px 6px rgba(61, 111, 70, 0.22);
            transition: all 0.2s ease;
        }

        .btn:hover,
        .product-btn:hover,
        .sales-btn:hover,
        .detail-btn:hover {
            background: var(--matcha-primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(61, 111, 70, 0.28);
        }

        .btn:active,
        .product-btn:active,
        .sales-btn:active,
        .detail-btn:active {
            transform: translateY(0);
        }

        .btn-secondary,
        .product-btn-secondary,
        .detail-btn-light {
            background: #edf3ee !important;
            color: var(--matcha-primary-dark) !important;
            box-shadow: none !important;
            border: 1px solid var(--border-color);
        }

        .btn-secondary:hover,
        .product-btn-secondary:hover,
        .detail-btn-light:hover {
            background: #e2ede3 !important;
            color: #17331d !important;
        }

        .btn-danger,
        .product-btn-danger {
            background: #fee2e2 !important;
            color: #b91c1c !important;
            box-shadow: none !important;
            border: 1px solid #fecaca;
        }

        .btn-danger:hover,
        .product-btn-danger:hover {
            background: #fecaca !important;
            color: #991b1b !important;
        }

        /* INPUTS */
        .search-input,
        .form-input,
        input[type="text"],
        input[type="email"],
        input[type="number"],
        input[type="date"],
        select,
        textarea {
            width: 100%;
            padding: 11px 14px;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            outline: none;
            font-size: 14px;
            font-family: inherit;
            background: #ffffff;
            color: var(--text-main);
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .search-input:focus,
        .form-input:focus,
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="number"]:focus,
        input[type="date"]:focus,
        select:focus,
        textarea:focus {
            border-color: var(--matcha-primary);
            box-shadow: 0 0 0 3px rgba(61, 111, 70, 0.15);
        }

        label {
            display: block;
            font-weight: 600;
            font-size: 13.5px;
            color: var(--text-main);
            margin-bottom: 7px;
        }

        /* TABLES */
        .table-wrap,
        .product-table-wrapper,
        .sales-table-wrap,
        .dashboard-table-wrap,
        .detail-table-wrap {
            width: 100%;
            overflow-x: auto;
            border-radius: var(--radius-md);
            border: 1px solid var(--border-color);
            background: white;
        }

        .data-table,
        .sales-table,
        .detail-table,
        .dashboard-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13.5px;
            text-align: left;
        }

        .data-table th,
        .sales-table th,
        .detail-table th,
        .dashboard-table th {
            background: #f3f7f4;
            color: #3b5a41;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 14px 16px;
            border-bottom: 1px solid var(--border-color);
            white-space: nowrap;
        }

        .data-table td,
        .sales-table td,
        .detail-table td,
        .dashboard-table td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--border-light);
            color: var(--text-main);
            vertical-align: middle;
        }

        .data-table tbody tr:last-child td,
        .sales-table tbody tr:last-child td,
        .detail-table tbody tr:last-child td {
            border-bottom: none;
        }

        .data-table tbody tr:hover,
        .sales-table tbody tr:hover,
        .dashboard-table tbody tr:hover {
            background: #f7faf8;
        }

        /* BADGES */
        .badge,
        .status-badge,
        .sales-status,
        .detail-status {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11.5px;
            font-weight: 700;
            white-space: nowrap;
        }

        .badge-success,
        .status-badge,
        .sales-status,
        .detail-status {
            background: var(--success-bg);
            color: var(--success-text);
            border: 1px solid #bbf0cb;
        }

        .badge-warning {
            background: var(--warning-bg);
            color: var(--warning-text);
            border: 1px solid #fae8a4;
        }

        .badge-danger,
        .stock-badge {
            background: var(--danger-bg);
            color: var(--danger-text);
            border: 1px solid #fecaca;
        }

        .badge-info {
            background: var(--info-bg);
            color: var(--info-text);
            border: 1px solid #c7e2f5;
        }

        /* ALERTS */
        .alert-success {
            background: #eaf6ed;
            color: #1a6330;
            border: 1px solid #c3ebcc;
            padding: 14px 18px;
            border-radius: var(--radius-md);
            margin-bottom: 22px;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: var(--shadow-xs);
        }

        .alert-error {
            background: #fdf2f2;
            color: #9b1c1c;
            border: 1px solid #f8b4b4;
            padding: 14px 18px;
            border-radius: var(--radius-md);
            margin-bottom: 22px;
            font-size: 14px;
            font-weight: 500;
            box-shadow: var(--shadow-xs);
        }

        .alert-error ul {
            margin: 6px 0 0 20px;
        }

        /* PAGINATION */
        .pagination,
        .sales-pagination {
            margin-top: 24px;
            display: flex;
            justify-content: flex-end;
        }

        /* RESPONSIVE */
        @media (max-width: 860px) {
            .sidebar {
                position: static;
                width: 100%;
                min-height: auto;
                box-shadow: none;
            }

            .main {
                margin-left: 0;
            }

            .topbar {
                padding: 14px 20px;
            }

            .content {
                padding: 20px;
            }
        }

        /* PRINT STYLES */
        @media print {
            .sidebar,
            .topbar,
            .btn,
            .no-print,
            .sidebar-logout {
                display: none !important;
            }

            .main {
                margin-left: 0 !important;
                background: white !important;
            }

            .content {
                padding: 0 !important;
            }

            body {
                background: white !important;
                color: #000000 !important;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
            }
        }
    </style>
</head>

<body>
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="brand-container">
            <div class="brand-logo-icon">🌿</div>
            <div class="brand-text-wrap">
                <div class="brand">Diesel Parts</div>
                <div class="brand-subtitle">Inventory System</div>
            </div>
        </div>

        @auth
            <div class="sidebar-user">
                <div class="sidebar-user-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="sidebar-user-info">
                    <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
                    <span class="sidebar-user-role {{ auth()->user()->role === 'admin' ? 'role-badge-admin' : 'role-badge-staff' }}">
                        {{ auth()->user()->role === 'admin' ? '🛡️ Admin' : '👤 Staff' }}
                    </span>
                </div>
            </div>
        @endauth

        <div class="nav-label">Menu Utama</div>

        <a
            class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
            href="{{ route('dashboard') }}"
        >
            <span class="nav-icon">📊</span>
            <span>Dashboard</span>
        </a>

        <a
            class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}"
            href="{{ route('products.index') }}"
        >
            <span class="nav-icon">📦</span>
            <span>Produk</span>
        </a>

        <a
            class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}"
            href="{{ route('suppliers.index') }}"
        >
            <span class="nav-icon">🏭</span>
            <span>Supplier</span>
        </a>

        <div class="nav-label">Transaksi</div>

        <a
            class="nav-link {{ request()->routeIs('purchases.*') ? 'active' : '' }}"
            href="{{ route('purchases.index') }}"
        >
            <span class="nav-icon">📥</span>
            <span>Stok Masuk</span>
        </a>

        <a
            class="nav-link {{ request()->routeIs('sales.*') ? 'active' : '' }}"
            href="{{ route('sales.index') }}"
        >
            <span class="nav-icon">💰</span>
            <span>Penjualan</span>
        </a>

        {{-- LAPORAN KEUANGAN: KHUSUS HANYA UNTUK ADMIN --}}
        @if (auth()->check() && auth()->user()->role === 'admin')
            <div class="nav-label">Laporan & Keuangan</div>

            <a
                class="nav-link {{ request()->routeIs('financial-reports.*') ? 'active' : '' }}"
                href="{{ route('financial-reports.index') }}"
            >
                <span class="nav-icon">📈</span>
                <span>Laporan Keuangan</span>
                <span class="nav-admin-tag">Admin</span>
            </a>
        @endif

        <div class="sidebar-logout">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-button">
                    <span>↪</span>
                    <span>Keluar Sistem</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN APP -->
    <div class="main">
        <header class="topbar">
            <div class="topbar-left">
                <h2>@yield('heading', View::getSection('header', 'Dashboard'))</h2>
            </div>

            <div class="topbar-right">
                <span class="date-badge">
                    🗓️ {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                </span>

                @auth
                    <div class="user-pill">
                        <div class="user-pill-avatar">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <span class="user-pill-text">{{ auth()->user()->name }}</span>
                        <span class="user-pill-role">{{ auth()->user()->role }}</span>
                    </div>
                @endauth
            </div>
        </header>

        <main class="content">
            @if (session('success'))
                <div class="alert-success">
                    <span>✅</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="alert-error">
                    <span>⚠️</span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
