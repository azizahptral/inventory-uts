<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Menampilkan daftar kategori dengan pencarian (Admin & Staff).
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $categories = Category::withCount('products')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('categories.index', compact('categories', 'search'));
    }

    /**
     * Form tambah kategori (Admin Saja).
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Simpan kategori baru (Admin Saja).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        Category::create($validated);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Kategori produk berhasil ditambahkan.');
    }

    /**
     * Detail kategori dan daftar produk di dalamnya (Admin & Staff).
     */
    public function show(Category $category)
    {
        $category->load(['products.supplier']);

        return view('categories.show', compact('category'));
    }

    /**
     * Form edit kategori (Admin Saja).
     */
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Update data kategori (Admin Saja).
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $category->update($validated);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Kategori produk berhasil diperbarui.');
    }

    /**
     * Hapus kategori (Admin Saja).
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()
            ->route('categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}

