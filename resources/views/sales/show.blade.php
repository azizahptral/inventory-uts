
@extends('layout.app')

@section('title', 'Detail Penjualan')
@section('header', 'Detail Penjualan')

@section('content')
<style>
    .detail-page { max-width: 1100px; margin: auto; }
    .detail-heading {
        display: flex; justify-content: space-between;
        align-items: center; gap: 15px; flex-wrap: wrap;
        margin-bottom: 24px;
    }
    .detail-heading h1 { margin: 0 0 6px; font-size: 27px; }
    .detail-muted { color: #64748b; font-size: 14px; }
    .detail-actions { display: flex; gap: 9px; flex-wrap: wrap; }
    .detail-btn {
        display: inline-flex; align-items: center; justify-content: center;
        border: 0; border-radius: 8px; padding: 11px 16px;
        background: #0d9488; color: white; text-decoration: none;
        cursor: pointer; font-size: 14px;
    }
    .detail-btn:hover { background: #0f766e; }
    .detail-btn-light { background: #e8eef5; color: #334155; }
    .detail-btn-light:hover { background: #dbe4ee; }
    .detail-card {
        background: white; border: 1px solid #e2e8f0;
        border-radius: 13px; padding: 24px; margin-bottom: 20px;
        box-shadow: 0 3px 12px rgba(15,23,42,.035);
    }
    .detail-card h3 { margin: 0 0 20px; font-size: 17px; }
    .detail-info {
        display: grid; grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
    }
    .detail-info-label {
        display: block; color: #64748b; font-size: 12px;
        margin-bottom: 6px;
    }
    .detail-info-value { font-size: 14px; font-weight: 600; overflow-wrap: anywhere; }
    .detail-status {
        display: inline-block; padding: 5px 10px; border-radius: 20px;
        color: #166534; background: #dcfce7; font-size: 12px;
    }
    .detail-table-wrap { overflow-x: auto; }
    .detail-table { width: 100%; border-collapse: collapse; min-width: 600px; }
    .detail-table th {
        text-align: left; padding: 13px 12px; background: #f8fafc;
        color: #64748b; font-size: 12px; text-transform: uppercase;
    }
    .detail-table td {
        padding: 14px 12px; border-bottom: 1px solid #edf1f6;
        font-size: 14px;
    }
    .detail-right { text-align: right !important; }
    .detail-summary { max-width: 420px; margin: 22px 0 0 auto; }
    .detail-line {
        display: flex; justify-content: space-between; gap: 15px;
        padding: 9px 0; font-size: 14px;
    }
    .detail-grand {
        border-top: 1px solid #e2e8f0; margin-top: 5px; padding-top: 17px;
        font-size: 21px; font-weight: 700; color: #0f766e;
    }
    .detail-footer {
        text-align: center; padding: 20px 0 5px;
        color: #64748b; font-size: 13px;
    }
    @media(max-width: 600px) {
        .detail-card { padding: 17px; }
        .detail-info { grid-template-columns: 1fr; gap: 15px; }
    }
    @media print {
        @page { margin: 12mm; }
        body { background: white !important; color: black !important; }
        .sidebar, .topbar, .detail-actions, .alert-success, .alert-error {
            display: none !important;
        }
        .main { margin: 0 !important; min-height: auto !important; }
        .content { padding: 0 !important; }
        .detail-page { max-width: none !important; }
        .detail-heading { margin-bottom: 15px; }
        .detail-heading h1 { font-size: 22px; }
        .detail-card {
            box-shadow: none !important; border: 1px solid #ddd !important;
            padding: 15px !important; break-inside: avoid;
        }
        .detail-table th { background: #f1f5f9 !important; }
        .detail-table th, .detail-table td {
            border-bottom: 1px solid #ddd !important;
        }
        .detail-footer { color: black !important; }
    }
</style>

<div class="detail-page">
    <div class="detail-heading">
        <div>
            <h1>Detail Penjualan</h1>
            <div class="detail-muted">
                Nomor transaksi: {{ $sale->sale_number }}
            </div>
        </div>

        <div class="detail-actions">
            <a href="{{ route('sales.index') }}" class="detail-btn detail-btn-light">
                Kembali
            </a>
            <button type="button" onclick="window.print()" class="detail-btn">
                Cetak Resi
            </button>
        </div>
    </div>

    <section class="detail-card">
        <h3>Informasi Transaksi</h3>
        <div class="detail-info">
            <div>
                <span class="detail-info-label">Nomor Transaksi</span>
                <div class="detail-info-value">{{ $sale->sale_number }}</div>
            </div>

            <div>
                <span class="detail-info-label">Tanggal Penjualan</span>
                <div class="detail-info-value">
                    {{ \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y') }}
                </div>
            </div>

            <div>
                <span class="detail-info-label">Kasir</span>
                <div class="detail-info-value">{{ $sale->user->name ?? '-' }}</div>
            </div>

            <div>
                <span class="detail-info-label">Status</span>
                <div>
                    <span class="detail-status">{{ ucfirst($sale->status) }}</span>
                </div>
            </div>
        </div>
    </section>

    <section class="detail-card">
        <h3>Barang yang Terjual</h3>

        <div class="detail-table-wrap">
            <table class="detail-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Produk</th>
                        <th>SKU</th>
                        <th class="detail-right">Harga Satuan</th>
                        <th class="detail-right">Jumlah</th>
                        <th class="detail-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sale->items as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->product->name ?? 'Produk' }}</td>
                            <td>{{ $item->product->sku ?? '-' }}</td>
                            <td class="detail-right">
                                Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                            </td>
                            <td class="detail-right">{{ $item->quantity }}</td>
                            <td class="detail-right">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="detail-summary">
            <div class="detail-line">
                <span>Total Belanja</span>
                <strong>Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</strong>
            </div>
            <div class="detail-line">
                <span>Uang Dibayarkan</span>
                <span>Rp {{ number_format($sale->paid_amount, 0, ',', '.') }}</span>
            </div>
            <div class="detail-line detail-grand">
                <span>Kembalian</span>
                <span>Rp {{ number_format($sale->change_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        @if($sale->notes)
            <div style="margin-top:22px;font-size:14px">
                <strong>Catatan:</strong> {{ $sale->notes }}
            </div>
        @endif

        <div class="detail-footer">
            <strong>Terima kasih telah berbelanja di Diesel Parts.</strong>
            <div>Simpan resi ini sebagai bukti pembayaran.</div>
        </div>
    </section>
</div>
@endsection
