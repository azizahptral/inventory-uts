@extends('layout.app')

@section('title', 'Dashboard - Diesel Parts')
@section('header', 'Dashboard')

@section('content')
<style>
    .dashboard-page { max-width: 1250px; margin: 0 auto; }

    .welcome-card {
        background: linear-gradient(135deg, #162c1b 0%, #294e32 60%, #3e6d47 100%);
        color: white;
        padding: 32px 36px;
        border-radius: var(--radius-lg);
        margin-bottom: 25px;
        box-shadow: 0 8px 24px rgba(22, 44, 27, 0.15);
        position: relative;
        overflow: hidden;
    }

    .welcome-card::after {
        content: "🌿";
        position: absolute;
        right: 25px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 80px;
        opacity: 0.12;
        pointer-events: none;
    }

    .welcome-card h1 {
        margin: 0 0 10px;
        font-size: 27px;
        font-weight: 800;
        letter-spacing: -0.4px;
    }

    .welcome-card p {
        margin: 0;
        color: #d8eadc;
        font-size: 14px;
        line-height: 1.7;
        max-width: 720px;
    }

    .dashboard-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 18px;
        margin-bottom: 25px;
    }

    .dashboard-stat {
        background: white;
        padding: 22px;
        border-radius: var(--radius-lg);
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-sm);
        min-width: 0;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .dashboard-stat:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .stat-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        margin-bottom: 18px;
    }

    .stat-label {
        color: var(--text-secondary);
        font-size: 13px;
        font-weight: 600;
    }

    .stat-icon {
        width: 39px;
        height: 39px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--matcha-primary-light);
        color: var(--matcha-primary-dark);
        font-size: 19px;
    }

    .stat-number {
        font-size: 27px;
        font-weight: 800;
        color: var(--text-main);
        overflow-wrap: anywhere;
        letter-spacing: -0.5px;
    }

    .stat-description {
        font-size: 12px;
        color: var(--text-muted);
        margin-top: 7px;
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
        gap: 20px;
        align-items: start;
    }

    .dashboard-card {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 23px;
        box-shadow: var(--shadow-sm);
        min-width: 0;
    }

    .dashboard-card-heading {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 20px;
    }

    .dashboard-card-heading h2 {
        font-size: 17px;
        font-weight: 700;
        color: var(--text-main);
        margin: 0 0 7px;
    }

    .dashboard-muted {
        color: var(--text-secondary);
        font-size: 12.5px;
        line-height: 1.6;
    }

    .dashboard-link {
        color: var(--matcha-primary);
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
        white-space: nowrap;
        transition: color 0.2s;
    }

    .dashboard-link:hover {
        color: var(--matcha-primary-hover);
        text-decoration: underline;
    }

    .dashboard-table-wrap {
        overflow-x: auto;
        border-radius: var(--radius-md);
        border: 1px solid var(--border-light);
    }

    .dashboard-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 340px;
    }

    .dashboard-table th {
        text-align: left;
        padding: 12px 14px;
        color: #3b5a41;
        background: #f3f7f4;
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        white-space: nowrap;
        border-bottom: 1px solid var(--border-color);
    }

    .dashboard-table td {
        padding: 14px 14px;
        border-bottom: 1px solid var(--border-light);
        font-size: 13px;
        vertical-align: middle;
    }

    .dashboard-table tbody tr:last-child td {
        border-bottom: none;
    }

    .product-name {
        font-weight: 600;
        color: var(--text-main);
    }

    .stock-badge {
        display: inline-block;
        background: #fee2e2;
        color: #991b1b;
        border-radius: 20px;
        padding: 4px 10px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-badge {
        display: inline-block;
        background: #e6f6eb;
        color: #166534;
        border-radius: 20px;
        padding: 4px 10px;
        font-size: 11px;
        font-weight: 700;
    }

    .dashboard-empty {
        padding: 30px 10px;
        text-align: center;
        color: var(--text-muted);
        font-size: 13px;
        line-height: 1.8;
    }

    .dashboard-bottom {
        margin-top: 24px;
        background: white;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 24px;
        box-shadow: var(--shadow-sm);
    }

    .dashboard-bottom h2 {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-main);
        margin: 0 0 7px;
    }

    .quick-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 18px;
    }

    .quick-action {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 11px 18px;
        border-radius: var(--radius-md);
        background: var(--matcha-primary);
        color: white;
        text-decoration: none;
        font-size: 13.5px;
        font-weight: 600;
        transition: all 0.2s;
        box-shadow: 0 2px 6px rgba(61, 111, 70, 0.2);
    }

    .quick-action:hover {
        background: var(--matcha-primary-hover);
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(61, 111, 70, 0.25);
    }

    .quick-action.secondary {
        background: #edf3ee;
        color: var(--matcha-primary-dark);
        border: 1px solid var(--border-color);
        box-shadow: none;
    }

    .quick-action.secondary:hover {
        background: #e0ece1;
        color: #142a18;
    }

    .quick-action.admin-action {
        background: #234328;
        color: #ffffff;
        border: 1px solid #37653e;
    }

    .quick-action.admin-action:hover {
        background: #19331e;
    }

    @media (max-width: 1000px) {
        .dashboard-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .dashboard-grid { grid-template-columns: 1fr; }
    }

    @media (max-width: 520px) {
        .dashboard-stats { grid-template-columns: 1fr 1fr; gap: 10px; }
        .dashboard-stat { padding: 15px; }
        .stat-number { font-size: 22px; }
        .welcome-card { padding: 22px; }
        .welcome-card h1 { font-size: 23px; }
        .dashboard-card { padding: 16px; }
        .dashboard-bottom { padding: 17px; }
    }
