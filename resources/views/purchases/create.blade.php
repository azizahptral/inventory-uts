
@extends('layout.app')

@section('title', 'Catat Pembelian')
@section('header', 'Catat Pembelian Supplier')

@section('content')
<h1 class="page-title">Catat Pembelian Baru</h1>
<p class="page-subtitle">
    Pilih barang yang sudah ada atau masukkan barang baru secara manual.
    Stok akan bertambah setelah pembelian berhasil disimpan.
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
<form action="{{ route('purchases.store') }}" method="POST" id="purchase-form">
    @csrf

    <h3>Informasi Pembelian</h3>

    <p>
        <label for="supplier_id">Supplier *</label>
        <select class="form-input" id="supplier_id" name="supplier_id" required>
            <option value="">Pilih atau ketik nama supplier...</option>
            @foreach ($suppliers as $supplier)
                <option value="{{ $supplier->id }}"
                    @selected(old('supplier_id') == $supplier->id)>
                    {{ $supplier->name }}
                </option>
            @endforeach
        </select>
        @if ($suppliers->isEmpty())
            <small>Tambahkan supplier terlebih dahulu.</small>
        @endif
    </p>

    <p>
        <label for="purchase_number">Nomor Pembelian *</label>
        <input class="form-input" type="text" id="purchase_number"
            name="purchase_number" value="{{ old('purchase_number') }}"
            placeholder="Contoh: PB-2026-001" maxlength="100" required>
    </p>

    <p>
        <label for="purchase_date">Tanggal Pembelian *</label>
        <input class="form-input" type="date" id="purchase_date"
            name="purchase_date"
            value="{{ old('purchase_date', now()->toDateString()) }}" required>
    </p>

    <p>
        <label for="due_date">Tanggal Jatuh Tempo</label>
        <input class="form-input" type="date" id="due_date" name="due_date"
            value="{{ old('due_date') }}"
            min="{{ old('purchase_date', now()->toDateString()) }}">
        <small>Kosongkan jika tidak ada tanggal jatuh tempo.</small>
    </p>

    <p>
        <label for="notes">Catatan</label>
        <textarea class="form-input" id="notes" name="notes"
            rows="3" maxlength="1000">{{ old('notes') }}</textarea>
    </p>

    <hr>

    <h3>Barang yang Sudah Terdaftar</h3>
    <p>Centang barang yang dibeli, lalu isi jumlah dan harga beli.</p>

    @if ($products->isNotEmpty())
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Pilih</th>
                        <th>Nama Barang</th>
                        <th>Stok</th>
                        <th>Jumlah Beli</th>
                        <th>Harga Beli (Rp)</th>
                        <th>Subtotal (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>
                            <input type="checkbox" class="existing-checkbox"
                                data-index="{{ $loop->index }}"
                                aria-label="Pilih {{ $product->name }}">
                        </td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->stock }}</td>
                        <td>
                            <input type="hidden"
                                name="items[{{ $loop->index }}][type]"
                                value="existing" disabled>
                            <input type="hidden"
                                name="items[{{ $loop->index }}][product_id]"
                                value="{{ $product->id }}" disabled>
                            <input class="form-input item-quantity"
                                type="number" min="1" step="1" value="1"
                                name="items[{{ $loop->index }}][quantity]"
                                data-index="{{ $loop->index }}" disabled>
                        </td>
                        <td>
                            <input class="form-input item-price"
                                type="number" min="0.01" step="0.01"
                                value="{{ $product->purchase_price }}"
                                name="items[{{ $loop->index }}][unit_price]"
                                data-index="{{ $loop->index }}" disabled>
                        </td>
                        <td>
                            <span class="item-subtotal"
                                data-index="{{ $loop->index }}">Rp 0</span>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p>Belum ada barang terdaftar. Kamu tetap bisa memasukkan barang baru di bawah.</p>
    @endif

    <hr>

    <h3>Tambah Barang Baru secara Manual</h3>
    <p>
        Isi bagian ini jika barang yang dibeli belum ada di daftar produk.
        Barang baru akan disimpan ke daftar produk setelah pembelian berhasil.
    </p>

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nama Barang *</th>
                    <th>Kode / SKU *</th>
                    <th>Jumlah *</th>
                    <th>Harga Beli (Rp) *</th>
                    <th>Harga Jual (Rp) *</th>
                    <th>Subtotal</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="new-products-body"></tbody>
        </table>
    </div>

    <button type="button" class="btn btn-secondary" id="add-new-product">
        + Tambah Baris Barang
    </button>

    <p style="margin-top:20px">
        <strong>Total Pembelian: Rp <span id="purchase-total">0</span></strong>
    </p>

    <button type="submit" class="btn"
        @disabled($suppliers->isEmpty())>
        Simpan Pembelian
    </button>

    <a href="{{ route('purchases.index') }}" class="btn btn-secondary">
        Batal
    </a>
</form>
</div>

<link rel="stylesheet"
 href="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/css/tom-select.css">

