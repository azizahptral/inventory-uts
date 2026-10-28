<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $suppliers = Supplier::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%");
                });
            })
            ->withCount('purchases')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('suppliers.index', compact(
            'suppliers',
            'search'
        ));
    }

    public function show(Supplier $supplier)
    {
        $purchases = $supplier->purchases()
            ->with(['items.product', 'payments'])
            ->latest('purchase_date')
            ->paginate(10);

        $totalTagihan = $supplier->purchases()->sum('total_amount');
        $totalDibayar = $supplier->purchases()->sum('paid_amount');
        $sisaUtang = max(0, $totalTagihan - $totalDibayar);

        return view('suppliers.show', compact(
            'supplier',
            'purchases',
            'totalTagihan',
            'totalDibayar',
            'sisaUtang'
        ));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:1000'],
        ]);

        Supplier::create($validated);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:1000'],
        ]);

        $supplier->update($validated);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Data supplier berhasil diperbarui.');
    }

    public function destroy(Supplier $supplier)
    {
        if (
            $supplier->products()->exists()
            || $supplier->purchases()->exists()
        ) {
            return redirect()
                ->route('suppliers.index')
                ->with(
                    'error',
                    'Supplier tidak dapat dihapus karena masih terhubung dengan barang atau transaksi pembelian.'
                );
        }

        $supplier->delete();

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier berhasil dihapus.');
    }
}
