
@extends('layout.app')

@section('title', 'Detail Supplier')
@section('header', 'Detail Supplier')

@section('content')
    <h1 class="page-title">Detail Supplier</h1>
    <p class="page-subtitle">
        Informasi pembelian, pembayaran, dan sisa utang supplier.
    </p>

    <p>
        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">
            &larr; Kembali ke Daftar Supplier
        </a>
    </p>

    {{-- Informasi Supplier --}}
    <div class="card">
        <h2>{{ $supplier->name }}</h2>

        <p><strong>Telepon:</strong> {{ $supplier->phone ?? '-' }}</p>
        <p><strong>Email:</strong> {{ $supplier->email ?? '-' }}</p>
        <p><strong>Alamat:</strong> {{ $supplier->address ?? '-' }}</p>
    </div>

    <br>

    {{-- Ringkasan Pembayaran --}}
    <div class="card">
        <h2>Ringkasan Pembayaran</h2>

        <table class="data-table">
            <tbody>
                <tr>
                    <th>Total Tagihan</th>
                    <td>
                        Rp {{ number_format($totalTagihan, 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <th>Total Dibayar</th>
                    <td>
                        Rp {{ number_format($totalDibayar, 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <th>Sisa Utang</th>
                    <td>
                        <strong>
                            Rp {{ number_format($sisaUtang, 0, ',', '.') }}
                        </strong>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <br>

    {{-- Riwayat Pembelian --}}
    <div class="card">
        <h2>Riwayat Pembelian</h2>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>No. Pembelian</th>
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
                            <td>
                                {{ $purchases->firstItem() + $loop->index }}
                            </td>

                            <td>{{ $purchase->purchase_number }}</td>

                            <td>
                                {{ $purchase->purchase_date
                                    ? $purchase->purchase_date->format('d-m-Y')
                                    : '-' }}
                            </td>

                            <td>
                                {{ $purchase->due_date
                                    ? $purchase->due_date->format('d-m-Y')
                                    : '-' }}
                            </td>

                            <td>
                                Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}
                            </td>

                            <td>
                                Rp {{ number_format($purchase->paid_amount, 0, ',', '.') }}
                            </td>

                            <td>
                                Rp {{ number_format($purchase->remaining_amount, 0, ',', '.') }}
                            </td>

                            <td>
                                @if ($purchase->remaining_amount <= 0)
                                    <span class="badge badge-success">
                                        Lunas
                                    </span>
                                @elseif (
                                    $purchase->due_date &&
                                    $purchase->due_date->format('Y-m-d') < now()->format('Y-m-d')
                                )
                                    <span class="badge badge-danger">
                                        Terlambat
                                    </span>
                                @elseif ($purchase->paid_amount > 0)
                                    <span class="badge badge-warning">
                                        Dibayar Sebagian
                                    </span>
                                @else
                                    <span class="badge badge-warning">
                                        Belum Dibayar
                                    </span>
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
                            <td colspan="9">
                                Belum ada riwayat pembelian dari supplier ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <br>

        {{ $purchases->links() }}
    </div>

    <br>

    {{-- Daftar Barang yang Dibeli --}}
    <div class="card">
        <h2>Barang yang Dibeli</h2>

        @php
            $purchaseItems = $purchases->getCollection()
                ->flatMap(function ($purchase) {
                    return $purchase->items;
                });
        @endphp

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Barang</th>
                        <th>No. Pembelian</th>
                        <th>Jumlah</th>
                        <th>Harga Satuan</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($purchaseItems as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>
                                {{ $item->product->name ?? 'Barang tidak ditemukan' }}
                            </td>

                            <td>
                                {{ $item->purchase->purchase_number ?? '-' }}
                            </td>

                            <td>{{ $item->quantity }}</td>

                            <td>
                                Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                            </td>

                            <td>
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                Belum ada barang yang tercatat pada pembelian supplier ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <br>

    {{-- Riwayat Pembayaran --}}
    <div class="card">
        <h2>Riwayat Pembayaran</h2>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Tanggal Pembayaran</th>
                        <th>No. Pembelian</th>
                        <th>Jumlah Dibayar</th>
                        <th>Metode</th>
                        <th>Referensi</th>
                        <th>Catatan</th>
                    </tr>
                </thead>

                <tbody>
                    @php
                        $payments = $purchases
                            ->getCollection()
                            ->flatMap(function ($purchase) {
                                return $purchase->payments->map(function ($payment) use ($purchase) {
                                    $payment->purchase_number = $purchase->purchase_number;
                                    return $payment;
                                });
                            })
                            ->sortByDesc('payment_date');
                    @endphp

                    @forelse ($payments as $payment)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>
                                {{ $payment->payment_date
                                    ? $payment->payment_date->format('d-m-Y')
                                    : '-' }}
                            </td>

                            <td>{{ $payment->purchase_number }}</td>

                            <td>
                                Rp {{ number_format($payment->amount, 0, ',', '.') }}
                            </td>

                            <td>{{ $payment->payment_method }}</td>

                            <td>{{ $payment->reference_number ?? '-' }}</td>

                            <td>{{ $payment->notes ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                Belum ada riwayat pembayaran.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection