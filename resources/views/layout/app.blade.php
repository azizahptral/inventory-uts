
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Inventory System') | Diesel Parts</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f7fb;
            color: #1e293b;
        }

        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            width: 250px;
            padding: 28px 18px;
            background: #142238;
            color: white;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        .brand {
            font-size: 22px;
            font-weight: bold;
            margin: 0 0 8px;
        }

        .brand-subtitle {
            color: #9fb0c8;
            font-size: 12px;
            margin-bottom: 35px;
        }

        .nav-label {
            color: #8293ac;
            font-size: 11px;
            letter-spacing: 1.5px;
            margin: 24px 12px 10px;
        }

        .nav-link {
            display: block;
            padding: 12px 14px;
            margin: 5px 0;
            color: #cbd5e1;
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
            transition: background .2s, color .2s;
        }

        .nav-link:hover,
        .nav-link.active {
            background: #0d9488;
            color: white;
        }

        .sidebar-logout {
            margin-top: auto;
            padding-top: 30px;
        }

        .logout-button {
            width: 100%;
            padding: 12px 14px;
            border: none;
            border-radius: 8px;
            background: #fee2e2;
            color: #b91c1c;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            text-align: left;
        }

        .logout-button:hover {
            background: #fecaca;
        }

        .main {
            margin-left: 250px;
            min-height: 100vh;
        }

        .topbar {
            min-height: 76px;
            background: white;
            border-bottom: 1px solid #e5eaf1;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            padding: 16px 32px;
        }

        .topbar h2 {
            font-size: 19px;
            margin: 0;
        }

        .user-label {
            color: #64748b;
            font-size: 13px;
        }

        .content {
            padding: 32px;
        }

        .page-title {
            margin: 0 0 8px;
            font-size: 27px;
        }

        .page-subtitle {
            margin: 0 0 28px;
            color: #64748b;
            font-size: 14px;
        }

        .card {
            background: white;
            border: 1px solid #e6ebf2;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 3px 12px rgba(15, 23, 42, 0.03);
        }

        .btn {
            display: inline-block;
            border: none;
            border-radius: 7px;
            padding: 11px 16px;
            background: #0d9488;
            color: white;
            text-decoration: none;
            cursor: pointer;
            font-size: 14px;
        }

        .btn:hover {
            background: #0f766e;
        }

        .btn-secondary {
            background: #e8eef5;
            color: #334155;
        }

        .search-input,
        .form-input {
            width: 100%;
            padding: 11px 13px;
            border: 1px solid #d7e0ea;
            border-radius: 7px;
            outline: none;
            font-size: 14px;
            background: white;
        }

        .search-input:focus,
        .form-input:focus {
            border-color: #0d9488;
        }

        .table-wrap {
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        .data-table th {
            background: #f8fafc;
            color: #64748b;
            font-size: 12px;
            text-align: left;
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        .data-table th,
        .data-table td {
            padding: 15px 16px;
            border-bottom: 1px solid #edf1f6;
        }

        .data-table tbody tr:hover {
            background: #f8fbfd;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            padding: 13px 16px;
            border-radius: 8px;
            margin-bottom: 18px;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            padding: 13px 16px;
            border-radius: 8px;
            margin-bottom: 18px;
        }

        .pagination {
            margin-top: 20px;
        }

        @media (max-width: 760px) {
            .sidebar {
                position: static;
                width: 100%;
                min-height: auto;
            }

            .sidebar-logout {
                margin-top: 20px;
                padding-top: 10px;
            }

            .main {
                margin-left: 0;
            }

            .content {
                padding: 18px;
            }

            .topbar {
                padding: 16px 18px;
            }
        }
    </style>
</head>

<body>
    <aside class="sidebar">
        <div class="brand">Diesel Parts</div>
        <div class="brand-subtitle">INVENTORY MANAGEMENT</div>

        <div class="nav-label">MENU UTAMA</div>

        <a
            class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
            href="{{ route('dashboard') }}"
        >
            Dashboard
        </a>

        <a
            class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}"
            href="{{ route('products.index') }}"
        >
            Produk
        </a>

        <a
            class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}"
            href="{{ route('suppliers.index') }}"
        >
            Supplier
        </a>

        <div class="nav-label">TRANSAKSI</div>

        <a
            class="nav-link {{ request()->routeIs('purchases.*') ? 'active' : '' }}"
            href="{{ route('purchases.index') }}"
        >
            Stok Masuk
        </a>

        <a
            class="nav-link {{ request()->routeIs('sales.*') ? 'active' : '' }}"
            href="{{ route('sales.index') }}"
        >
            Penjualan
        </a>

        <div class="sidebar-logout">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-button">
                    ↪ Logout
                </button>
            </form>
        </div>
    </aside>

    <div class="main">
        <header class="topbar">
            <h2>@yield('heading', 'Dashboard')</h2>
            <span class="user-label">Inventory System</span>
        </header>

        <main class="content">
            @if (session('success'))
                <div class="alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert-error">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