</style>

<div class="dashboard-page">

    <section class="welcome-card">
        <h1>Selamat Datang di Diesel Parts!</h1>
        <p>
            Sistem manajemen persediaan dan penjualan sparepart mobil diesel.
            Pantau kondisi stok dan aktivitas transaksi dari satu halaman.
        </p>
    </section>

    <div class="dashboard-stats">
        <div class="dashboard-stat">
            <div class="stat-top">
                <span class="stat-label">Total Produk</span>
                <span class="stat-icon">📦</span>
            </div>
            <div class="stat-number">{{ number_format($totalProducts, 0, ',', '.') }}</div>
            <div class="stat-description">Produk terdaftar</div>
        </div>

        <div class="dashboard-stat">
            <div class="stat-top">
                <span class="stat-label">Total Supplier</span>
                <span class="stat-icon">🏢</span>
            </div>
            <div class="stat-number">{{ number_format($totalSuppliers, 0, ',', '.') }}</div>
            <div class="stat-description">Supplier terdaftar</div>
        </div>

        <div class="dashboard-stat">
            <div class="stat-top">
                <span class="stat-label">Transaksi Penjualan</span>
                <span class="stat-icon">🧾</span>
            </div>
            <div class="stat-number">{{ number_format($totalSales, 0, ',', '.') }}</div>
            <div class="stat-description">Total transaksi tersimpan</div>
        </div>

        <div class="dashboard-stat">
            <div class="stat-top">
                <span class="stat-label">Total Omzet</span>
                <span class="stat-icon">💰</span>
            </div>
            <div class="stat-number" style="font-size:22px">
                Rp {{ number_format($totalRevenue, 0, ',', '.') }}
            </div>
            <div class="stat-description">Akumulasi nilai penjualan</div>
        </div>
    </div>

    <div class="dashboard-grid">
        <section class="dashboard-card">
            <div class="dashboard-card-heading">
                <div>
                    <h2>⚠️ Stok Perlu Diperhatikan</h2>
                    <div class="dashboard-muted">
                        Produk dengan stok sama dengan atau di bawah batas minimum.
                    </div>
                </div>
                <a href="{{ route('products.index') }}" class="dashboard-link">
                    Lihat Produk →
                </a>
            </div>

            @if($lowStockProducts->count())
                <div class="dashboard-table-wrap">
                    <table class="dashboard-table">
                        <thead>
                            <tr>
                                <th>Nama Produk</th>
                                <th>Stok</th>
                                <th>Batas Minimum</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lowStockProducts as $product)
                                <tr>
                                    <td>
                                        <div class="product-name">{{ $product->name }}</div>
                                        <div class="dashboard-muted">
                                            {{ $product->sku ?: 'Tanpa SKU' }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="stock-badge">
                                            {{ number_format($product->stock, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td>{{ number_format($product->min_stock, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="dashboard-empty">
                    <div style="font-size:28px;margin-bottom:8px">✅</div>
                    <strong>Stok aman!</strong>
                    <div>Tidak ada produk yang berada di bawah batas minimum.</div>
                </div>
            @endif
        </section>

        <section class="dashboard-card">
            <div class="dashboard-card-heading">
                <div>
                    <h2>🧾 Penjualan Terbaru</h2>
                    <div class="dashboard-muted">
                        Lima transaksi terakhir yang tersimpan.
                    </div>
                </div>
                <a href="{{ route('sales.index') }}" class="dashboard-link">
                    Lihat Semua →
                </a>
            </div>

            @if($recentSales->count())
                <div class="dashboard-table-wrap">
                    <table class="dashboard-table">
                        <thead>
                            <tr>
                                <th>Transaksi</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentSales as $sale)
                                <tr>
                                    <td>
                                        <a
                                            href="{{ route('sales.show', $sale) }}"
                                            class="dashboard-link"
                                        >
                                            {{ $sale->sale_number }}
                                        </a>
                                        <div class="dashboard-muted">
                                            {{ $sale->user->name ?? 'Kasir' }}
                                        </div>
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y') }}
                                    </td>
                                    <td style="font-weight:600;white-space:nowrap">
                                        Rp {{ number_format($sale->total_amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="dashboard-empty">
                    <div style="font-size:28px;margin-bottom:8px">🧾</div>
                    <strong>Belum ada transaksi</strong>
                    <div>Transaksi penjualan akan muncul di bagian ini.</div>
                </div>
            @endif
        </section>
    </div>

    <section class="dashboard-bottom">
        <h2>Akses Cepat</h2>
        <div class="dashboard-muted">
            Pilih menu untuk langsung mengelola persediaan dan transaksi.
        </div>

        <div class="quick-actions">
            <a href="{{ route('products.index') }}" class="quick-action">
                📦 Kelola Produk
            </a>
            <a href="{{ route('suppliers.index') }}" class="quick-action secondary">
                🏢 Data Supplier
            </a>
            <a href="{{ route('purchases.index') }}" class="quick-action secondary">
                ⬆ Stok Masuk
            </a>
            <a href="{{ route('sales.create') }}" class="quick-action">
                + Transaksi Penjualan
            </a>

            @if (auth()->check() && auth()->user()->role === 'admin')
                <a href="{{ route('financial-reports.index') }}" class="quick-action admin-action">
                    📈 Laporan Keuangan (Admin)
                </a>
            @endif
        </div>
    </section>

</div>
@endsection
