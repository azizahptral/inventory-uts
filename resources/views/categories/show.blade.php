@extends('layout.app')

@section('title', 'Detail Kategori: ' . $category->name . ' - Diesel Parts')
@section('heading', 'Detail Kategori')

@section('content')
<style>
    .cat-detail-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .cat-detail-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
    }

    .cat-info-card {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-lg);
        padding: 24px 28px;
        box-shadow: var(--shadow-sm);
        margin-bottom: 24px;
    }

    .cat-badge-code {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 6px;
        background: #eaf3ed;
        color: var(--matcha-primary-dark);
        font-weight: 700;
        font-size: 13px;
        margin-bottom: 10px;
    }
</style>

<div class="cat-detail-container">
    <div class="cat-detail-header">
        <div>
            <h1 class="page-title">{{ $category->name }}</h1>
            <p class="page-subtitle">Rincian kategori dan daftar produk yang terhubung.</p>
        </div>

        <div style="display: flex; gap: 8px;">
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                <span>← Kembali</span>
            </a>

            @if (auth()->user()->role === 'admin')
                <a href="{{ route('categories.edit', $category) }}" class="btn">
                    <span>✏️ Edit Kategori</span>
                </a>
            @endif
        </div>
    </div>

    <!-- INFO KATEGORI -->
    <div class="cat-info-card">
        @if ($category->code)
            <div class="cat-badge-code">Kode: {{ $category->code }}</div>
        @endif

        <h2 style="font-size: 20px; font-weight: 700; margin-bottom: 8px; color: var(--text-main);">
            {{ $category->name }}
        </h2>

        <p style="color: var(--text-secondary); font-size: 14px; line-height: 1.6; margin-bottom: 14px;">
            {{ $category->description ?: 'Belum ada keterangan atau deskripsi untuk kategori ini.' }}
        </p>

        <div style="font-size: 13px; color: var(--text-muted); display: flex; gap: 20px;">
            <span>📦 Total Produk Terdaftar: <strong>{{ $category->products->count() }} item</strong></span>
            <span>🗓️ Ditambahkan pada: {{ $category->created_at?->format('d F Y') ?? '-' }}</span>
        </div>
    </div>

    <!-- DAFTAR PRODUK DI KATEGORI INI -->
    <div class="card" style="padding: 0; overflow: hidden;">
        <div style="padding: 18px 24px; border-bottom: 1px solid var(--border-color); background: #fbfdfb;">
            <h3 style="font-size: 16px; font-weight: 700; color: var(--text-main); margin: 0;">
                Daftar Produk dalam Kategori Ini
            </h3>
        </div>

        <div class="table-wrap" style="border: none;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">No.</th>
                        <th>SKU</th>
                        <th>Nama Produk</th>
                        <th>Supplier</th>
                        <th style="text-align: right;">Harga Beli</th>
                        <th style="text-align: right;">Harga Jual</th>
                        <th style="text-align: center;">Sisa Stok</th>
                        <th style="text-align: center; width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($category->products as $product)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><strong>{{ $product->sku }}</strong></td>
                            <td style="font-weight: 600;">{{ $product->name }}</td>
                            <td>{{ $product->supplier->name ?? '-' }}</td>
                            <td style="text-align: right;">Rp {{ number_format((float) $product->purchase_price, 0, ',', '.') }}</td>
                            <td style="text-align: right; font-weight: 700; color: #166534;">
                                Rp {{ number_format((float) $product->selling_price, 0, ',', '.') }}
                            </td>
                            <td style="text-align: center;">
                                @if ($product->stock <= $product->min_stock)
                                    <span class="badge badge-danger">{{ $product->stock }} unit</span>
                                @else
                                    <span class="badge badge-success">{{ $product->stock }} unit</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                <a href="{{ route('products.show', $product) }}" class="btn btn-secondary" style="padding: 5px 10px; font-size: 12px;">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 35px; color: var(--text-muted);">
                                Belum ada produk yang dikaitkan dengan kategori ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