<style>
    .ts-control {
        min-height: 42px;
        padding: 10px 12px;
        border: 1px solid #d1dbea;
        border-radius: 7px;
    }

    .ts-dropdown { z-index: 9999; }

    .data-table .form-input {
        min-width: 105px;
        width: 130px;
        box-sizing: border-box;
    }

    .data-table .new-name,
    .data-table .new-sku {
        min-width: 150px;
        width: 180px;
    }

    .item-subtotal,
    .new-subtotal { white-space: nowrap; }

    .remove-new-product {
        background: #dc3545;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 8px 10px;
        cursor: pointer;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/js/tom-select.complete.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const supplierSelect = document.getElementById('supplier_id');

    if (supplierSelect && window.TomSelect && !supplierSelect.tomselect) {
        new TomSelect(supplierSelect, {
            create: false,
            placeholder: 'Ketik nama supplier untuk mencari...',
            searchField: ['text'],
            allowEmptyOption: true
        });
    }

    const form = document.getElementById('purchase-form');
    const newBody = document.getElementById('new-products-body');
    const totalDisplay = document.getElementById('purchase-total');

    let nextIndex = {{ $products->count() }};

    function rupiah(value) {
        return new Intl.NumberFormat('id-ID', {
            maximumFractionDigits: 2
        }).format(value);
    }

    function updateTotal() {
        let total = 0;

        document.querySelectorAll('.existing-checkbox').forEach(function (check) {
            const index = check.dataset.index;
            const qty = document.querySelector(
                '.item-quantity[data-index="' + index + '"]'
            );
            const price = document.querySelector(
                '.item-price[data-index="' + index + '"]'
            );
            const type = document.querySelector(
                'input[name="items[' + index + '][type]"]'
            );
            const productId = document.querySelector(
                'input[name="items[' + index + '][product_id]"]'
            );
            const subtotal = document.querySelector(
                '.item-subtotal[data-index="' + index + '"]'
            );

            qty.disabled = !check.checked;
            price.disabled = !check.checked;
            type.disabled = !check.checked;
            productId.disabled = !check.checked;

            const amount = check.checked
                ? (Number(qty.value) || 0) * (Number(price.value) || 0)
                : 0;

            subtotal.textContent = 'Rp ' + rupiah(amount);
            total += amount;
        });

        newBody.querySelectorAll('tr').forEach(function (row) {
            const qty = Number(row.querySelector('.new-quantity').value) || 0;
            const price = Number(row.querySelector('.new-price').value) || 0;
            const amount = qty * price;

            row.querySelector('.new-subtotal').textContent = 'Rp ' + rupiah(amount);
            total += amount;
        });

        totalDisplay.textContent = rupiah(total);
    }

    function addNewProductRow() {
        const index = nextIndex++;
        const row = document.createElement('tr');

        row.innerHTML = `
            <td>
                <input type="hidden" name="items[${index}][type]" value="new">
                <input class="form-input new-name" type="text"
                    name="items[${index}][product_name]"
                    maxlength="255" placeholder="Nama barang" required>
            </td>
            <td>
                <input class="form-input new-sku" type="text"
                    name="items[${index}][sku]"
                    maxlength="100" placeholder="Kode unik" required>
            </td>
            <td>
                <input class="form-input new-quantity" type="number"
                    name="items[${index}][quantity]"
                    min="1" step="1" value="1" required>
                <input type="hidden" name="items[${index}][product_id]" value="">
            </td>
            <td>
                <input class="form-input new-price" type="number"
                    name="items[${index}][unit_price]"
                    min="0.01" step="0.01" placeholder="Harga beli" required>
            </td>
            <td>
                <input class="form-input" type="number"
                    name="items[${index}][selling_price]"
                    min="0" step="0.01" placeholder="Harga jual" required>
            </td>
            <td><span class="new-subtotal">Rp 0</span></td>
            <td>
                <button type="button" class="remove-new-product">Hapus</button>
            </td>
        `;

        newBody.appendChild(row);

        row.querySelectorAll('input').forEach(function (input) {
            input.addEventListener('input', updateTotal);
        });

        row.querySelector('.remove-new-product').addEventListener('click', function () {
            row.remove();
            updateTotal();
        });

        updateTotal();
    }

    document.querySelectorAll('.existing-checkbox').forEach(function (check) {
        check.addEventListener('change', updateTotal);
    });

    document.querySelectorAll('.item-quantity, .item-price').forEach(function (input) {
        input.addEventListener('input', updateTotal);
    });

    document.getElementById('add-new-product').addEventListener('click', addNewProductRow);

    // Jika belum ada barang terdaftar, sediakan satu baris manual otomatis.
    if ({{ $products->isEmpty() ? 'true' : 'false' }}) {
        addNewProductRow();
    }

    form.addEventListener('submit', function (event) {
        const hasExisting = Array.from(
            document.querySelectorAll('.existing-checkbox')
        ).some(function (checkbox) {
            return checkbox.checked;
        });

        const hasNew = newBody.querySelectorAll('tr').length > 0;

        if (!hasExisting && !hasNew) {
            event.preventDefault();
            alert('Pilih minimal satu barang atau tambahkan barang baru secara manual.');
        }
    });

    updateTotal();
});
</script>
@endsection
