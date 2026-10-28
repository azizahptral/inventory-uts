
@extends('layout.app')

@section('title', 'Tambah Supplier')
@section('header', 'Tambah Supplier')

@section('content')
    <h1 class="page-title">Tambah Supplier Baru</h1>
    <p class="page-subtitle">
        Masukkan informasi supplier atau pemasok barang.
    </p>

    @if ($errors->any())
        <div class="alert-error">
            <strong>Periksa kembali data berikut:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('error'))
        <div class="alert-error">{{ session('error') }}</div>
    @endif

    <div class="card">
        <form action="{{ route('suppliers.store') }}" method="POST">
            @csrf

            <p>
                <label for="name">Nama Supplier *</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    class="form-input"
                    value="{{ old('name') }}"
                    maxlength="255"
                    required
                >
            </p>

            <p>
                <label for="phone">Nomor Telepon</label>
                <input
                    type="text"
                    id="phone"
                    name="phone"
                    class="form-input"
                    value="{{ old('phone') }}"
                    maxlength="30"
                >
            </p>

            <p>
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-input"
                    value="{{ old('email') }}"
                    maxlength="255"
                >
            </p>

            <p>
                <label for="address">Alamat</label>
                <textarea
                    id="address"
                    name="address"
                    class="form-input"
                    rows="4"
                >{{ old('address') }}</textarea>
            </p>

            <p>
                <label for="notes">Catatan</label>
                <textarea
                    id="notes"
                    name="notes"
                    class="form-input"
                    rows="3"
                >{{ old('notes') }}</textarea>
            </p>

            <button type="submit" class="btn">
                Simpan Supplier
            </button>

            <a href="{{ route('suppliers.index') }}"
               class="btn btn-secondary">
                Batal
            </a>
        </form>
    </div>
@endsection
