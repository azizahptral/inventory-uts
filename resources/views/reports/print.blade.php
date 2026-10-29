<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Keuangan - Diesel Parts ({{ $periodLabel }})</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
            color: #1a1a1a;
            background: #f4f6f8;
            font-size: 12px;
            line-height: 1.4;
            padding: 20px;
        }

        .no-print-bar {
            max-width: 960px;
            margin: 0 auto 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #2b5433;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            border: none;
        }

        .btn-print-now {
            background: #a7df85;
            color: #102014;
        }

        .btn-back {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .sheet {
            max-width: 960px;
            margin: 0 auto;
            background: #ffffff;
            padding: 40px 45px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        }

        /* HEADER / KOP SURAT */
        .company-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px double #2b5433;
            padding-bottom: 16px;
            margin-bottom: 24px;
        }

        .company-brand h1 {
            font-size: 24px;
            font-weight: 800;
            color: #234328;
            letter-spacing: -0.5px;
            margin-bottom: 4px;
        }

        .company-brand p {
            color: #555555;
            font-size: 11px;
        }

        .report-meta {
            text-align: right;
            font-size: 11px;
            color: #444444;
        }

        .report-meta h2 {
            font-size: 16px;
            color: #1e3a24;
            margin-bottom: 4px;
            font-weight: 700;
        }

        .badge-period {
            display: inline-block;
            background: #eef5ef;
            color: #2b5433;
            padding: 3px 8px;
            border-radius: 4px;
            font-weight: bold;
            margin-top: 3px;
        }

        /* SECTION TITLES */
        .section-title {
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #2b5433;
            border-left: 4px solid #3d6f46;
            padding-left: 8px;
            margin: 24px 0 10px;
        }

        /* KPI SUMMARY TABLE */
        .kpi-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .kpi-table th,
        .kpi-table td {
            border: 1px solid #d2ded4;
            padding: 10px 12px;
            font-size: 11.5px;
        }

        .kpi-table th {
            background: #f1f7f2;
            color: #203f26;
            text-align: left;
            width: 35%;
        }

        .kpi-table td {
            font-weight: bold;
            color: #111111;
        }

        /* DATA TABLES */
        .doc-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 11px;
        }

        .doc-table th {
            background: #2b5433;
            color: white;
            padding: 8px 10px;
            text-align: left;
            border: 1px solid #2b5433;
            font-size: 10.5px;
            text-transform: uppercase;
        }

        .doc-table td {
            padding: 7px 10px;
            border: 1px solid #e0e0e0;
            vertical-align: middle;
        }

        .doc-table tbody tr:nth-child(even) {
            background: #fafdfa;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* SIGNATURE SECTION */
        .signature-grid {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            padding-top: 20px;
        }

        .signature-box {
            text-align: center;
            width: 200px;
        }

        .signature-box p {
            margin-bottom: 60px;
            color: #555555;
            font-size: 11px;
        }

        .signature-line {
            font-weight: bold;
            border-top: 1px solid #222222;
            padding-top: 4px;
            color: #111111;
        }

        /* PRINT STYLES */
        @media print {
            body {
                background: white;
                padding: 0;
            }

            .no-print-bar {
                display: none !important;
            }

            .sheet {
                box-shadow: none;
                border-radius: 0;
                padding: 15px 20px;
                max-width: 100%;
            }

            .doc-table th {
                background: #2b5433 !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .kpi-table th {
                background: #f1f7f2 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>

    <!-- TOP ACTION BAR (HIDDEN IN PRINT) -->
    <div class="no-print-bar">
        <div>
            <strong>Mode Cetak Laporan Keuangan</strong>
            <span style="opacity: 0.8; font-size: 12px; margin-left: 10px;">(Periode: {{ $periodLabel }})</span>
        </div>
        <div style="display: flex; gap: 8px;">
            <button onclick="window.print()" class="btn-action btn-print-now">
                🖨️ Cetak / Simpan PDF
            </button>
            <a href="{{ route('financial-reports.index', request()->all()) }}" class="btn-action btn-back">
                ↩ Kembali
            </a>
        </div>
    </div>

    <!-- PRINTABLE SHEET -->
    <div class="sheet">
        <!-- KOP LAPORAN -->
        <div class="company-header">
            <div class="company-brand">
                <h1>🌿 DIESEL PARTS</h1>
                <p>Spesialis Suku Cadang & Komponen Mesin Diesel</p>
                <p>Sistem Informasi Manajemen Persediaan & Keuangan</p>
            </div>
            <div class="report-meta">
                <h2>LAPORAN KEUANGAN RESMI</h2>
                <div class="badge-period">Periode: {{ $periodLabel }}</div>
                <p style="margin-top: 4px;">Tanggal Cetak: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB</p>
                <p>Dicetak Oleh: <strong>{{ auth()->user()->name ?? 'Administrator' }}</strong> ({{ strtoupper(auth()->user()->role ?? 'admin') }})</p>
            </div>
        </div>

        <!-- RINGKASAN EKSEKUTIF -->
        <div class="section-title">I. Ringkasan Kinerja Keuangan & Laba Rugi</div>
        <table class="kpi-table">
            <tr>
                <th>Total Pendapatan (Omzet Penjualan)</th>
                <td>Rp {{ number_format($totalRevenue, 0, ',', '.') }} ({{ $totalSalesCount }} Transaksi, {{ $totalItemsSold }} Unit)</td>
            </tr>
            <tr>
                <th>Estimasi HPP (Modal Pokok Barang Terjual)</th>
                <td>Rp {{ number_format($totalCogs, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Estimasi Laba Kotor</th>
                <td style="color: #166534;">
                    Rp {{ number_format($grossProfit, 0, ',', '.') }}
                    <span style="font-weight: normal; font-size: 11px; margin-left: 6px;">(Margin: {{ number_format($profitMargin, 1) }}%)</span>
                </td>
            </tr>
            <tr>
                <th>Arus Kas Masuk (Penerimaan Penjualan)</th>
                <td>Rp {{ number_format($totalCashInflow, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Arus Kas Keluar (Pembayaran Supplier)</th>
                <td style="color: #b91c1c;">Rp {{ number_format($totalCashOutflow, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Arus Kas Bersih (Net Cash Flow)</th>
                <td style="color: {{ $netCashFlow >= 0 ? '#166534' : '#b91c1c' }}; font-size: 13px;">
                    Rp {{ number_format($netCashFlow, 0, ',', '.') }}
                </td>
            </tr>
            <tr>
                <th>Total Pembelian Stok Masuk Periode Ini</th>
                <td>Rp {{ number_format($totalPurchaseInvoices, 0, ',', '.') }} ({{ $totalPurchasesCount }} Faktur Pembelian)</td>
            </tr>
            <tr>
                <th>Sisa Hutang Faktur Pembelian Periode Ini</th>
                <td style="color: #b45309;">Rp {{ number_format($periodDebtRemaining, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Total Akumulasi Seluruh Hutang Toko</th>
                <td style="color: #b45309;">Rp {{ number_format($allTimeDebt, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Estimasi Total Nilai Aset Stok Barang Saat Ini</th>
                <td>Rp {{ number_format($totalInventoryAsset, 0, ',', '.') }}</td>
            </tr>
        </table>

        <!-- RINCIAN PENJUALAN -->
        <div class="section-title">II. Rincian Transaksi Penjualan Barang</div>
        <table class="doc-table">
            <thead>
                <tr>
                    <th style="width: 30px;">No.</th>
                    <th>No. Transaksi</th>
                    <th>Tanggal</th>
                    <th>Kasir</th>
                    <th>Rincian Item</th>
                    <th class="text-right">Modal (HPP)</th>
                    <th class="text-right">Total Penjualan</th>
                    <th class="text-right">Laba Kotor</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sales as $sale)
                    @php
                        $saleCogs = $sale->items->reduce(fn($c, $i) => $c + (((float) ($i->product->purchase_price ?? 0)) * $i->quantity), 0);
                        $saleProfit = (float) $sale->total_amount - $saleCogs;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td style="font-weight: bold;">{{ $sale->sale_number }}</td>
                        <td>{{ $sale->sale_date?->format('d/m/Y') }}</td>
                        <td>{{ $sale->user->name ?? '-' }}</td>
                        <td>
                            @foreach ($sale->items as $item)
                                <div>- {{ $item->product->name ?? 'Produk' }} ({{ $item->quantity }}x)</div>
                            @endforeach
                        </td>
                        <td class="text-right">Rp {{ number_format($saleCogs, 0, ',', '.') }}</td>
                        <td class="text-right" style="font-weight: bold;">Rp {{ number_format((float) $sale->total_amount, 0, ',', '.') }}</td>
                        <td class="text-right" style="font-weight: bold; color: #166534;">Rp {{ number_format($saleProfit, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center" style="padding: 15px; color: #777;">
                            Tidak ada data penjualan pada periode ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr style="font-weight: bold; background: #f1f7f2;">
                    <td colspan="5" class="text-right">TOTAL KESELURUHAN:</td>
                    <td class="text-right">Rp {{ number_format($totalCogs, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
                    <td class="text-right" style="color: #166534;">Rp {{ number_format($grossProfit, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        <!-- RINCIAN PEMBELIAN STOK -->
        <div class="section-title">III. Rincian Pembelian Stok Masuk dari Supplier</div>
        <table class="doc-table">
            <thead>
                <tr>
                    <th style="width: 30px;">No.</th>
                    <th>No. Pembelian</th>
                    <th>Tanggal</th>
                    <th>Supplier</th>
                    <th>Jatuh Tempo</th>
                    <th class="text-right">Total Tagihan</th>
                    <th class="text-right">Terbayar</th>
                    <th class="text-right">Sisa Hutang</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($purchases as $p)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td style="font-weight: bold;">{{ $p->purchase_number }}</td>
                        <td>{{ $p->purchase_date?->format('d/m/Y') }}</td>
                        <td>{{ $p->supplier->name ?? '-' }}</td>
                        <td>{{ $p->due_date?->format('d/m/Y') ?? '-' }}</td>
                        <td class="text-right" style="font-weight: bold;">Rp {{ number_format((float) $p->total_amount, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format((float) $p->paid_amount, 0, ',', '.') }}</td>
                        <td class="text-right" style="font-weight: bold; color: {{ $p->remaining_amount > 0 ? '#b91c1c' : '#166534' }};">
                            Rp {{ number_format($p->remaining_amount, 0, ',', '.') }}
                        </td>
                        <td class="text-center">
                            @if ($p->remaining_amount <= 0)
                                Lunas
                            @elseif ((float) $p->paid_amount > 0)
                                Sebagian
                            @else
                                Belum Dibayar
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center" style="padding: 15px; color: #777;">
                            Tidak ada data pembelian pada periode ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr style="font-weight: bold; background: #f1f7f2;">
                    <td colspan="5" class="text-right">TOTAL KESELURUHAN:</td>
                    <td class="text-right">Rp {{ number_format($totalPurchaseInvoices, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($totalPurchaseInvoices - $periodDebtRemaining, 0, ',', '.') }}</td>
                    <td class="text-right" style="color: #b91c1c;">Rp {{ number_format($periodDebtRemaining, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

        <!-- TANDA TANGAN -->
        <div class="signature-grid">
            <div class="signature-box">
                <p>Dibuat Oleh,</p>
                <div class="signature-line">Petugas Keuangan / Staff</div>
            </div>

            <div class="signature-box">
                <p>Mengetahui & Menyetujui,</p>
                <div class="signature-line">Pimpinan / Direktur Diesel Parts</div>
            </div>
        </div>
    </div>

</body>
</html>

