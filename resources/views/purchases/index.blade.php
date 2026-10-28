
@extends('layout.app')

@section('title', 'Daftar Pembelian')
@section('header', 'Stok Masuk dari Supplier')

@section('content')
    <h1 class="page-title">Daftar Pembelian Supplier</h1>
    <p class="page-subtitle">
        Catat barang masuk, total tagihan, dan status utang supplier.
    </p>

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

    <div class="card">
        <form action="{{ route('purchases.index') }}" method="GET">
            <p>
                <label for="search">Cari nomor pembelian atau supplier</label>
                <input
                    class="form-input"
                    type="text"
                    id="search"
                    name="search"
                    value="{{ $search ?? '' }}"
                    placeholder="Contoh: PB-001 atau nama supplier"
                >
            </p>

            <button type="submit" class="btn">Cari</button>
            <a href="{{ route('purchases.index') }}" class="btn btn-secondary">
                Reset
            </a>
        </form>
    </div>

    <br>

    <div class="card">
        <p>
            <a href="{{ route('purchases.create') }}" class="btn">
                + Catat Pembelian Baru
            </a>
        </p>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>No. Pembelian</th>
                        <th>Supplier</th>
                        <th>Tanggal</th>
                        <th>Jatuh Tempo</th>
                        <th>Total Tagihan</th>
                        <th>Sudah Dibayar</th>
                        <th>Sisa Utang</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($purchases as $purchase)
                        <tr>
                            <td>{{ $purchases->firstItem() + $loop->index }}</td>
                            <td>{{ $purchase->purchase_number }}</td>
                            <td>{{ $purchase->supplier->name ?? '-' }}</td>
                            <td>
                                {{ $purchase->purchase_date?->format('d-m-Y') ?? '-' }}
                            </td>
                            <td>
                                {{ $purchase->due_date?->format('d-m-Y') ?? '-' }}
                            </td>
                            <td>
                                Rp {{ number_format((float) $purchase->total_amount, 0, ',', '.') }}
                            </td>
                            <td>
                                Rp {{ number_format((float) $purchase->paid_amount, 0, ',', '.') }}
                            </td>
                            <td>
                                Rp {{ number_format($purchase->remaining_amount, 0, ',', '.') }}
                            </td>
                            <td>
                                @if ($purchase->remaining_amount <= 0)
                                    <span class="badge badge-success">Lunas</span>
                                @elseif ((float) $purchase->paid_amount > 0)
                                    <span class="badge badge-warning">Belum Lunas</span>
                                @else
                                    <span class="badge badge-danger">Belum Dibayar</span>
                                @endif
                            </td>
                            <td>
                                <a
                                    href="{{ route('purchases.show', $purchase) }}"
                                    class="btn"
                                >
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10">
                                Belum ada transaksi pembelian supplier.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <br>

        {{ $purchases->links() }}
    </div>
@endsection
