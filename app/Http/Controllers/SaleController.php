<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with('user')
            ->latest()
            ->paginate(10);

        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $products = Product::where('stock', '>', 0)
            ->orderBy('name')
            ->get();

        return view('sales.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sale_date' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'paid_amount' => ['required', 'numeric', 'min:0'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        $sale = DB::transaction(function () use ($validated) {
            // Kunci baris stok agar dua transaksi bersamaan tidak
            // menjual stok yang sama secara berlebihan.
            $quantities = [];

            foreach ($validated['items'] as $item) {
                $productId = (int) $item['product_id'];
                $quantities[$productId] =
                    ($quantities[$productId] ?? 0) + (int) $item['quantity'];
            }

            ksort($quantities);

            $products = Product::whereIn('id', array_keys($quantities))
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $total = 0;
            $saleItems = [];

            foreach ($quantities as $productId => $quantity) {
                $product = $products->get($productId);

                if (!$product) {
                    throw ValidationException::withMessages([
                        'items' => 'Produk tidak ditemukan.',
                    ]);
                }

                if ((int) $product->stock < $quantity) {
                    throw ValidationException::withMessages([
                        'items' => "Stok {$product->name} tidak mencukupi. Stok tersedia: {$product->stock}.",
                    ]);
                }
            }

            // Pastikan kasir tersedia meskipun aplikasi belum memakai login.
            $userId = Auth::id() ?? User::query()->value('id');

            if (!$userId) {
                throw ValidationException::withMessages([
                    'items' => 'Belum ada pengguna di database. Tambahkan pengguna terlebih dahulu.',
                ]);
            }

            $sale = Sale::create([
                'user_id' => $userId,
                'sale_number' => 'PJ-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(6)),
                'sale_date' => $validated['sale_date'],
                'total_amount' => 0,
                'paid_amount' => 0,
                'change_amount' => 0,
                'status' => 'completed',
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($quantities as $productId => $quantity) {
                $product = $products->get($productId);
                $price = (float) $product->selling_price;
                $subtotal = $price * $quantity;

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $price,
                    'subtotal' => $subtotal,
                ]);

                // STOK OTOMATIS BERKURANG SAAT PENJUALAN DISIMPAN.
                $product->decrement('stock', $quantity);

                $total += $subtotal;
            }

            $paid = (float) $validated['paid_amount'];

            if ($paid < $total) {
                throw ValidationException::withMessages([
                    'paid_amount' => 'Uang pembayaran kurang dari total belanja.',
                ]);
            }

            $sale->update([
                'total_amount' => $total,
                'paid_amount' => $paid,
                'change_amount' => $paid - $total,
            ]);

            return $sale;
        });

        return redirect()
            ->route('sales.show', $sale)
            ->with('success', 'Transaksi penjualan berhasil disimpan.');
    }

    public function show(Sale $sale)
    {
        $sale->load(['items.product', 'user']);

        return view('sales.show', compact('sale'));
    }
}
