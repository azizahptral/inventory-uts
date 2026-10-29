<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchasePayment;
use App\Models\Sale;
use App\Models\SaleItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinancialReportController extends Controller
{
    /**
     * Memastikan hanya Admin yang dapat mengakses laporan keuangan.
     */
    protected function checkAdminAuthorization(): void
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Akses Ditolak. Halaman Laporan Keuangan hanya dapat diakses oleh Administrator.');
        }
    }

    /**
     * Parse periode filter dan kembalikan tanggal awal, akhir, dan label periode.
     */
    protected function resolveDateRange(Request $request): array
    {
        $period = $request->input('period', 'monthly');
        $now = Carbon::now();

        $selectedDate = $request->input('date', $now->toDateString());
        $selectedWeekDate = $request->input('week_date', $now->toDateString());
        $selectedMonth = (int) $request->input('month', $now->month);
        $selectedYear = (int) $request->input('year', $now->year);
        $customStart = $request->input('start_date');
        $customEnd = $request->input('end_date');

        switch ($period) {
            case 'daily':
                $dateObj = Carbon::parse($selectedDate);
                $startDate = $dateObj->copy()->startOfDay();
                $endDate = $dateObj->copy()->endOfDay();
                $periodLabel = 'Harian (' . $dateObj->translatedFormat('d F Y') . ')';
                break;

            case 'weekly':
                $weekObj = Carbon::parse($selectedWeekDate);
                $startDate = $weekObj->copy()->startOfWeek();
                $endDate = $weekObj->copy()->endOfWeek();
                $periodLabel = 'Mingguan (' . $startDate->translatedFormat('d M Y') . ' - ' . $endDate->translatedFormat('d M Y') . ')';
                break;

            case 'yearly':
                $startDate = Carbon::createFromDate($selectedYear, 1, 1)->startOfYear();
                $endDate = Carbon::createFromDate($selectedYear, 12, 31)->endOfYear();
                $periodLabel = 'Tahunan (Tahun ' . $selectedYear . ')';
                break;

            case 'custom':
                $startDate = $customStart
                    ? Carbon::parse($customStart)->startOfDay()
                    : $now->copy()->startOfMonth();
                $endDate = $customEnd
                    ? Carbon::parse($customEnd)->endOfDay()
                    : $now->copy()->endOfDay();
                $periodLabel = 'Rentang Tanggal (' . $startDate->translatedFormat('d M Y') . ' s/d ' . $endDate->translatedFormat('d M Y') . ')';
                break;

            case 'monthly':
            default:
                $period = 'monthly';
                $monthDate = Carbon::createFromDate($selectedYear, $selectedMonth, 1);
                $startDate = $monthDate->copy()->startOfMonth();
                $endDate = $monthDate->copy()->endOfMonth();
                $periodLabel = 'Bulanan (' . $monthDate->translatedFormat('F Y') . ')';
                break;
        }

        return [
            'period' => $period,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'periodLabel' => $periodLabel,
            'selectedDate' => $selectedDate,
            'selectedWeekDate' => $selectedWeekDate,
            'selectedMonth' => $selectedMonth,
            'selectedYear' => $selectedYear,
            'customStart' => $customStart ?? $startDate->toDateString(),
            'customEnd' => $customEnd ?? $endDate->toDateString(),
        ];
    }

    /**
     * Menghitung seluruh metrik keuangan dalam rentang tanggal yang dipilih.
     */
    protected function calculateFinancialMetrics(Carbon $startDate, Carbon $endDate): array
    {
        $startDateStr = $startDate->toDateString();
        $endDateStr = $endDate->toDateString();

        // 1. Data Penjualan (Revenue)
        $sales = Sale::with(['user', 'items.product'])
            ->whereBetween('sale_date', [$startDateStr, $endDateStr])
            ->orderBy('sale_date', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        $totalRevenue = (float) $sales->sum('total_amount');
        $totalSalesCount = $sales->count();
        $totalItemsSold = 0;
        $totalCogs = 0.0; // Harga Pokok Penjualan (Modal)
        $productStats = [];

        foreach ($sales as $sale) {
            foreach ($sale->items as $item) {
                $qty = (int) $item->quantity;
                $totalItemsSold += $qty;

                $purchasePrice = (float) ($item->product->purchase_price ?? 0);
                $cogsItem = $purchasePrice * $qty;
                $revenueItem = (float) $item->subtotal;
                $profitItem = $revenueItem - $cogsItem;

                $totalCogs += $cogsItem;

                $prodId = $item->product_id;
                if (!isset($productStats[$prodId])) {
                    $productStats[$prodId] = [
                        'sku' => $item->product->sku ?? '-',
                        'name' => $item->product->name ?? 'Produk Dihapus',
                        'quantity' => 0,
                        'revenue' => 0.0,
                        'cogs' => 0.0,
                        'profit' => 0.0,
                    ];
                }

                $productStats[$prodId]['quantity'] += $qty;
                $productStats[$prodId]['revenue'] += $revenueItem;
                $productStats[$prodId]['cogs'] += $cogsItem;
                $productStats[$prodId]['profit'] += $profitItem;
            }
        }

        // Estimasi Laba Kotor & Margin
        $grossProfit = $totalRevenue - $totalCogs;
        $profitMargin = $totalRevenue > 0 ? ($grossProfit / $totalRevenue) * 100 : 0;

        // Urutkan produk terlaris berdasarkan omset pendapatan
        usort($productStats, fn($a, $b) => $b['revenue'] <=> $a['revenue']);
        $topProducts = array_slice($productStats, 0, 5);

        // 2. Data Pembelian Stok Masuk (Purchases)
        $purchases = Purchase::with(['supplier', 'user', 'items.product', 'payments'])
            ->whereBetween('purchase_date', [$startDateStr, $endDateStr])
            ->orderBy('purchase_date', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        $totalPurchaseInvoices = (float) $purchases->sum('total_amount');
        $totalPurchasesCount = $purchases->count();
        $periodDebtRemaining = 0.0;

        foreach ($purchases as $p) {
            $periodDebtRemaining += $p->remaining_amount;
        }

        // 3. Arus Kas Pengeluaran Pembayaran Supplier
        $payments = PurchasePayment::with(['purchase.supplier', 'user'])
            ->whereBetween('payment_date', [$startDateStr, $endDateStr])
            ->orderBy('payment_date', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        $totalCashOutflow = (float) $payments->sum('amount');

        // Arus Kas Penerimaan dari Penjualan (seluruh penjualan diselesaikan & lunas saat input)
        $totalCashInflow = $totalRevenue;

        // Arus Kas Bersih (Net Cash Flow)
        $netCashFlow = $totalCashInflow - $totalCashOutflow;

        // Total akumulasi seluruh sisa hutang ke supplier di database (semua periode)
        $allTimeDebt = (float) Purchase::all()->sum(fn($p) => $p->remaining_amount);

        // Estimasi nilai inventori saat ini (aset stok barang)
        $totalInventoryAsset = (float) Product::select(DB::raw('SUM(stock * purchase_price) as total_asset'))
            ->value('total_asset');

        return [
            'sales' => $sales,
            'purchases' => $purchases,
            'payments' => $payments,
            'totalRevenue' => $totalRevenue,
            'totalSalesCount' => $totalSalesCount,
            'totalItemsSold' => $totalItemsSold,
            'totalCogs' => $totalCogs,
            'grossProfit' => $grossProfit,
            'profitMargin' => $profitMargin,
            'topProducts' => $topProducts,
            'totalPurchaseInvoices' => $totalPurchaseInvoices,
            'totalPurchasesCount' => $totalPurchasesCount,
            'periodDebtRemaining' => $periodDebtRemaining,
            'totalCashInflow' => $totalCashInflow,
            'totalCashOutflow' => $totalCashOutflow,
            'netCashFlow' => $netCashFlow,
            'allTimeDebt' => $allTimeDebt,
            'totalInventoryAsset' => $totalInventoryAsset,
        ];
    }

    /**
     * Halaman Utama Laporan Keuangan (Hanya Admin).
     */
    public function index(Request $request)
    {
        $this->checkAdminAuthorization();

        $range = $this->resolveDateRange($request);
        $metrics = $this->calculateFinancialMetrics($range['startDate'], $range['endDate']);

        return view('reports.financial', array_merge($range, $metrics));
    }

    /**
     * Halaman Cetak Laporan Keuangan (Hanya Admin).
     */
    public function print(Request $request)
    {
        $this->checkAdminAuthorization();

        $range = $this->resolveDateRange($request);
        $metrics = $this->calculateFinancialMetrics($range['startDate'], $range['endDate']);

        return view('reports.print', array_merge($range, $metrics));
    }
}

