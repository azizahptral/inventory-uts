<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\Sale;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();

        $totalSuppliers = Supplier::count();

        $totalSales = Sale::count();

        $totalRevenue = Sale::sum('total_amount');

        $lowStockCount = Product::whereColumn('stock', '<=', 'min_stock')
            ->count();

        $lowStockProducts = Product::whereColumn('stock', '<=', 'min_stock')
            ->orderBy('stock')
            ->limit(5)
            ->get();

        $recentSales = Sale::with('user')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalProducts',
            'totalSuppliers',
            'totalSales',
            'totalRevenue',
            'lowStockCount',
            'lowStockProducts',
            'recentSales'
        ));
    }
}
