@extends('layout.app')

@section('title', 'Transaksi Penjualan')
@section('header', 'Kasir / Penjualan')

@section('content')
<style>
    .sale-page { max-width: 1200px; margin: 0 auto; }
    .sale-heading { margin-bottom: 24px; }
    .sale-heading h1 { margin: 0 0 7px; font-size: 26px; }
    .sale-muted { color: #64748b; font-size: 14px; }
    .sale-card {
        background: white; border: 1px solid #e2e8f0;
        border-radius: 14px; padding: 24px; margin-bottom: 20px;
        box-shadow: 0 4px 15px rgba(15,23,42,.04);
    }
    .sale-card h3 { margin: 0 0 20px; font-size: 17px; }
    .sale-grid { display: grid; grid-template-columns: 1fr 2fr; gap: 20px; }
    .sale-field label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; }
    .sale-field input, .sale-field textarea, .sale-input {
        width: 100%; min-width: 0; padding: 11px 12px;
        border: 1px solid #dbe3ed; border-radius: 8px;
        font: inherit; background: #fff;
    }
    .sale-field input:focus, .sale-input:focus {
        outline: 2px solid #99f6e4; border-color: #0d9488;
    }
    .sale-table-wrap { overflow-x: auto; }
    .sale-table { width: 100%; border-collapse: collapse; min-width: 700px; }
    .sale-table th {
        padding: 12px; text-align: left; font-size: 12px;
        color: #64748b; background: #f8fafc; text-transform: uppercase;
    }
    .sale-table td { padding: 12px; border-bottom: 1px solid #edf2f7; vertical-align: middle; }
    .sale-table .sale-input { padding: 10px; }
    .sale-toprow {
        display: flex; justify-content: space-between; align-items: center;
        gap: 12px; margin-bottom: 18px;
    }
    .sale-btn {
        display: inline-flex; align-items: center; justify-content: center;
        gap: 7px; padding: 11px 16px; border: 0; border-radius: 8px;
        background: #0d9488; color: white; text-decoration: none;
        font-size: 14px; cursor: pointer;
    }
    .sale-btn:hover { background: #0f766e; }
    .sale-btn-light { background: #e8eef5; color: #334155; }
    .sale-btn-light:hover { background: #dbe4ee; }
    .sale-btn-danger { background: #fff1f2; color: #be123c; padding: 9px 12px; }
    .sale-btn-danger:hover { background: #ffe4e6; }
    .sale-summary {
        max-width: 420px; margin-left: auto; padding-top: 18px;
    }
    .sale-summary-line {
        display: flex; justify-content: space-between; gap: 20px;
        margin-bottom: 12px; color: #475569;
    }
    .sale-grand-total {
        padding-top: 15px; border-top: 1px solid #e2e8f0;
        font-size: 22px; font-weight: 700; color: #0f766e;
    }
    .sale-payment-grid {
        display: grid; grid-template-columns: 1fr 1fr; gap: 20px;
    }
    .sale-change {
        padding: 14px; border-radius: 9px; background: #f0fdfa;
        color: #0f766e; font-size: 22px; font-weight: 700;
    }
    .sale-actions { display: flex; justify-content: flex-end; gap: 10px; }
    .sale-error {
        padding: 14px 18px; margin-bottom: 18px; border-radius: 9px;
        color: #991b1b; background: #fee2e2;
    }
    .sale-empty { padding: 24px; text-align: center; color: #64748b; }
    @media(max-width: 700px) {
        .sale-grid, .sale-payment-grid { grid-template-columns: 1fr; }
        .sale-card { padding: 16px; }
        .sale-toprow { align-items: flex-start; flex-direction: column; }
        .sale-actions { flex-wrap: wrap; }
    }
</style>

<div class="sale-page">
    <div class="sale-heading">
        <h1>Transaksi Penjualan</h1>
        <div class="sale-muted">Masukkan barang, periksa total, lalu proses pembayaran.</div>
    </div>

    @if($errors->any())
        <div class="sale-error">
            <strong>Transaksi belum berhasil:</strong>
            <ul style="margin-bottom:0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($products->isEmpty())
        <div class="sale-error">
            Belum ada produk dengan stok tersedia. Tambahkan stok melalui menu Stok Masuk terlebih dahulu.
        </div>
    @endif

    <form action="{{ route('sales.store') }}" method="POST" id="saleForm">
        @csrf

        <section class="sale-card">
            <h3>Informasi Transaksi</h3>
            <div class="sale-grid">
                <div class="sale-field">
                    <label for="sale_date">Tanggal Penjualan</label>
                    <input
                        type="date"
                        id="sale_date"
                        name="sale_date"
                        value="{{ old('sale_date', date('Y-m-d')) }}"
                        required
                    >
                </div>

                <div class="sale-field">
                    <label for="notes">Catatan (opsional)</label>
                    <input
                        type="text"
                        id="notes"
                        name="notes"
                        value="{{ old('notes') }}"
                        placeholder="Contoh: pembelian pelanggan"
                    >
                </div>
            </div>
        </section>

        <section class="sale-card">
            <div class="sale-toprow">
                <div>
                    <h3 style="margin-bottom:5px">Daftar Barang</h3>
                    <div class="sale-muted">Ketik nama barang atau SKU untuk mencari produk terdaftar.</div>
                </div>
                <button type="button" class="sale-btn" id="addRow">+ Tambah Barang</button>
            </div>

            <datalist id="productSuggestions">
                @foreach($products as $product)
                    <option value="{{ $product->name }} — {{ $product->sku }}"></option>
                @endforeach
            </datalist>

            <div class="sale-table-wrap">
                <table class="sale-table">
                    <thead>
                        <tr>
                            <th>Nama Barang / SKU</th>
                            <th>Stok</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="itemsBody"></tbody>
                </table>
            </div>

            <div id="emptyMessage" class="sale-empty">
                Belum ada barang. Klik "Tambah Barang" untuk memulai.
            </div>

            <div class="sale-summary">
                <div class="sale-summary-line">
                    <span>Jumlah jenis barang</span>
                    <strong id="itemCount">0</strong>
                </div>
                <div class="sale-summary-line sale-grand-total">
                    <span>Total Belanja</span>
                    <span id="totalDisplay">Rp 0</span>
                </div>
            </div>
        </section>

        <section class="sale-card">
            <h3>Pembayaran</h3>
            <div class="sale-payment-grid">
                <div class="sale-field">
                    <label for="paid_amount">Uang Dibayarkan (Rp)</label>
                    <input
                        type="number"
                        name="paid_amount"
                        id="paid_amount"
                        min="0"
                        step="1"
                        value="{{ old('paid_amount', 0) }}"
                        required
                    >
                    <small class="sale-muted">Masukkan uang yang diterima dari pelanggan.</small>
                </div>

                <div>
                    <label style="display:block;font-size:13px;font-weight:600;margin-bottom:8px">
                        Kembalian
                    </label>
                    <div class="sale-change" id="changeDisplay">Rp 0</div>
                    <small class="sale-muted" id="paymentMessage">Total akan dihitung otomatis.</small>
                </div>
            </div>
        </section>

        <div class="sale-actions">
            <a href="{{ route('sales.index') }}" class="sale-btn sale-btn-light">Batal</a>
            <button type="submit" class="sale-btn" {{ $products->isEmpty() ? 'disabled' : '' }}>
                Simpan Transaksi
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const products = {{ Illuminate\Support\Js::from(
    $products->map(function ($p) {
        return [
            'id' => $p->id,
            'name' => $p->name,
            'sku' => $p->sku,
            'stock' => (int) $p->stock,
            'price' => (float) $p->selling_price,
        ];
    })->values()
) }};

    const body = document.getElementById('itemsBody');
    const addButton = document.getElementById('addRow');
    const paidInput = document.getElementById('paid_amount');
    const form = document.getElementById('saleForm');

    let nextIndex = 0;

    function money(value) {
        return 'Rp ' + Math.round(value).toLocaleString('id-ID');
    }

    function findProduct(text) {
        const query = text.trim().toLocaleLowerCase('id-ID');
        if (!query) return null;

        return products.find(p =>
            (p.name + ' — ' + p.sku).toLocaleLowerCase('id-ID') === query ||
            p.name.toLocaleLowerCase('id-ID') === query ||
            p.sku.toLocaleLowerCase('id-ID') === query
        ) || null;
    }

    function createRow() {
        const index = nextIndex++;
        const row = document.createElement('tr');
        row.className = 'sale-row';
        row.innerHTML = `
            <td>
                <input type="text" class="sale-input product-search"
                    list="productSuggestions" placeholder="Ketik nama barang / SKU"
                    autocomplete="off" required>
                <input type="hidden" name="items[${index}][product_id]" class="product-id">
                <div class="sale-muted product-hint" style="margin-top:5px"></div>
            </td>
            <td class="stock-cell">-</td>
            <td class="price-cell">-</td>
            <td>
                <input type="number" name="items[${index}][quantity]"
                    class="sale-input quantity-input" min="1" value="1" required>
            </td>
            <td class="subtotal-cell">Rp 0</td>
            <td>
                <button type="button" class="sale-btn sale-btn-danger remove-row">Hapus</button>
            </td>
        `;

        body.appendChild(row);

        const search = row.querySelector('.product-search');
        const quantity = row.querySelector('.quantity-input');

        function updateRow() {
            const product = findProduct(search.value);
            const idInput = row.querySelector('.product-id');

            if (!product) {
                idInput.value = '';
                row.querySelector('.product-hint').textContent =
                    search.value.trim() ? 'Pilih nama barang dari saran yang tersedia.' : '';
                row.querySelector('.stock-cell').textContent = '-';
                row.querySelector('.price-cell').textContent = '-';
                row.querySelector('.subtotal-cell').textContent = 'Rp 0';
                updateTotal();
                return;
            }

            idInput.value = product.id;
            row.querySelector('.product-hint').textContent = product.name + ' (' + product.sku + ')';
            row.querySelector('.stock-cell').textContent = product.stock;
            row.querySelector('.price-cell').textContent = money(product.price);

            quantity.max = product.stock;
            if (Number(quantity.value) > product.stock) {
                quantity.value = product.stock;
            }

            row.querySelector('.subtotal-cell').textContent =
                money(product.price * Number(quantity.value || 0));

            updateTotal();
        }

        search.addEventListener('input', updateRow);
        search.addEventListener('change', updateRow);
        quantity.addEventListener('input', updateRow);

        row.querySelector('.remove-row').addEventListener('click', function () {
            row.remove();
            updateTotal();
        });

        updateRow();
        search.focus();
    }

    function updateTotal() {
        let total = 0;
        let count = 0;

        body.querySelectorAll('.sale-row').forEach(row => {
            const product = findProduct(row.querySelector('.product-search').value);
            const quantity = Number(row.querySelector('.quantity-input').value || 0);

            if (product && quantity > 0) {
                total += product.price * quantity;
                count++;
                row.querySelector('.subtotal-cell').textContent =
                    money(product.price * quantity);
            }
        });

        document.getElementById('totalDisplay').textContent = money(total);
        document.getElementById('itemCount').textContent = count;
        document.getElementById('emptyMessage').style.display =
            body.querySelector('.sale-row') ? 'none' : 'block';

        const paid = Number(paidInput.value || 0);
        const change = paid - total;

        document.getElementById('changeDisplay').textContent =
            money(Math.max(0, change));

        document.getElementById('paymentMessage').textContent =
            total === 0 ? 'Tambahkan barang terlebih dahulu.' :
            change < 0 ? 'Pembayaran masih kurang ' + money(-change) :
            'Pembayaran mencukupi.';
    }

    addButton.addEventListener('click', createRow);
    paidInput.addEventListener('input', updateTotal);

    form.addEventListener('submit', function (event) {
        const rows = [...body.querySelectorAll('.sale-row')];
        const totals = new Map();
        let total = 0;

        if (!rows.length) {
            event.preventDefault();
            alert('Tambahkan setidaknya satu barang.');
            return;
        }

        for (const row of rows) {
            const product = findProduct(row.querySelector('.product-search').value);
            const quantity = Number(row.querySelector('.quantity-input').value);

            if (!product) {
                event.preventDefault();
                alert('Pilih barang dari saran nama barang atau SKU yang tersedia.');
                row.querySelector('.product-search').focus();
                return;
            }

            if (!Number.isInteger(quantity) || quantity < 1) {
                event.preventDefault();
                alert('Jumlah barang harus berupa bilangan bulat minimal 1.');
                return;
            }

            totals.set(product.id, (totals.get(product.id) || 0) + quantity);
            total += product.price * quantity;
        }

        for (const [productId, quantity] of totals) {
            const product = products.find(p => p.id === productId);
            if (!product || quantity > product.stock) {
                event.preventDefault();
                alert('Jumlah barang melebihi stok tersedia: ' + (product?.name || 'produk'));
                return;
            }
        }

        if (Number(paidInput.value || 0) < total) {
            event.preventDefault();
            alert('Uang pembayaran kurang dari total belanja.');
        }
    });

    createRow();
    updateTotal();
});
</script>
@endsection