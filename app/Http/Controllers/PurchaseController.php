<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $purchases = Purchase::with('supplier')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where(
                        'purchase_number',
                        'like',
                        "%{$search}%"
                    )->orWhereHas('supplier', function ($supplierQuery) use ($search) {
                        $supplierQuery->where(
                            'name',
                            'like',
                            "%{$search}%"
                        );
                    });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('purchases.index', compact(
            'purchases',
            'search'
        ));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        return view('purchases.create', compact(
            'suppliers',
            'products'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => [
                'required',
                'exists:suppliers,id',
            ],
            'purchase_number' => [
                'required',
                'string',
                'max:100',
                'unique:purchases,purchase_number',
            ],
            'purchase_date' => [
                'required',
                'date',
            ],
            'due_date' => [
                'nullable',
                'date',
                'after_or_equal:purchase_date',
            ],
            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
            'items' => [
                'required',
                'array',
                'min:1',
            ],
            'items.*.type' => [
                'required',
                Rule::in(['existing', 'new']),
            ],
            'items.*.product_id' => [
                'nullable',
                'required_if:items.*.type,existing',
                'exists:products,id',
            ],
            'items.*.product_name' => [
                'nullable',
                'required_if:items.*.type,new',
                'string',
                'max:255',
            ],
            'items.*.sku' => [
                'nullable',
                'required_if:items.*.type,new',
                'string',
                'max:100',
                'distinct',
                'unique:products,sku',
            ],
            'items.*.selling_price' => [
                'nullable',
                'required_if:items.*.type,new',
                'numeric',
                'min:0',
            ],
            'items.*.quantity' => [
                'required',
                'integer',
                'min:1',
            ],
            'items.*.unit_price' => [
                'required',
                'numeric',
                'min:0.01',
            ],
        ]);

        $userId = 1;

        if (!User::whereKey($userId)->exists()) {
            return back()
                ->withInput()
                ->with('error', 'Akun pengguna ID 1 belum tersedia.');
        }

        DB::transaction(function () use ($validated, $userId) {
            $purchase = Purchase::create([
                'supplier_id' => $validated['supplier_id'],
                'user_id' => $userId,
                'purchase_number' => $validated['purchase_number'],
                'purchase_date' => $validated['purchase_date'],
                'due_date' => $validated['due_date'] ?? null,
                'total_amount' => 0,
                'paid_amount' => 0,
                'status' => 'completed',
                'notes' => $validated['notes'] ?? null,
            ]);

            $total = 0;

            foreach ($validated['items'] as $item) {
                if ($item['type'] === 'new') {
                    $product = Product::create([
                        'supplier_id' => $validated['supplier_id'],
                        'sku' => $item['sku'],
                        'name' => $item['product_name'],
                        'purchase_price' => $item['unit_price'],
                        'selling_price' => $item['selling_price'],
                        'stock' => 0,
                        'min_stock' => 0,
                    ]);
                } else {
                    $product = Product::whereKey($item['product_id'])
                        ->lockForUpdate()
                        ->firstOrFail();
                }

                $quantity = (int) $item['quantity'];
                $unitPrice = (float) $item['unit_price'];
                $subtotal = round($quantity * $unitPrice, 2);

                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                ]);

                $product->increment('stock', $quantity);

                $total += $subtotal;
            }

            $purchase->update([
                'total_amount' => round($total, 2),
            ]);
        });

        return redirect()
            ->route('purchases.index')
            ->with(
                'success',
                'Pembelian berhasil disimpan dan stok telah ditambahkan.'
            );
    }

    public function show(Purchase $purchase)
    {
        $purchase->load([
            'supplier',
            'items.product',
            'payments',
        ]);

        return view('purchases.show', compact('purchase'));
    }

    public function edit(Purchase $purchase)
    {
        return redirect()
            ->route('purchases.index')
            ->with(
                'error',
                'Pembelian yang sudah tercatat tidak dapat diedit.'
            );
    }

    public function update(Request $request, Purchase $purchase)
    {
        return redirect()
            ->route('purchases.index')
            ->with(
                'error',
                'Pembelian yang sudah tercatat tidak dapat diubah.'
            );
    }

    public function destroy(Purchase $purchase)
    {
        return redirect()
            ->route('purchases.index')
            ->with(
                'error',
                'Pembelian tidak dapat dihapus agar riwayat stok tetap aman.'
            );
    }
}