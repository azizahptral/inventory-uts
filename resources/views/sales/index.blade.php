
@extends('layout.app')

@section('title', 'Data Penjualan')
@section('header', 'Data Penjualan')

@section('content')
<style>
    .sales-page { max-width: 1200px; margin: 0 auto; }
    .sales-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
    }
    .sales-heading h1 { margin: 0 0 7px; font-size: 27px; }
    .sales-muted { color: #64748b; font-size: 14px; }
    .sales-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 11px 16px;
        border: none;
        border-radius: 8px;
        background: #0d9488;
        color: white;
        text-decoration: none;
        cursor: pointer;
        font-size: 14px;
        white-space: nowrap;
    }
    .sales-btn:hover { background: #0f766e; }
    .sales-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 13px;
        padding: 24px;
        box-shadow: 0 3px 12px rgba(15,23,42,.035);
    }
    .sales-card-title {
        margin: 0 0 5px;
        font-size: 17px;
    }
    .sales-table-wrap { overflow-x: auto; margin-top: 20px; }
    .sales-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 850px;
    }
    .sales-table th {
        padding: 14px 13px;
        background: #f8fafc;
        color: #64748b;
        font-size: 12px;
        font-weight: 700;
        text-align: left;
        text-transform: uppercase;
        letter-spacing: .3px;
        white-space: nowrap;
    }
    .sales-table td {
        padding: 15px 13px;
        border-bottom: 1px solid #edf1f6;
        font-size: 14px;
        vertical-align: middle;
    }
    .sales-table tbody tr:hover { background: #f8fbfd; }
    .sales-number { font-weight: 600; color: #0f172a; }
    .sales-money { font-weight: 600; white-space: nowrap; }
    .sales-status {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 20px;
        background: #dcfce7;
        color: #166534;
        font-size: 12px;
        font-weight: 600;
    }
    .sales-pagination { margin-top: 22px; }
    .sales-empty {
        padding: 45px 20px;
        text-align: center;
        color: #64748b;
    }
    .sales-empty strong {
        display: block;
        color: #334155;
        font-size: 16px;
        margin-bottom: 7px;
    }
    @media(max-width: 600px) {
        .sales-card { padding: 16px; }
        .sales-heading h1 { font-size: 23px; }
    }
</style>

<div class="sales-page">
    <div class="sales-heading">
        <div>
            <h1>Data Penjualan</h1>
            <div class="sales-muted">
                Kelola transaksi, lihat pembayaran, dan cetak resi penjualan.
            </div>
        </div>

        <a href="{{ route('sales.create') }}" class="sales-btn">
            + Transaksi Baru
        </a>
    </div>

    <section class="sales-card">
        <h2 class="sales-card-title">Riwayat Transaksi</h2>
        <div class="sales-muted">
            Daftar transaksi penjualan yang tersimpan di sistem.
        </div>

        @if($sales->count())
            <div class="sales-table-wrap">
                <table class="sales-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nomor Transaksi</th>
                            <th>Tanggal</th>
                            <th>Kasir</th>
                            <th>Total Belanja</th>
                            <th>Pembayaran</th>
                            <th>Kembalian</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($sales as $sale)
                            <tr>
                                <td>{{ $sales->firstItem() + $loop->index }}</td>

                                <td>
                                    <div class="sales-number">
                                        {{ $sale->sale_number }}
                                    </div>
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y') }}
                                </td>

                                <td>{{ $sale->user->name ?? '-' }}</td>

                                <td class="sales-money">
                                    Rp {{ number_format($sale->total_amount, 0, ',', '.') }}
                                </td>

                                <td>
                                    Rp {{ number_format($sale->paid_amount, 0, ',', '.') }}
                                </td>

                                <td>
                                    Rp {{ number_format($sale->change_amount, 0, ',', '.') }}
                                </td>

                                <td>
                                    <span class="sales-status">
                                        {{ ucfirst($sale->status) }}
                                    </span>
                                </td>

                                <td>
                                    <a
                                        href="{{ route('sales.show', $sale) }}"
                                        class="sales-btn"
                                    >
                                        Detail / Resi
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($sales->hasPages())
                <div class="sales-pagination">
                    {{ $sales->links() }}
                </div>
            @endif
        @else
            <div class="sales-empty">
                <strong>Belum ada transaksi penjualan</strong>
                <div>Transaksi yang kamu simpan akan muncul di sini.</div>
                <div style="margin-top:18px">
                    <a href="{{ route('sales.create') }}" class="sales-btn">
                        Buat Penjualan Pertama
                    </a>
                </div>
            </div>
        @endif
    </section>
</div>
@endsection
