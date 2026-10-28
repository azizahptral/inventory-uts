
@extends('layout.app')

@section('title', 'Dashboard - Diesel Parts')
@section('header', 'Dashboard')

@section('content')
<style>
    .dashboard-page { max-width: 1250px; margin: 0 auto; }

    .welcome-card {
        background: linear-gradient(135deg, #142238, #24506a);
        color: white;
        padding: 30px;
        border-radius: 15px;
        margin-bottom: 25px;
    }

    .welcome-card h1 {
        margin: 0 0 10px;
        font-size: 27px;
    }

    .welcome-card p {
        margin: 0;
        color: #d4e2ee;
        font-size: 14px;
        line-height: 1.7;
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
        border-radius: 13px;
        border: 1px solid #e5eaf1;
        box-shadow: 0 3px 12px rgba(15,23,42,.035);
        min-width: 0;
    }

    .stat-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        margin-bottom: 18px;
    }

    .stat-label {
        color: #64748b;
        font-size: 13px;
    }

    .stat-icon {
        width: 39px;
        height: 39px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #e0f2fe;
        font-size: 19px;
    }

    .stat-number {
        font-size: 27px;
        font-weight: 700;
        color: #142238;
        overflow-wrap: anywhere;
    }

    .stat-description {
        font-size: 12px;
        color: #94a3b8;
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
        border: 1px solid #e5eaf1;
        border-radius: 13px;
        padding: 23px;
        box-shadow: 0 3px 12px rgba(15,23,42,.035);
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
        margin: 0 0 7px;
    }

    .dashboard-muted {
        color: #64748b;
        font-size: 12px;
        line-height: 1.6;
    }

    .dashboard-link {
        color: #0d9488;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
    }

    .dashboard-link:hover { text-decoration: underline; }

    .dashboard-table-wrap { overflow-x: auto; }

    .dashboard-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 340px;
    }

    .dashboard-table th {
        text-align: left;
        padding: 12px 10px;
        color: #64748b;
        background: #f8fafc;
        font-size: 11px;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .dashboard-table td {
        padding: 14px 10px;
        border-bottom: 1px solid #edf1f6;
        font-size: 13px;
        vertical-align: middle;
    }

    .dashboard-table tbody tr:last-child td {
        border-bottom: none;
    }

    .product-name {
        font-weight: 600;
        color: #1e293b;
    }

    .stock-badge {
        display: inline-block;
        background: #fee2e2;
        color: #991b1b;
        border-radius: 20px;
        padding: 5px 9px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-badge {
        display: inline-block;
        background: #dcfce7;
        color: #166534;
        border-radius: 20px;
        padding: 5px 9px;
        font-size: 11px;
        font-weight: 600;
    }

    .dashboard-empty {
        padding: 25px 10px;
        text-align: center;
        color: #64748b;
        font-size: 13px;
        line-height: 1.8;
    }

    .dashboard-bottom {
        margin-top: 20px;
        background: white;
        border: 1px solid #e5eaf1;
        border-radius: 13px;
        padding: 22px;
    }

    .dashboard-bottom h2 {
        font-size: 17px;
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
        padding: 11px 15px;
        border-radius: 8px;
        background: #0d9488;
        color: white;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
    }

    .quick-action:hover { background: #0f766e; }

    .quick-action.secondary {
        background: #e8eef5;
        color: #334155;
    }

    .quick-action.secondary:hover { background: #dbe4ee; }

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
        </div>
    </section>

</div>
@endsection
