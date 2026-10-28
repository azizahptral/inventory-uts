@extends('layout.app')

@section('title', 'Detail Pembelian')
@section('header', 'Detail Pembelian Supplier')

@section('content')
    <h1 class="page-title">Detail Pembelian</h1>
    <p class="page-subtitle">
        Informasi barang, tagihan, dan pembayaran supplier.
    </p>

    <div class="card">
        <p><strong>No. Pembelian:</strong> {{ $purchase->purchase_number }}</p>
        <p><strong>Supplier:</strong> {{ $purchase->supplier->name ?? '-' }}</p>
        <p>
            <strong>Tanggal Pembelian:</strong>
            {{ $purchase->purchase_date?->format('d-m-Y') ?? '-' }}
        </p>
        <p>
            <strong>Jatuh Tempo:</strong>
            {{ $purchase->due_date?->format('d-m-Y') ?? 'Belum ditentukan' }}
        </p>
    </div>

    <br>

    <div class="card">
        <h3>Barang yang Dibeli</h3>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Harga Satuan</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($purchase->items as $item)
                        <tr>
                            <td>{{ $item->product->name ?? '-' }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>Rp {{ number_format((float) $item->unit_price, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format((float) $item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">Belum ada barang.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <br>

    <div class="card">
        <h3>Ringkasan Utang</h3>

        <p>
            <strong>Total Tagihan:</strong>
            Rp {{ number_format((float) $purchase->total_amount, 0, ',', '.') }}
        </p>
        <p>
            <strong>Sudah Dibayar:</strong>
            Rp {{ number_format((float) $purchase->paid_amount, 0, ',', '.') }}
        </p>
        <p>
            <strong>Sisa Utang:</strong>
            Rp {{ number_format($purchase->remaining_amount, 0, ',', '.') }}
        </p>
        <p>
            <strong>Status:</strong> {{ $purchase->payment_status }}
        </p>
    </div>

    <br>

    <div class="card">
        <h3>Riwayat Pembayaran</h3>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jumlah</th>
                        <th>Metode</th>
                        <th>Referensi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($purchase->payments->sortByDesc('payment_date') as $payment)
                        <tr>
                            <td>{{ $payment->payment_date->format('d-m-Y') }}</td>
                            <td>Rp {{ number_format((float) $payment->amount, 0, ',', '.') }}</td>
                            <td>{{ ucfirst($payment->payment_method) }}</td>
                            <td>{{ $payment->reference_number ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">Belum ada pembayaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($purchase->remaining_amount > 0)
        <br>

        <div class="card">
            <h3>Catat Pembayaran</h3>

            @if ($errors->any())
                <div class="alert-error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form
                action="{{ route('purchases.payments.store', $purchase) }}"
                method="POST"
            >
                @csrf

                <p>
                    <label for="payment_date">Tanggal Pembayaran</label>
                    <input
                        class="form-input"
                        type="date"
                        id="payment_date"
                        name="payment_date"
                        value="{{ old('payment_date', now()->toDateString()) }}"
                        required
                    >
                </p>

                <p>
                    <label for="amount">Jumlah Pembayaran (Rp)</label>
                    <input
                        class="form-input"
                        type="number"
                        id="amount"
                        name="amount"
                        min="0.01"
                        max="{{ $purchase->remaining_amount }}"
                        step="0.01"
                        value="{{ old('amount') }}"
                        required
                    >
                    <small>
                        Sisa utang: Rp {{ number_format($purchase->remaining_amount, 0, ',', '.') }}
                    </small>
                </p>

                <p>
                    <label for="payment_method">Metode Pembayaran</label>
                    <select
                        class="form-input"
                        id="payment_method"
                        name="payment_method"
                        required
                    >
                        <option value="cash">Tunai</option>
                        <option value="transfer">Transfer</option>
                        <option value="other">Lainnya</option>
                    </select>
                </p>

                <p>
                    <label for="reference_number">Nomor Referensi</label>
                    <input
                        class="form-input"
                        type="text"
                        id="reference_number"
                        name="reference_number"
                        value="{{ old('reference_number') }}"
                    >
                </p>

                <p>
                    <label for="notes">Catatan</label>
                    <textarea
                        class="form-input"
                        id="notes"
                        name="notes"
                        rows="3"
                    >{{ old('notes') }}</textarea>
                </p>

                <button type="submit" class="btn">
                    Simpan Pembayaran
                </button>
            </form>
        </div>
    @else
        <div class="alert-success">
            Tagihan ini sudah lunas.
        </div>
    @endif

    <br>

    <a href="{{ route('purchases.index') }}" class="btn btn-secondary">
        Kembali ke Daftar Pembelian
    </a>
@endsection
