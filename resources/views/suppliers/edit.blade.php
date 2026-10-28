
@extends('layout.app')

@section('title', 'Edit Supplier')
@section('header', 'Edit Supplier')

@section('content')
    <h1 class="page-title">Edit Supplier</h1>
    <p class="page-subtitle">
        Perbarui informasi supplier Diesel Parts.
    </p>

    @if ($errors->any())
        <div class="alert-error">
            <strong>Data belum valid:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <form
            action="{{ route('suppliers.update', $supplier) }}"
            method="POST"
        >
            @csrf
            @method('PUT')

            <p>
                <label for="name">Nama Supplier *</label>
                <input
                    class="form-input"
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $supplier->name) }}"
                    maxlength="255"
                    required
                >
            </p>

            <p>
                <label for="phone">Nomor Telepon</label>
                <input
                    class="form-input"
                    type="text"
                    id="phone"
                    name="phone"
                    value="{{ old('phone', $supplier->phone) }}"
                    maxlength="30"
                >
            </p>

            <p>
                <label for="email">Email</label>
                <input
                    class="form-input"
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email', $supplier->email) }}"
                    maxlength="255"
                >
            </p>

            <p>
                <label for="address">Alamat</label>
                <textarea
                    class="form-input"
                    id="address"
                    name="address"
                    rows="4"
                >{{ old('address', $supplier->address) }}</textarea>
            </p>

            <button type="submit" class="btn">
                Simpan Perubahan
            </button>

            <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">
                Batal
            </a>
        </form>
    </div>
@endsection
