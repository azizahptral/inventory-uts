@extends('layout.app')

@section('title', 'Produk - Diesel Parts')
@section('heading', 'Produk')

@section('content')
<style>
    .product-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .product-search {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .product-search input {
        width: 260px;
        max-width: 100%;
        padding: 11px 13px;
        border: 1px solid #d6deea;
        border-radius: 9px;
        font-size: 14px;
    }

    .product-btn {
        display: inline-block;
        border: none;
        border-radius: 9px;
        padding: 11px 16px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
    }

    .product-btn-primary {
        background: #109688;
        color: white;
    }

    .product-btn-secondary {
        background: #eef2f7;
        color: #344054;
    }

    .product-btn-danger {
        background: #fee2e2;
        color: #b91c1c;
    }

    .product-table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .product-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 850px;
    }

    .product-table th,
    .product-table td {
        padding: 14px 12px;
        border-bottom: 1px solid #edf0f5;
        text-align: left;
        font-size: 14px;
    }

    .product-table th {
        color: #667085;
        background: #f8fafc;
        font-weight: 600;
    }

    .product-table td {
        color: #344054;
    }

    .product-name {
        font-weight: 600;
        color: #1d2939;
    }

    .product-muted {
        color: #98a2b3;
    }

    .stock-badge {
        display: inline-block;
        padding: 5px 9px;
        border-radius: 20px;
        background: #dcfce7;
        color: #166534;
        font-size: 12px;
        font-weight: 600;
    }

    .stock-badge-low {
        background: #fee2e2;
        color: #b91c1c;
    }

    .product-actions {
        display: flex;
        gap: 6px;
        align-items: center;
    }

    .product-actions form {
        margin: 0;
    }

    .product-alert {
        padding: 13px 16px;
        border-radius: 9px;
        margin-bottom: 18px;
    }

    .product-alert-success {
        background: #dcfce7;
        color: #166534;
    }

    .product-alert-error {
        background: #fee2e2;
        color: #991b1b;
    }

    .product-pagination {
        padding-top: 18px;
    }

    .product-drawer-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, .45);
        z-index: 1000;
        opacity: 0;
        visibility: hidden;
        transition: .25s;
    }

    .product-drawer {
        position: fixed;
        top: 0;
        right: 0;
        height: 100vh;
        width: 650px;
        max-width: 100%;
        background: #fff;
        z-index: 1001;
        transform: translateX(100%);
        transition: transform .3s ease;
        display: flex;
        flex-direction: column;
        box-shadow: -10px 0 35px rgba(0, 0, 0, .12);
    }

    .product-drawer-backdrop.active {
        opacity: 1;
        visibility: visible;
    }

    .product-drawer.active {
        transform: translateX(0);
    }

    .product-drawer-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 25px 30px;
        border-bottom: 1px solid #e5eaf1;
        gap: 15px;
    }

    .product-drawer-header h2 {
        margin: 0 0 7px;
        font-size: 25px;
        color: #1d2939;
    }

    .product-drawer-header p {
        margin: 0;
        color: #667085;
        font-size: 14px;
    }

    .product-drawer-close {
        border: none;
        background: #f1f5f9;
        color: #475569;
        border-radius: 10px;
        width: 44px;
        height: 44px;
        font-size: 25px;
        cursor: pointer;
        flex-shrink: 0;
    }

    .product-drawer-body {
        padding: 25px 30px;
        overflow-y: auto;
        flex: 1;
    }

    .product-section-title {
        color: #109688;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 1px;
        margin: 0 0 18px;
    }

    .product-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .product-form-group {
        min-width: 0;
    }

    .product-form-group.full-width {
        grid-column: 1 / -1;
    }

    .product-form-group label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #344054;
        margin-bottom: 9px;
    }

    .product-form-group input,
    .product-form-group select,
    .product-form-group textarea {
        display: block;
        width: 100%;
        box-sizing: border-box;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        padding: 13px 15px;
        font-size: 15px;
        color: #344054;
        background: #fff;
        outline: none;
    }

    .product-form-group input:focus,
    .product-form-group select:focus,
    .product-form-group textarea:focus {
        border-color: #109688;
        box-shadow: 0 0 0 3px rgba(16, 150, 136, .12);
    }

    .product-form-group textarea {
        resize: vertical;
        min-height: 105px;
    }

    .product-required {
        color: #ef4444;
    }

    .product-field-error {
        color: #dc2626;
        font-size: 12px;
        margin-top: 6px;
    }

    .product-drawer-footer {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        padding: 18px 30px;
        border-top: 1px solid #e5eaf1;
        background: white;
    }

    body.product-drawer-open {
        overflow: hidden;
    }

    @media (max-width: 600px) {
        .product-form-grid {
            grid-template-columns: 1fr;
        }

        .product-form-group.full-width {
            grid-column: auto;
        }

        .product-drawer-header,
        .product-drawer-body {
            padding: 20px;
        }

        .product-drawer-footer {
            padding: 15px 20px;
        }

        .product-search input {
            width: 100%;
        }
    }
