<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the warehouse dashboard.
     */
    public function index()
    {
        // 1. Calculate KPI Metrics
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalSuppliers = Supplier::count();
        $totalBarangMasuk = BarangMasuk::sum('jumlah');
        $totalBarangKeluar = BarangKeluar::sum('jumlah');
        $totalStokTersedia = Product::sum('stok');

        // 2. Fetch Low Stock Alerts (stok <= minimum_stok)
        $lowStockProducts = Product::with('category', 'supplier')
            ->whereRaw('stok <= minimum_stok')
            ->orderBy('stok', 'asc')
            ->get();

        // 3. Compile Monthly Stock In vs Stock Out Chart Data (Current Year)
        $currentYear = date('Y');

        $barangMasukMonthly = BarangMasuk::selectRaw('MONTH(tanggal) as month, SUM(jumlah) as total')
            ->whereYear('tanggal', $currentYear)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->all();

        $barangKeluarMonthly = BarangKeluar::selectRaw('MONTH(tanggal) as month, SUM(jumlah) as total')
            ->whereYear('tanggal', $currentYear)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->all();

        $monthsLabels = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $chartMasukData = [];
        $chartKeluarData = [];

        for ($m = 1; $m <= 12; $m++) {
            $chartMasukData[] = $barangMasukMonthly[$m] ?? 0;
            $chartKeluarData[] = $barangKeluarMonthly[$m] ?? 0;
        }

        // 4. Compile Category Stock Distribution for Doughnut Chart
        $categoriesData = Category::withSum('products', 'stok')
            ->get()
            ->filter(fn($cat) => ($cat->products_sum_stok ?? 0) > 0);

        $categoryLabels = $categoriesData->pluck('nama_kategori')->all();
        $categoryStocks = $categoriesData->pluck('products_sum_stok')->all();

        // If categories are empty, fallback to default labels
        if (empty($categoryLabels)) {
            $categoryLabels = ['Tidak ada data'];
            $categoryStocks = [0];
        }

        // 5. Recent Transactions log
        $recentMasuk = BarangMasuk::with('product')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentKeluar = BarangKeluar::with('product')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalProducts',
            'totalCategories',
            'totalSuppliers',
            'totalBarangMasuk',
            'totalBarangKeluar',
            'totalStokTersedia',
            'lowStockProducts',
            'monthsLabels',
            'chartMasukData',
            'chartKeluarData',
            'categoryLabels',
            'categoryStocks',
            'recentMasuk',
            'recentKeluar'
        ));
    }
}
