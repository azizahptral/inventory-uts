<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchasePaymentController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\FinancialReportController;

// LOGIN
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.process');
});

// SEMUA HALAMAN YANG WAJIB LOGIN
Route::middleware('auth')->group(function () {

    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // ADMIN DAN STAFF: HANYA MELIHAT PRODUK DAN SUPPLIER
    Route::middleware('role:admin,staff')->group(function () {

        Route::get('/products', [ProductController::class, 'index'])
            ->name('products.index');

        Route::get('/products/{product}', [ProductController::class, 'show'])
            ->name('products.show');

        Route::get('/suppliers', [SupplierController::class, 'index'])
            ->name('suppliers.index');

        Route::get('/suppliers/{supplier}', [SupplierController::class, 'show'])
            ->name('suppliers.show');

        // STOK MASUK
        Route::get('/purchases', [PurchaseController::class, 'index'])
            ->name('purchases.index');

        Route::get('/purchases/create', [PurchaseController::class, 'create'])
            ->name('purchases.create');

        Route::post('/purchases', [PurchaseController::class, 'store'])
            ->name('purchases.store');

        Route::get('/purchases/{purchase}', [PurchaseController::class, 'show'])
            ->name('purchases.show');

        Route::post('/purchases/{purchase}/payments', [
            PurchasePaymentController::class,
            'store'
        ])->name('purchases.payments.store');

        // PENJUALAN
        Route::resource('sales', SaleController::class)
            ->only(['index', 'create', 'store', 'show']);
    });

    // ADMIN SAJA: TAMBAH, EDIT, DAN HAPUS PRODUK
    Route::middleware('role:admin')->group(function () {

        Route::get('/products/create', [ProductController::class, 'create'])
            ->name('products.create');

        Route::post('/products', [ProductController::class, 'store'])
            ->name('products.store');

        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])
            ->name('products.edit');

        Route::put('/products/{product}', [ProductController::class, 'update'])
            ->name('products.update');

        Route::patch('/products/{product}', [ProductController::class, 'update']);

        Route::delete('/products/{product}', [ProductController::class, 'destroy'])
            ->name('products.destroy');

        // ADMIN SAJA: TAMBAH, EDIT, DAN HAPUS SUPPLIER
        Route::get('/suppliers/create', [SupplierController::class, 'create'])
            ->name('suppliers.create');

        Route::post('/suppliers', [SupplierController::class, 'store'])
            ->name('suppliers.store');

        Route::get('/suppliers/{supplier}/edit', [SupplierController::class, 'edit'])
            ->name('suppliers.edit');

        Route::put('/suppliers/{supplier}', [SupplierController::class, 'update'])
            ->name('suppliers.update');

        Route::patch('/suppliers/{supplier}', [SupplierController::class, 'update']);

        Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])
            ->name('suppliers.destroy');

        // ADMIN SAJA: EDIT DAN HAPUS STOK MASUK
        Route::get('/purchases/{purchase}/edit', [
            PurchaseController::class,
            'edit'
        ])->name('purchases.edit');

        Route::put('/purchases/{purchase}', [
            PurchaseController::class,
            'update'
        ])->name('purchases.update');

        Route::patch('/purchases/{purchase}', [
            PurchaseController::class,
            'update'
        ]);

        Route::delete('/purchases/{purchase}', [
            PurchaseController::class,
            'destroy'
        ])->name('purchases.destroy');

        // ADMIN SAJA: LAPORAN KEUANGAN & CETAK
        Route::get('/financial-reports', [
            FinancialReportController::class,
            'index'
        ])->name('financial-reports.index');

        Route::get('/financial-reports/print', [
            FinancialReportController::class,
            'print'
        ])->name('financial-reports.print');
    });

    // LOGOUT
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});