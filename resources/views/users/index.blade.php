@extends('layout.app')

@section('title', 'Kelola Pengguna - Diesel Parts')
@section('heading', 'Kelola Pengguna')

@section('content')
<style>
    .users-page {
        max-width: 1250px;
        margin: 0 auto;
    }

    .users-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
    }

    .users-header h1 {
        margin: 0 0 6px;
        font-size: 26px;
        font-weight: 800;
        color: var(--text-main);
    }

    .users-header p {
        margin: 0;
        color: var(--text-secondary);
        font-size: 14px;
    }

    .filter-card {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 20px 24px;
        box-shadow: var(--shadow-sm);
        margin-bottom: 24px;
    }

    .filter-form-row {
        display: flex;
        gap: 12px;
        align-items: center;
        flex-wrap: wrap;
    }

    .user-profile-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .user-avatar-circle {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: #3e6d47;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
        flex-shrink: 0;
    }

    .user-name-text {
        font-weight: 700;
        color: var(--text-main);
        font-size: 14px;
    }

    .user-email-text {
        color: var(--text-muted);
        font-size: 12.5px;
    }

    .role-tag {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11.5px;
        font-weight: 700;
        letter-spacing: 0.3px;
    }

    .role-tag.admin {
        background: #eaf3ed;
        color: #244b2a;
        border: 1px solid #bde0c4;
    }

    .role-tag.staff {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #cbd5e1;
    }

    .current-user-badge {
        display: inline-block;
        background: #dbeafe;
        color: #1e40af;
        font-size: 10.5px;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 10px;
        margin-left: 6px;
    }
</style>

<div class="users-page">
    <div class="users-header">
        <div>
            <h1>👥 Kelola Akun Pengguna</h1>
            <p>Kelola daftar akun yang memiliki akses login ke sistem dan atur hak aksesnya (Admin / Staff).</p>
        </div>

        <div>
            <a href="{{ route('users.create') }}" class="btn">
                <span>+ Tambah Pengguna Baru</span>
            </a>
        </div>
    </div>

    <!-- PENCARIAN & FILTER ROLE -->
    <div class="filter-card">
        <form action="{{ route('users.index') }}" method="GET" class="filter-form-row">
            <div style="flex: 1; min-width: 240px;">
                <input
                    type="text"
                    name="search"
                    class="form-input"
                    value="{{ $search ?? '' }}"
                    placeholder="Cari berdasarkan nama atau alamat email..."
                >
            </div>

            <div style="min-width: 160px;">
                <select name="role" class="form-input">
                    <option value="">Semua Peran</option>
                    <option value="admin" @selected(($role ?? '') === 'admin')>🛡️ Administrator</option>
                    <option value="staff" @selected(($role ?? '') === 'staff')>👤 Staff</option>
                </select>
            </div>

            <button type="submit" class="btn">
                <span>🔍 Filter</span>
            </button>

            @if (!empty($search) || !empty($role))
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    <span>Reset</span>
                </a>
            @endif
        </form>
    </div>

    <!-- TABEL USERS -->
    <div class="card" style="padding: 0; overflow: hidden;">
        <div class="table-wrap" style="border: none;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">No.</th>
                        <th>Pengguna</th>
                        <th style="width: 160px; text-align: center;">Peran (Hak Akses)</th>
                        <th>Terdaftar Pada</th>
                        <th style="width: 150px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ $users->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="user-profile-cell">
                                    <div class="user-avatar-circle" style="background: {{ $user->role === 'admin' ? '#2f5936' : '#64748b' }};">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="user-name-text">
                                            {{ $user->name }}
                                            @if (auth()->id() === $user->id)
                                                <span class="current-user-badge">Anda</span>
                                            @endif
                                        </div>
                                        <div class="user-email-text">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="text-align: center;">
                                @if ($user->role === 'admin')
                                    <span class="role-tag admin">🛡️ Administrator</span>
                                @else
                                    <span class="role-tag staff">👤 Staff</span>
                                @endif
                            </td>
                            <td style="color: var(--text-secondary); font-size: 13px;">
                                {{ $user->created_at?->translatedFormat('d F Y') ?? '-' }}
                            </td>
                            <td>
                                <div style="display: flex; gap: 6px; justify-content: center;">
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-secondary" style="padding: 6px 12px; font-size: 12px;">
                                        <span>✏️ Edit</span>
                                    </a>

                                    @if (auth()->id() !== $user->id)
                                        <form action="{{ route('users.destroy', $user) }}" method="POST" style="display: inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ addslashes($user->name) }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" style="padding: 6px 10px; font-size: 12px;" title="Hapus Pengguna">
                                                <span>🗑️</span>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 40px; color: var(--text-muted);">
                                Tidak ada akun pengguna ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
            <div style="padding: 16px 24px; border-top: 1px solid var(--border-light);">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

