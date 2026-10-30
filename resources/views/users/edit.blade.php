@extends('layout.app')

@section('title', 'Edit Pengguna: ' . $user->name . ' - Diesel Parts')
@section('heading', 'Edit Pengguna')

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
        padding: 32px;
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

    .role-selection-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
        margin-top: 6px;
    }

    .role-option-card {
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        padding: 14px;
        cursor: pointer;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        transition: all 0.2s;
        background: #fbfdfb;
    }

    .role-option-card:hover {
        border-color: var(--matcha-primary);
        background: #f4f8f5;
    }

    .role-option-card input[type="radio"] {
        margin-top: 3px;
        accent-color: var(--matcha-primary);
    }

    .password-helper-box {
        background: #f4f8f5;
        border: 1px dashed var(--border-color);
        border-radius: var(--radius-md);
        padding: 16px;
        margin-top: 10px;
    }
</style>

<div class="form-container">
    <div class="form-card">
        <h2 class="form-title">Edit Akun: {{ $user->name }}</h2>
        <p class="form-subtitle">Perbarui data nama, email, peran hak akses, atau ubah kata sandi.</p>

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

        <form action="{{ route('users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Nama Lengkap Pengguna <span style="color: #e11d48;">*</span></label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    class="form-input"
                    value="{{ old('name', $user->name) }}"
                    required
                    autofocus
                >
            </div>

            <div class="form-group">
                <label for="email">Alamat Email <span style="color: #e11d48;">*</span></label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-input"
                    value="{{ old('email', $user->email) }}"
                    required
                >
            </div>

            <div class="form-group">
                <label>Peran & Hak Akses <span style="color: #e11d48;">*</span></label>
                <div class="role-selection-grid">
                    <label class="role-option-card">
                        <input type="radio" name="role" value="staff" @checked(old('role', $user->role) === 'staff') required>
                        <div>
                            <strong style="color: var(--text-main); font-size: 14px;">👤 Staff</strong>
                            <p style="font-size: 12px; color: var(--text-muted); margin-top: 3px;">
                                Mengelola transaksi kasir, melihat produk, dan melihat kategori.
                            </p>
                        </div>
                    </label>

                    <label class="role-option-card">
                        <input type="radio" name="role" value="admin" @checked(old('role', $user->role) === 'admin') required>
                        <div>
                            <strong style="color: var(--matcha-primary-dark); font-size: 14px;">🛡️ Administrator</strong>
                            <p style="font-size: 12px; color: var(--text-muted); margin-top: 3px;">
                                Akses penuh seluruh sistem, laporan keuangan, dan kelola pengguna.
                            </p>
                        </div>
                    </label>
                </div>
            </div>

            <div class="password-helper-box">
                <label style="margin-bottom: 4px;">Ubah Kata Sandi (Opsional)</label>
                <p style="font-size: 12.5px; color: var(--text-muted); margin-bottom: 14px;">
                    Kosongkan kolom kata sandi jika tidak ingin mengganti password akun ini.
                </p>

                <div class="form-group">
                    <label for="password">Kata Sandi Baru</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-input"
                        placeholder="Minimal 6 karakter"
                    >
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label for="password_confirmation">Konfirmasi Kata Sandi Baru</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="form-input"
                        placeholder="Ulangi kata sandi baru"
                    >
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn">
                    <span>💾 Simpan Perubahan</span>
                </button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    <span>Batal</span>
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

