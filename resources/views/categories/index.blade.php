@extends('layout.app')

@section('title', 'Kategori Produk - Diesel Parts')
@section('heading', 'Kategori Produk')

@section('content')
<style>
    .category-page {
        max-width: 1250px;
        margin: 0 auto;
    }

    .category-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
    }

    .category-header h1 {
        margin: 0 0 6px;
        font-size: 26px;
        font-weight: 800;
        color: var(--text-main);
    }

    .category-header p {
        margin: 0;
        color: var(--text-secondary);
        font-size: 14px;
    }

    .search-card {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 20px 24px;
        box-shadow: var(--shadow-sm);
        margin-bottom: 24px;
    }

    .search-form-row {
        display: flex;
        gap: 12px;
        align-items: center;
        flex-wrap: wrap;
    }

    .search-input-wrap {
        flex: 1;
        min-width: 260px;
    }

    .category-code-badge {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 11.5px;
        font-weight: 700;
        letter-spacing: 0.5px;
        background: #eaf3ed;
        color: var(--matcha-primary-dark);
        border: 1px solid #cce2d1;
    }

    .count-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #f1f7f2;
        color: #2b5433;
        font-weight: 700;
        font-size: 12px;
        padding: 4px 10px;
        border-radius: 20px;
        border: 1px solid #d4e6d7;
    }

    .action-btn-group {
        display: flex;
        gap: 6px;
        align-items: center;
    }

    .btn-action-small {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 6px 11px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-action-view {
        background: #edf4ee;
        color: var(--matcha-primary-dark);
        border: 1px solid var(--border-color);
    }

    .btn-action-view:hover {
        background: #e0eee2;
    }

    .btn-action-edit {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }

    .btn-action-edit:hover {
        background: #fde68a;
    }

    .btn-action-delete {
        background: #fee2e2;
        color: #b91c1c;
        border: 1px solid #fca5a5;
    }

    .btn-action-delete:hover {
        background: #fca5a5;
    }

    .staff-notice {
        font-size: 12px;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 6px;
        background: #f3f7f4;
        padding: 6px 12px;
        border-radius: 6px;
        border: 1px dashed var(--border-color);
    }
</style>

<div class="category-page">
    <!-- HEADER -->
    <div class="category-header">
        <div>
            <h1>🏷️ Kategori Produk</h1>
            <p>Kelola pengelompokan suku cadang mesin diesel untuk memudahkan pencarian barang.</p>
        </div>

        @if (auth()->user()->role === 'admin')
            <div>
                <a href="{{ route('categories.create') }}" class="btn">
                    <span>+ Tambah Kategori Baru</span>
                </a>
            </div>
        @else
            <div class="staff-notice">
                <span>👁️</span>
                <span>Mode Lihat Saja (Staff)</span>
            </div>
        @endif
    </div>

    <!-- PENCARIAN -->
    <div class="search-card">
        <form action="{{ route('categories.index') }}" method="GET" class="search-form-row">
            <div class="search-input-wrap">
                <input
                    type="text"
                    name="search"
                    class="form-input"
                    value="{{ $search ?? '' }}"
                    placeholder="Cari berdasarkan nama kategori, kode, atau deskripsi..."
                    autofocus
                >
            </div>

            <button type="submit" class="btn">
                <span>🔍 Cari</span>
            </button>

            @if (!empty($search))
                <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                    <span>Reset</span>
                </a>
            @endif
        </form>

        @if (!empty($search))
            <div style="margin-top: 12px; font-size: 13px; color: var(--text-secondary);">
                Menampilkan hasil pencarian untuk: <strong>"{{ $search }}"</strong>
                ({{ $categories->total() }} kategori ditemukan)
            </div>
        @endif
    </div>

    <!-- TABEL KATEGORI -->
    <div class="card" style="padding: 0; overflow: hidden;">
        <div class="table-wrap" style="border: none;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">No.</th>
                        <th style="width: 110px;">Kode</th>
                        <th>Nama Kategori</th>
                        <th>Deskripsi</th>
                        <th style="width: 140px; text-align: center;">Jumlah Produk</th>
                        <th style="width: 130px;">Dibuat</th>
                        <th style="width: 160px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td>{{ $categories->firstItem() + $loop->index }}</td>
                            <td>
                                @if ($category->code)
                                    <span class="category-code-badge">{{ $category->code }}</span>
                                @else
                                    <span style="color: var(--text-muted); font-size: 12px;">-</span>
                                @endif
                            </td>
                            <td>
                                <strong style="color: var(--text-main); font-size: 14px;">{{ $category->name }}</strong>
                            </td>
                            <td style="color: var(--text-secondary); font-size: 13px;">
                                {{ $category->description ?: 'Tidak ada deskripsi' }}
                            </td>
                            <td style="text-align: center;">
                                <span class="count-pill">
                                    <span>📦</span>
                                    <span>{{ $category->products_count }} Produk</span>
                                </span>
                            </td>
                            <td style="font-size: 12.5px; color: var(--text-muted);">
                                {{ $category->created_at?->format('d/m/Y') ?? '-' }}
                            </td>
                            <td>
                                <div class="action-btn-group" style="justify-content: center;">
                                    <a href="{{ route('categories.show', $category) }}" class="btn-action-small btn-action-view" title="Lihat Produk">
                                        <span>👁️ Lihat</span>
                                    </a>

                                    {{-- ADMIN SAJA YANG BISA EDIT & HAPUS --}}
                                    @if (auth()->user()->role === 'admin')
                                        <a href="{{ route('categories.edit', $category) }}" class="btn-action-small btn-action-edit" title="Edit Kategori">
                                            <span>✏️ Edit</span>
                                        </a>

                                        <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display: inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori {{ addslashes($category->name) }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action-small btn-action-delete" title="Hapus Kategori">
                                                <span>🗑️</span>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px; color: var(--text-muted);">
                                <div style="font-size: 32px; margin-bottom: 8px;">🏷️</div>
                                <strong>Tidak ada kategori ditemukan</strong>
                                <p style="font-size: 13px; margin-top: 4px;">
                                    @if (!empty($search))
                                        Tidak ada kategori yang cocok dengan kata kunci pencarian "{{ $search }}".
                                    @else
                                        Belum ada kategori yang terdaftar.
                                    @endif
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($categories->hasPages())
            <div style="padding: 16px 24px; border-top: 1px solid var(--border-light);">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