</style>

@if (session('success'))
    <div class="product-alert product-alert-success auto-dismiss-alert">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="product-alert product-alert-error auto-dismiss-alert">
        {{ session('error') }}
    </div>
@endif

@if ($errors->any())
    <div class="product-alert product-alert-error">
        <strong>Data belum berhasil disimpan.</strong>
        <ul style="margin-bottom: 0;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="product-toolbar">
        <form action="{{ route('products.index') }}" method="GET"
              class="product-search">
            <input
                type="text"
                name="search"
                value="{{ $search ?? '' }}"
                placeholder="Cari nama atau kode produk..."
            >

            <button type="submit" class="product-btn product-btn-secondary">
                Cari
            </button>

            @if (!empty($search))
                <a href="{{ route('products.index') }}"
                   class="product-btn product-btn-secondary">
                    Reset
                </a>
            @endif
        </form>

        <button type="button"
                class="product-btn product-btn-primary"
                onclick="openProductDrawer()">
            + Tambah Produk
        </button>
    </div>

    <div class="product-table-wrapper">
        <table class="product-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Kode Produk</th>
                    <th>Nama Produk</th>
                    <th>Supplier</th>
                    <th>Harga Beli</th>
                    <th>Harga Jual</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>{{ $products->firstItem() + $loop->index }}</td>

                        <td>{{ $product->sku }}</td>

                        <td>
                            <div class="product-name">{{ $product->name }}</div>

                            @if ($product->description)
                                <small class="product-muted">
                                    {{ $product->description }}
                                </small>
                            @endif
                        </td>

                        <td>{{ $product->supplier->name ?? '-' }}</td>

                        <td>
                            Rp {{ number_format($product->purchase_price, 0, ',', '.') }}
                        </td>

                        <td>
                            Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                        </td>

                        <td>
                            @if ($product->stock <= $product->min_stock)
                                <span class="stock-badge stock-badge-low">
                                    {{ $product->stock }}
                                </span>
                            @else
                                <span class="stock-badge">
                                    {{ $product->stock }}
                                </span>
                            @endif
                        </td>

                        <td>
                            <div class="product-actions">
                                <button
                                    type="button"
                                    class="product-btn product-btn-secondary"
                                    onclick="editProduct({{ $product->id }})">
                                    Edit
                                </button>

                                <form
                                    action="{{ route('products.destroy', $product) }}"
                                    method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="product-btn product-btn-danger">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 35px;">
                            Belum ada data produk.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="product-pagination">
        {{ $products->links() }}
    </div>
</div>

<div class="product-drawer-backdrop"
     id="productDrawerBackdrop"
     onclick="closeProductDrawer()"></div>

