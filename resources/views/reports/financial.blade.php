@extends('layout.app')

@section('title', 'Laporan Keuangan - Diesel Parts')
@section('heading', 'Laporan Keuangan')

@section('content')
<style>
    .report-container {
        max-width: 1300px;
        margin: 0 auto;
    }

    /* HEADER BAR */
    .report-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
    }

    .report-header-left h1 {
        font-size: 26px;
        font-weight: 800;
        color: var(--text-main);
        margin-bottom: 6px;
    }

    .report-header-left p {
        color: var(--text-secondary);
        font-size: 14px;
        margin: 0;
    }

    .report-header-actions {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .btn-print {
        background: #2b5735;
        color: #ffffff;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        border-radius: var(--radius-md);
        font-weight: 700;
        text-decoration: none;
        font-size: 14px;
        box-shadow: 0 4px 12px rgba(43, 87, 53, 0.25);
        transition: all 0.2s;
    }

    .btn-print:hover {
        background: #1f3f26;
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(43, 87, 53, 0.35);
    }

    /* FILTER SECTION */
    .filter-card {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 22px;
        margin-bottom: 24px;
        box-shadow: var(--shadow-sm);
    }

    .period-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--border-light);
    }

    .period-tab-btn {
        padding: 9px 18px;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s;
        border: 1px solid var(--border-color);
        background: #f7faf8;
        color: var(--text-secondary);
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .period-tab-btn:hover {
        background: #eaf3ed;
        color: var(--matcha-primary-dark);
        border-color: var(--matcha-primary);
    }

    .period-tab-btn.active {
        background: var(--matcha-primary);
        color: white;
        border-color: var(--matcha-primary);
        box-shadow: 0 2px 8px rgba(61, 111, 70, 0.3);
    }

    .filter-inputs-row {
        display: flex;
        align-items: flex-end;
        gap: 14px;
        flex-wrap: wrap;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
        min-width: 170px;
    }

    .filter-group label {
        font-size: 12.5px;
        font-weight: 600;
        color: var(--text-secondary);
        margin: 0;
    }

    /* KPI STATS CARDS */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 18px;
        margin-bottom: 24px;
    }

    .kpi-card {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 22px 20px;
        box-shadow: var(--shadow-sm);
        position: relative;
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .kpi-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
    }

    .kpi-card.revenue::before { background: #3d6f46; }
    .kpi-card.cogs::before { background: #d97706; }
    .kpi-card.profit::before { background: #16a34a; }
    .kpi-card.cashflow::before { background: #0284c7; }

    .kpi-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
    }

    .kpi-title {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .kpi-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
    }

    .kpi-revenue .kpi-icon { background: #eaf3ed; color: #2e5936; }
    .kpi-cogs .kpi-icon { background: #fef3c7; color: #92400e; }
    .kpi-profit .kpi-icon { background: #e6f6eb; color: #166534; }
    .kpi-cashflow .kpi-icon { background: #e0f2fe; color: #0369a1; }

    .kpi-value {
        font-size: 24px;
        font-weight: 800;
        color: var(--text-main);
        letter-spacing: -0.5px;
        margin-bottom: 6px;
    }

    .kpi-sub {
        font-size: 12px;
        color: var(--text-secondary);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .margin-badge {
        display: inline-block;
        padding: 2px 7px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 700;
        background: #dcfce7;
        color: #15803d;
    }

    /* SECONDARY METRICS */
    .secondary-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 24px;
    }

    .secondary-card {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 22px;
        box-shadow: var(--shadow-sm);
    }

    .secondary-card-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .finance-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px dashed var(--border-light);
        font-size: 13.5px;
    }

    .finance-row:last-child {
        border-bottom: none;
    }

    .finance-row-label {
        color: var(--text-secondary);
    }

    .finance-row-val {
        font-weight: 700;
        color: var(--text-main);
    }

    .highlight-net {
        background: #f4f9f4;
        padding: 12px 14px;
        border-radius: var(--radius-md);
        margin-top: 10px;
        border: 1px solid #d2e4d5;
    }

    /* SECTION TABS FOR TABLES */
    .report-tabs-wrapper {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        margin-bottom: 30px;
    }

    .nav-tabs-bar {
        display: flex;
        gap: 4px;
        background: #f4f8f5;
        padding: 8px 12px 0;
        border-bottom: 1px solid var(--border-color);
        overflow-x: auto;
    }

    .nav-tab-item {
        padding: 12px 18px;
        font-size: 13.5px;
        font-weight: 600;
        color: var(--text-secondary);
        text-decoration: none;
        border-radius: var(--radius-md) var(--radius-md) 0 0;
        background: transparent;
        cursor: pointer;
        border: 1px solid transparent;
        border-bottom: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
        transition: all 0.2s;
    }

    .nav-tab-item:hover {
        color: var(--matcha-primary);
        background: rgba(255, 255, 255, 0.6);
    }

    .nav-tab-item.active {
        background: white;
        color: var(--matcha-primary-dark);
        border-color: var(--border-color);
        font-weight: 700;
        box-shadow: 0 -2px 6px rgba(0,0,0,0.02);
    }

    .tab-badge {
        font-size: 11px;
        padding: 2px 7px;
        border-radius: 10px;
        background: #e2ede4;
        color: #2b5433;
        font-weight: 700;
    }

    .tab-content-panel {
        padding: 24px;
        display: none;
    }

    .tab-content-panel.active {
        display: block;
    }

    /* TOP PRODUCTS TABLE */
    .top-products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 14px;
        margin-top: 14px;
    }

    .product-profit-card {
        background: #fbfdfb;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        padding: 16px;
    }

    .product-profit-name {
        font-weight: 700;
        font-size: 14px;
        color: var(--text-main);
        margin-bottom: 4px;
    }

    .product-profit-sku {
        font-size: 11px;
        color: var(--text-muted);
        margin-bottom: 12px;
    }

    /* RESPONSIVE */
    @media (max-width: 1100px) {
        .kpi-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
        .secondary-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 640px) {
        .kpi-grid {
            grid-template-columns: 1fr;
        }
        .filter-inputs-row {
            flex-direction: column;
            align-items: stretch;
        }
        .report-header {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

<div class="report-container">
    <!-- HEADER BAR -->
    <div class="report-header">
        <div class="report-header-left">
            <h1>📊 Laporan Keuangan & Laba Rugi</h1>
            <p>Rekapitulasi lengkap arus kas, omzet penjualan, biaya modal (HPP), dan tagihan supplier.</p>
        </div>

        <div class="report-header-actions">
            @php
                $printQuery = http_build_query(request()->all());
            @endphp
            <a href="{{ route('financial-reports.print') }}?{{ $printQuery }}" target="_blank" class="btn-print">
                <span>🖨️ Cetak Laporan Resmi</span>
            </a>
        </div>
    </div>

    <!-- FILTER SECTION -->
    <div class="filter-card">
        <!-- Period Preset Pills -->
        <div class="period-tabs">
            <a href="{{ route('financial-reports.index', ['period' => 'daily', 'date' => $selectedDate]) }}"
               class="period-tab-btn {{ $period === 'daily' ? 'active' : '' }}">
                <span>📅 Harian</span>
            </a>
            <a href="{{ route('financial-reports.index', ['period' => 'weekly', 'week_date' => $selectedWeekDate]) }}"
               class="period-tab-btn {{ $period === 'weekly' ? 'active' : '' }}">
                <span>📆 Mingguan (7 Hari)</span>
            </a>
            <a href="{{ route('financial-reports.index', ['period' => 'monthly', 'month' => $selectedMonth, 'year' => $selectedYear]) }}"
               class="period-tab-btn {{ $period === 'monthly' ? 'active' : '' }}">
                <span>🗓️ Bulanan</span>
            </a>
            <a href="{{ route('financial-reports.index', ['period' => 'yearly', 'year' => $selectedYear]) }}"
               class="period-tab-btn {{ $period === 'yearly' ? 'active' : '' }}">
                <span>📈 Tahunan</span>
            </a>
            <a href="{{ route('financial-reports.index', ['period' => 'custom', 'start_date' => $customStart, 'end_date' => $customEnd]) }}"
               class="period-tab-btn {{ $period === 'custom' ? 'active' : '' }}">
                <span>⚙️ Rentang Tanggal Kustom</span>
            </a>
        </div>

        <!-- Filter Form based on Active Period -->
        <form action="{{ route('financial-reports.index') }}" method="GET" class="filter-inputs-row">
            <input type="hidden" name="period" value="{{ $period }}">

            @if ($period === 'daily')
                <div class="filter-group">
                    <label for="date">Pilih Tanggal:</label>
                    <input type="date" id="date" name="date" class="form-input" value="{{ $selectedDate }}">
                </div>
            @elseif ($period === 'weekly')
                <div class="filter-group">
                    <label for="week_date">Pilih Tanggal Acuan Minggu:</label>
                    <input type="date" id="week_date" name="week_date" class="form-input" value="{{ $selectedWeekDate }}">
                </div>
            @elseif ($period === 'monthly')
                <div class="filter-group">
                    <label for="month">Pilih Bulan:</label>
                    <select id="month" name="month" class="form-input">
                        @for ($m = 1; $m <= 12; $m++)
                            @php
                                $dateObj = \Carbon\Carbon::create(null, $m, 1);
                            @endphp
                            <option value="{{ $m }}" @selected($selectedMonth == $m)>
                                {{ $dateObj->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="filter-group">
                    <label for="year">Pilih Tahun:</label>
                    <select id="year" name="year" class="form-input">
                        @for ($y = now()->year; $y >= now()->year - 5; $y--)
                            <option value="{{ $y }}" @selected($selectedYear == $y)>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>
            @elseif ($period === 'yearly')
                <div class="filter-group">
                    <label for="year">Pilih Tahun:</label>
                    <select id="year" name="year" class="form-input">
                        @for ($y = now()->year; $y >= now()->year - 5; $y--)
                            <option value="{{ $y }}" @selected($selectedYear == $y)>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>
            @elseif ($period === 'custom')
                <div class="filter-group">
                    <label for="start_date">Dari Tanggal:</label>
                    <input type="date" id="start_date" name="start_date" class="form-input" value="{{ $customStart }}">
                </div>
                <div class="filter-group">
                    <label for="end_date">Sampai Tanggal:</label>
                    <input type="date" id="end_date" name="end_date" class="form-input" value="{{ $customEnd }}">
                </div>
            @endif

            <button type="submit" class="btn" style="height: 42px;">
                <span>🔍 Terapkan Filter</span>
            </button>

            <span style="font-size: 13px; color: var(--text-muted); align-self: center; margin-left: auto;">
                <strong>Periode Aktif:</strong> {{ $periodLabel }}
            </span>
        </form>
    </div>

    <!-- PRIMARY KPI METRICS (4 CARDS) -->
    <div class="kpi-grid">
        <!-- Pendapatan / Omzet -->
        <div class="kpi-card kpi-revenue">
            <div class="kpi-header">
                <span class="kpi-title">Total Pendapatan</span>
                <div class="kpi-icon">💰</div>
            </div>
            <div class="kpi-value">
                Rp {{ number_format($totalRevenue, 0, ',', '.') }}
            </div>
            <div class="kpi-sub">
                <span>{{ $totalSalesCount }} Transaksi</span>
                <span>•</span>
                <span>{{ $totalItemsSold }} Unit Terjual</span>
            </div>
        </div>

        <!-- Biaya Pokok (HPP / Modal) -->
        <div class="kpi-card kpi-cogs">
            <div class="kpi-header">
                <span class="kpi-title">Estimasi HPP (Modal)</span>
                <div class="kpi-icon">📦</div>
            </div>
            <div class="kpi-value">
                Rp {{ number_format($totalCogs, 0, ',', '.') }}
            </div>
            <div class="kpi-sub">
                <span>Total modal beli barang yang laku</span>
            </div>
        </div>

        <!-- Estimasi Laba Kotor -->
        <div class="kpi-card kpi-profit">
            <div class="kpi-header">
                <span class="kpi-title">Estimasi Laba Kotor</span>
                <div class="kpi-icon">📈</div>
            </div>
            <div class="kpi-value" style="color: #15803d;">
                Rp {{ number_format($grossProfit, 0, ',', '.') }}
            </div>
            <div class="kpi-sub">
                <span class="margin-badge">Margin: {{ number_format($profitMargin, 1) }}%</span>
                <span>(Omzet - HPP)</span>
            </div>
        </div>

        <!-- Arus Kas Bersih -->
        <div class="kpi-card kpi-cashflow">
            <div class="kpi-header">
                <span class="kpi-title">Arus Kas Bersih</span>
                <div class="kpi-icon">💵</div>
            </div>
            <div class="kpi-value" style="color: {{ $netCashFlow >= 0 ? '#0369a1' : '#b91c1c' }};">
                Rp {{ number_format($netCashFlow, 0, ',', '.') }}
            </div>
            <div class="kpi-sub">
                <span>Masuk: Rp {{ number_format($totalCashInflow, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- SECONDARY METRICS: CASH FLOW & SUPPLIER DEBT -->
    <div class="secondary-grid">
        <!-- Arus Kas Detail -->
        <div class="secondary-card">
            <h3 class="secondary-card-title">
                <span>🏦 Ringkasan Arus Kas (Cash Flow)</span>
            </h3>

            <div class="finance-row">
                <span class="finance-row-label">Penerimaan Kas Penjualan:</span>
                <span class="finance-row-val" style="color: #166534;">
                    + Rp {{ number_format($totalCashInflow, 0, ',', '.') }}
                </span>
            </div>

            <div class="finance-row">
                <span class="finance-row-label">Pengeluaran Kas Bayar Supplier:</span>
                <span class="finance-row-val" style="color: #991b1b;">
                    - Rp {{ number_format($totalCashOutflow, 0, ',', '.') }}
                </span>
            </div>

            <div class="finance-row highlight-net">
                <span class="finance-row-label" style="font-weight: 700; color: var(--text-main);">
                    Arus Kas Bersih Periode Ini:
                </span>
                <span class="finance-row-val" style="font-size: 16px; color: {{ $netCashFlow >= 0 ? '#15803d' : '#b91c1c' }};">
                    Rp {{ number_format($netCashFlow, 0, ',', '.') }}
                </span>
            </div>

            <div style="margin-top: 14px; font-size: 12px; color: var(--text-muted); line-height: 1.5;">
                💡 <em>Arus kas mencatat aliran uang tunai nyata yang masuk dari konsumen dan keluar untuk membayar faktur supplier.</em>
            </div>
        </div>

        <!-- Tagihan & Utang Supplier -->
        <div class="secondary-card">
            <h3 class="secondary-card-title">
                <span>📑 Kewajiban & Hutang Supplier</span>
            </h3>

            <div class="finance-row">
                <span class="finance-row-label">Total Pembelian Stok (Faktur Periode Ini):</span>
                <span class="finance-row-val">
                    Rp {{ number_format($totalPurchaseInvoices, 0, ',', '.') }}
                </span>
            </div>

            <div class="finance-row">
                <span class="finance-row-label">Sisa Hutang Faktur Periode Ini:</span>
                <span class="finance-row-val" style="color: #d97706;">
                    Rp {{ number_format($periodDebtRemaining, 0, ',', '.') }}
                </span>
            </div>

            <div class="finance-row highlight-net" style="background: #fef9ee; border-color: #f7e0aa;">
                <span class="finance-row-label" style="font-weight: 700; color: #92400e;">
                    Total Seluruh Sisa Hutang Toko (Semua Faktur):
                </span>
                <span class="finance-row-val" style="font-size: 16px; color: #b45309;">
                    Rp {{ number_format($allTimeDebt, 0, ',', '.') }}
                </span>
            </div>

            <div class="finance-row">
                <span class="finance-row-label">Total Nilai Estimasi Aset Stok Saat Ini:</span>
                <span class="finance-row-val" style="color: var(--matcha-primary-dark);">
                    Rp {{ number_format($totalInventoryAsset, 0, ',', '.') }}
                </span>
            </div>
        </div>
    </div>

    <!-- TABS SECTION FOR DETAILED TRANSACTIONS -->
    <div class="report-tabs-wrapper">
        <div class="nav-tabs-bar">
            <button type="button" class="nav-tab-item active" onclick="switchReportTab(event, 'tab-sales')">
                <span>🛒 Transaksi Penjualan</span>
                <span class="tab-badge">{{ $sales->count() }}</span>
            </button>
            <button type="button" class="nav-tab-item" onclick="switchReportTab(event, 'tab-purchases')">
                <span>📦 Pembelian Supplier</span>
                <span class="tab-badge">{{ $purchases->count() }}</span>
            </button>
            <button type="button" class="nav-tab-item" onclick="switchReportTab(event, 'tab-payments')">
                <span>💸 Kas Pembayaran Supplier</span>
                <span class="tab-badge">{{ $payments->count() }}</span>
            </button>
            <button type="button" class="nav-tab-item" onclick="switchReportTab(event, 'tab-products')">
                <span>⭐ Performa Produk Terjual</span>
                <span class="tab-badge">{{ count($topProducts) }}</span>
            </button>
        </div>

        <!-- TAB 1: PENJUALAN -->
        <div id="tab-sales" class="tab-content-panel active">
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>No. Transaksi</th>
                            <th>Tanggal</th>
                            <th>Kasir</th>
                            <th>Item Barang</th>
                            <th>Estimasi Modal</th>
                            <th>Total Penjualan</th>
                            <th>Laba Kotor</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sales as $sale)
                            @php
                                $saleCogs = $sale->items->reduce(fn($c, $i) => $c + (((float) ($i->product->purchase_price ?? 0)) * $i->quantity), 0);
                                $saleProfit = (float) $sale->total_amount - $saleCogs;
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td style="font-weight: 600;">{{ $sale->sale_number }}</td>
                                <td>{{ $sale->sale_date?->format('d/m/Y') }}</td>
                                <td>{{ $sale->user->name ?? '-' }}</td>
                                <td>
                                    <div style="font-size: 12.5px;">
                                        @foreach ($sale->items as $item)
                                            <div>{{ $item->product->name ?? 'Produk' }} ({{ $item->quantity }}x)</div>
                                        @endforeach
                                    </div>
                                </td>
                                <td>Rp {{ number_format($saleCogs, 0, ',', '.') }}</td>
                                <td style="font-weight: 700; color: var(--text-main);">
                                    Rp {{ number_format((float) $sale->total_amount, 0, ',', '.') }}
                                </td>
                                <td style="font-weight: 700; color: #166534;">
                                    Rp {{ number_format($saleProfit, 0, ',', '.') }}
                                </td>
                                <td>
                                    <a href="{{ route('sales.show', $sale) }}" class="btn btn-secondary" style="padding: 5px 10px; font-size: 12px;">
                                        Lihat
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" style="text-align: center; padding: 30px; color: var(--text-muted);">
                                    Tidak ada data penjualan pada periode {{ $periodLabel }}.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TAB 2: PEMBELIAN -->
        <div id="tab-purchases" class="tab-content-panel">
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>No. Pembelian</th>
                            <th>Tanggal</th>
                            <th>Supplier</th>
                            <th>Jatuh Tempo</th>
                            <th>Total Tagihan</th>
                            <th>Terbayar</th>
                            <th>Sisa Hutang</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($purchases as $p)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td style="font-weight: 600;">{{ $p->purchase_number }}</td>
                                <td>{{ $p->purchase_date?->format('d/m/Y') }}</td>
                                <td>{{ $p->supplier->name ?? '-' }}</td>
                                <td>{{ $p->due_date?->format('d/m/Y') ?? '-' }}</td>
                                <td style="font-weight: 700;">
                                    Rp {{ number_format((float) $p->total_amount, 0, ',', '.') }}
                                </td>
                                <td style="color: #166534;">
                                    Rp {{ number_format((float) $p->paid_amount, 0, ',', '.') }}
                                </td>
                                <td style="font-weight: 700; color: {{ $p->remaining_amount > 0 ? '#b91c1c' : '#166534' }};">
                                    Rp {{ number_format($p->remaining_amount, 0, ',', '.') }}
                                </td>
                                <td>
                                    @if ($p->remaining_amount <= 0)
                                        <span class="badge badge-success">Lunas</span>
                                    @elseif ((float) $p->paid_amount > 0)
                                        <span class="badge badge-warning">Sebagian</span>
                                    @else
                                        <span class="badge badge-danger">Belum Dibayar</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('purchases.show', $p) }}" class="btn btn-secondary" style="padding: 5px 10px; font-size: 12px;">
                                        Lihat
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" style="text-align: center; padding: 30px; color: var(--text-muted);">
                                    Tidak ada data pembelian pada periode {{ $periodLabel }}.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TAB 3: PEMBAYARAN KAS KELUAR -->
        <div id="tab-payments" class="tab-content-panel">
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Tanggal Bayar</th>
                            <th>No. Pembelian</th>
                            <th>Supplier</th>
                            <th>Metode Pembayaran</th>
                            <th>No. Referensi</th>
                            <th>Nominal Bayar</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($payments as $pay)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $pay->payment_date?->format('d/m/Y') }}</td>
                                <td style="font-weight: 600;">{{ $pay->purchase->purchase_number ?? '-' }}</td>
                                <td>{{ $pay->purchase->supplier->name ?? '-' }}</td>
                                <td>
                                    <span class="badge badge-info">{{ ucfirst($pay->payment_method) }}</span>
                                </td>
                                <td>{{ $pay->reference_number ?? '-' }}</td>
                                <td style="font-weight: 700; color: #991b1c;">
                                    Rp {{ number_format((float) $pay->amount, 0, ',', '.') }}
                                </td>
                                <td>{{ $pay->notes ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="text-align: center; padding: 30px; color: var(--text-muted);">
                                    Tidak ada pengeluaran kas pembayaran supplier pada periode {{ $periodLabel }}.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TAB 4: PERFORMA PRODUK -->
        <div id="tab-products" class="tab-content-panel">
            @if (count($topProducts) > 0)
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>SKU</th>
                                <th>Nama Produk</th>
                                <th>Total Terjual</th>
                                <th>Total Omzet</th>
                                <th>Total HPP Modal</th>
                                <th>Total Keuntungan</th>
                                <th>Margin</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($topProducts as $prod)
                                @php
                                    $pMargin = $prod['revenue'] > 0 ? ($prod['profit'] / $prod['revenue']) * 100 : 0;
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td style="font-weight: 600;">{{ $prod['sku'] }}</td>
                                    <td>{{ $prod['name'] }}</td>
                                    <td>{{ $prod['quantity'] }} unit</td>
                                    <td style="font-weight: 700;">Rp {{ number_format($prod['revenue'], 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($prod['cogs'], 0, ',', '.') }}</td>
                                    <td style="font-weight: 700; color: #166534;">
                                        Rp {{ number_format($prod['profit'], 0, ',', '.') }}
                                    </td>
                                    <td>
                                        <span class="badge badge-success">{{ number_format($pMargin, 1) }}%</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p style="text-align: center; padding: 30px; color: var(--text-muted);">
                    Belum ada data barang terjual pada periode {{ $periodLabel }}.
                </p>
            @endif
        </div>
    </div>
</div>

<script>
    function switchReportTab(event, tabId) {
        // Remove active class from tab buttons
        document.querySelectorAll('.nav-tab-item').forEach(button => {
            button.classList.remove('active');
        });

        // Hide all tab panels
        document.querySelectorAll('.tab-content-panel').forEach(panel => {
            panel.classList.remove('active');
        });

        // Activate clicked button and target panel
        event.currentTarget.classList.add('active');
        const targetPanel = document.getElementById(tabId);
        if (targetPanel) {
            targetPanel.classList.add('active');
        }
    }
</script>
@endsection

