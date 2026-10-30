@extends('layout.app')

@section('title', 'Edit Kategori - Diesel Parts')
@section('heading', 'Edit Kategori')

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
        <h2 class="form-title">Edit Kategori: {{ $category->name }}</h2>
        <p class="form-subtitle">Perbarui informasi nama, kode, atau keterangan kategori.</p>

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

        <form action="{{ route('categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Nama Kategori <span style="color: #e11d48;">*</span></label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    class="form-input"
                    value="{{ old('name', $category->name) }}"
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
                    value="{{ old('code', $category->code) }}"
                    maxlength="50"
                >
            </div>

            <div class="form-group">
                <label for="description">Deskripsi Kategori (Opsional)</label>
                <textarea
                    id="description"
                    name="description"
                    class="form-input"
                    rows="4"
                >{{ old('description', $category->description) }}</textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn">
                    <span>💾 Simpan Perubahan</span>
                </button>
                <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                    <span>Batal</span>
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