<aside class="product-drawer" id="productDrawer">
    <div class="product-drawer-header">
        <div>
            <h2 id="productFormTitle">Tambah Produk</h2>
            <p>Lengkapi informasi sparepart yang ingin ditambahkan.</p>
        </div>

        <button type="button"
                class="product-drawer-close"
                onclick="closeProductDrawer()"
                aria-label="Tutup">
            &times;
        </button>
    </div>

    <form id="productForm"
          action="{{ route('products.store') }}"
          method="POST"
          style="display:flex; flex-direction:column; flex:1; min-height:0;">
        @csrf

        <div id="productMethodField"></div>

        <div class="product-drawer-body">
            <h3 class="product-section-title">INFORMASI PRODUK</h3>

            <div class="product-form-grid">
                <div class="product-form-group">
                    <label for="sku">
                        SKU / Kode Produk <span class="product-required">*</span>
                    </label>

                    <input type="text"
                           id="sku"
                           name="sku"
                           value="{{ old('sku') }}"
                           placeholder="Contoh: SP001"
                           required>

                    @error('sku')
                        <div class="product-field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="product-form-group">
                    <label for="name">
                        Nama Produk <span class="product-required">*</span>
                    </label>

                    <input type="text"
                           id="name"
                           name="name"
                           value="{{ old('name') }}"
                           placeholder="Masukkan nama produk"
                           required>

                    @error('name')
                        <div class="product-field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="product-form-group full-width">
                    <label for="supplier_name">Supplier</label>

                    <input
                        type="text"
                        id="supplier_name"
                        name="supplier_name"
                        value="{{ old('supplier_name') }}"
                        placeholder="Ketik nama supplier..."
                        list="supplierSuggestions"
                        autocomplete="off"
                    >

                    <datalist id="supplierSuggestions">
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->name }}"></option>
                        @endforeach
                    </datalist>

                    <small class="product-muted">
                        Ketik nama supplier. Jika belum ada, supplier akan dibuat otomatis saat produk disimpan.
                    </small>

                    @error('supplier_name')
                        <div class="product-field-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <h3 class="product-section-title">HARGA DAN PERSEDIAAN</h3>

            <div class="product-form-grid">
                <div class="product-form-group">
                    <label for="purchase_price">
                        Harga Beli (Rp) <span class="product-required">*</span>
                    </label>

                    <input type="number"
                           id="purchase_price"
                           name="purchase_price"
                           value="{{ old('purchase_price', 0) }}"
                           min="0"
                           step="0.01"
                           required>

                    @error('purchase_price')
                        <div class="product-field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="product-form-group">
                    <label for="selling_price">
                        Harga Jual (Rp) <span class="product-required">*</span>
                    </label>

                    <input type="number"
                           id="selling_price"
                           name="selling_price"
                           value="{{ old('selling_price', 0) }}"
                           min="0"
                           step="0.01"
                           required>

                    @error('selling_price')
                        <div class="product-field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="product-form-group">
                    <label for="stock">
                        Stok Awal <span class="product-required">*</span>
                    </label>

                    <input type="number"
                           id="stock"
                           name="stock"
                           value="{{ old('stock', 0) }}"
                           min="0"
                           step="1"
                           required>

                    @error('stock')
                        <div class="product-field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="product-form-group">
                    <label for="min_stock">
                        Batas Minimum Stok <span class="product-required">*</span>
                    </label>

                    <input type="number"
                           id="min_stock"
                           name="min_stock"
                           value="{{ old('min_stock', 5) }}"
                           min="0"
                           step="1"
                           required>

                    @error('min_stock')
                        <div class="product-field-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <h3 class="product-section-title">INFORMASI TAMBAHAN</h3>

            <div class="product-form-grid">
                <div class="product-form-group full-width">
                    <label for="description">Deskripsi Produk</label>

                    <textarea
                        id="description"
                        name="description"
                        placeholder="Catatan atau detail tambahan produk..."
                    >{{ old('description') }}</textarea>

                    @error('description')
                        <div class="product-field-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="product-drawer-footer">
            <button type="button"
                    class="product-btn product-btn-secondary"
                    onclick="closeProductDrawer()">
                Batal
            </button>

            <button type="submit"
                    class="product-btn product-btn-primary"
                    id="productSubmitButton">
                Simpan Produk
            </button>
        </div>
    </form>
</aside>

<script>
    const productDrawer = document.getElementById('productDrawer');
    const productBackdrop = document.getElementById('productDrawerBackdrop');
    const productForm = document.getElementById('productForm');
    const productMethodField = document.getElementById('productMethodField');
    const productFormTitle = document.getElementById('productFormTitle');
    const productSubmitButton = document.getElementById('productSubmitButton');

    const productBaseUrl = @json(url('/products'));

    // Hilangkan notifikasi setelah 3 detik
    document.querySelectorAll('.auto-dismiss-alert').forEach(function (alert) {
        setTimeout(function () {
            alert.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';

            setTimeout(function () {
                alert.remove();
            }, 400);
        }, 3000);
    });

    function openProductDrawer() {
        productDrawer.classList.add('active');
        productBackdrop.classList.add('active');
        document.body.classList.add('product-drawer-open');
    }

    function closeProductDrawer() {
        productDrawer.classList.remove('active');
        productBackdrop.classList.remove('active');
        document.body.classList.remove('product-drawer-open');
    }

    function resetProductForm() {
        productForm.action = @json(route('products.store'));
        productMethodField.innerHTML = '';

        productFormTitle.textContent = 'Tambah Produk';
        productSubmitButton.textContent = 'Simpan Produk';

        productForm.reset();

        document.getElementById('purchase_price').value = 0;
        document.getElementById('selling_price').value = 0;
        document.getElementById('stock').value = 0;
        document.getElementById('min_stock').value = 5;
    }

    async function editProduct(id) {
        try {
            const response = await fetch(productBaseUrl + '/' + id + '/edit', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Gagal mengambil data produk. Coba refresh halaman.');
            }

            const product = await response.json();

            productForm.action = productBaseUrl + '/' + product.id;
            productMethodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';

            productFormTitle.textContent = 'Edit Produk';
            productSubmitButton.textContent = 'Simpan Perubahan';

            document.getElementById('sku').value = product.sku ?? '';
            document.getElementById('name').value = product.name ?? '';
            document.getElementById('supplier_name').value = product.supplier?.name ?? '';
            document.getElementById('purchase_price').value = product.purchase_price ?? 0;
            document.getElementById('selling_price').value = product.selling_price ?? 0;
            document.getElementById('stock').value = product.stock ?? 0;
            document.getElementById('min_stock').value = product.min_stock ?? 5;
            document.getElementById('description').value = product.description ?? '';

            openProductDrawer();
        } catch (error) {
            alert(error.message || 'Terjadi kesalahan saat membuka data produk.');
        }
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeProductDrawer();
        }
    });

    @if ($errors->any())
        openProductDrawer();
    @endif
</script>
@endsection