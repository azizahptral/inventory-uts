
@extends('layout.app')

@section('title', 'Data Supplier')
@section('header', 'Data Supplier')

@section('content')
    <h1 class="page-title">Data Supplier</h1>
    <p class="page-subtitle">
        Kelola informasi pemasok barang Diesel Parts.
    </p>

    @if (session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert-error">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert-error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <form action="{{ route('suppliers.index') }}" method="GET">
            <p>
                <label for="search">Cari Supplier</label>
                <input
                    class="form-input"
                    type="text"
                    id="search"
                    name="search"
                    value="{{ $search ?? '' }}"
                    placeholder="Nama, telepon, atau email"
                >
            </p>

            <button type="submit" class="btn">Cari</button>

            <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">
                Reset
            </a>
        </form>
    </div>

    <br>

    <div class="card">
        <p>
            <a href="{{ route('suppliers.create') }}" class="btn">
                + Tambah Supplier
            </a>
        </p>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Supplier</th>
                        <th>Telepon</th>
                        <th>Email</th>
                        <th>Alamat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($suppliers as $supplier)
                        <tr>
                            <td>
                                {{ $suppliers->firstItem() + $loop->index }}
                            </td>

                            <td>
                                <a href="{{ route('suppliers.show', $supplier) }}">
                                    {{ $supplier->name }}
                                </a>
                            </td>

                            <td>{{ $supplier->phone ?? '-' }}</td>
                            <td>{{ $supplier->email ?? '-' }}</td>
                            <td>{{ $supplier->address ?? '-' }}</td>

                            <td>
                                <a
                                    href="{{ route('suppliers.edit', $supplier) }}"
                                    class="btn"
                                >
                                    Edit
                                </a>

                                <form
                                    action="{{ route('suppliers.destroy', $supplier) }}"
                                    method="POST"
                                    style="display: inline;"
                                    onsubmit="return confirm('Yakin ingin menghapus supplier ini?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-secondary"
                                    >
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                Belum ada data supplier.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <br>

        {{ $suppliers->links() }}
    </div>
@endsection