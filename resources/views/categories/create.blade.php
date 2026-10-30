@extends('layout.app')

@section('title', 'Tambah Kategori - Diesel Parts')
@section('heading', 'Tambah Kategori')

@section('content')
<style>
    .form-container {
        max-width: 680px;
        margin: 0 auto;
    }

    .form-card {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 30px;
        box-shadow: var(--shadow-sm);
    }

    .form-title {
        font-size: 20px;
        font-weight: 800;
        color: var(--text-main);
        margin-bottom: 6px;
    }

    .form-subtitle {
        color: var(--text-secondary);
        font-size: 13.5px;
        margin-bottom: 24px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        align-items: center;
        margin-top: 28px;
        padding-top: 20px;
        border-top: 1px solid var(--border-light);
    }
</style>

<div class="form-container">
    <div class="form-card">
        <h2 class="form-title">Tambah Kategori Baru</h2>
        <p class="form-subtitle">Buat kelompok kategori baru untuk mengorganisir produk dan suku cadang.</p>

        @if ($errors->any())
            <div class="alert-error">
                <strong>Periksa kembali kesalahan berikut:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('categories.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">Nama Kategori <span style="color: #e11d48;">*</span></label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    class="form-input"
                    value="{{ old('name') }}"
                    placeholder="Contoh: Filter & Penyaring, Oli Mesin, Sistem Rem..."
                    required
                    autofocus
                >
            </div>

            <div class="form-group">
                <label for="code">Kode Kategori (Opsional)</label>
                <input
                    type="text"
                    id="code"
                    name="code"
                    class="form-input"
                    value="{{ old('code') }}"
                    placeholder="Contoh: FLT, OIL, BRK, RAD"
                    maxlength="50"
                >
                <small style="color: var(--text-muted); font-size: 12px; margin-top: 4px; display: block;">
                    Singkatan atau kode acuan kategori untuk identifikasi cepat.
                </small>
            </div>

            <div class="form-group">
                <label for="description">Deskripsi Kategori (Opsional)</label>
                <textarea
                    id="description"
                    name="description"
                    class="form-input"
                    rows="4"
                    placeholder="Tuliskan keterangan jenis produk apa saja yang termasuk dalam kategori ini..."
                >{{ old('description') }}</textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn">
                    <span>💾 Simpan Kategori</span>
                </button>
                <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                    <span>Batal</span>
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

