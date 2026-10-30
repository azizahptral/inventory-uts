<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Menampilkan daftar produk.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $categoryId = $request->input('category_id');

        $products = Product::with(['supplier', 'category'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhereHas('category', function ($catQ) use ($search) {
                            $catQ->where('name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('supplier', function ($supQ) use ($search) {
                            $supQ->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($categoryId, function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $suppliers = Supplier::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('products.index', compact(
            'products',
            'suppliers',
            'categories',
            'search',
            'categoryId'
        ));
    }

    /**
     * Menampilkan form tambah produk.
     */
    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('products.create', compact('suppliers', 'categories'));
    }

    /**
     * Menyimpan produk baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_name' => ['nullable', 'string', 'max:255'],
            'category_name' => ['nullable', 'string', 'max:255'],
            'sku' => [
                'required',
                'string',
                'max:100',
                'unique:products,sku',
            ],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'min_stock' => ['required', 'integer', 'min:0'],
        ]);

        $supplierName = trim($validated['supplier_name'] ?? '');
        unset($validated['supplier_name']);
        $validated['supplier_id'] = null;

        if ($supplierName !== '') {
            $supplier = Supplier::firstOrCreate([
                'name' => $supplierName,
            ]);

            $validated['supplier_id'] = $supplier->id;
        }

        // Kategori otomatis terhubung atau dibuat baru jika belum ada
        $categoryName = trim($validated['category_name'] ?? '');
        unset($validated['category_name']);
        $validated['category_id'] = null;

        if ($categoryName !== '') {
            $category = Category::firstOrCreate([
                'name' => $categoryName,
            ]);

            $validated['category_id'] = $category->id;
        }

        Product::create($validated);

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail produk.
     */
    public function show(Product $product)
    {
        $product->load(['supplier', 'category']);

        return view('products.show', compact('product'));
    }

    /**
     * Mengambil data produk untuk JavaScript drawer edit.
     * Mengembalikan JSON, bukan halaman HTML.
     */
    public function edit(Product $product)
    {
        return response()->json(
            $product->load(['supplier', 'category'])
        );
    }

    /**
     * Memperbarui produk.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'supplier_name' => ['nullable', 'string', 'max:255'],
            'category_name' => ['nullable', 'string', 'max:255'],
            'sku' => [
                'required',
                'string',
                'max:100',
                Rule::unique('products', 'sku')->ignore($product->id),
            ],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'min_stock' => ['required', 'integer', 'min:0'],
        ]);

        $supplierName = trim($validated['supplier_name'] ?? '');
        unset($validated['supplier_name']);
        $validated['supplier_id'] = null;

        if ($supplierName !== '') {
            $supplier = Supplier::firstOrCreate([
                'name' => $supplierName,
            ]);

            $validated['supplier_id'] = $supplier->id;
        }

        // Kategori otomatis terhubung atau dibuat baru jika belum ada
        $categoryName = trim($validated['category_name'] ?? '');
        unset($validated['category_name']);
        $validated['category_id'] = null;

        if ($categoryName !== '') {
            $category = Category::firstOrCreate([
                'name' => $categoryName,
            ]);

            $validated['category_id'] = $category->id;
        }

        $product->update($validated);

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Menapus produk.
     */
    public function destroy(Product $product)
    {
        if (
            $product->purchaseItems()->exists() ||
            $product->saleItems()->exists()
        ) {
            return redirect()
                ->route('products.index')
                ->with(
                    'error',
                    'Produk tidak bisa dihapus karena sudah digunakan dalam transaksi.'
                );
        }

        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
