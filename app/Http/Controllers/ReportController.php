<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display report filters and compilation table.
     */
    public function index(Request $request)
    {
        $type = $request->input('type', 'barang_masuk');
        $filter = $request->input('filter', 'bulan');
        $dateInput = $request->input('date', date('Y-m-d'));

        $dateRange = $this->getDateRange($filter, $dateInput);
        $data = $this->getReportData($type, $dateRange['start'], $dateRange['end']);

        $startDateStr = $dateRange['start']->translatedFormat('d F Y');
        $endDateStr = $dateRange['end']->translatedFormat('d F Y');

        return view('reports.index', compact('data', 'type', 'filter', 'dateInput', 'startDateStr', 'endDateStr'));
    }

    /**
     * Stream a downloadable CSV of the filtered report data.
     */
    public function exportCsv(Request $request)
    {
        $type = $request->input('type', 'barang_masuk');
        $filter = $request->input('filter', 'bulan');
        $dateInput = $request->input('date', date('Y-m-d'));

        $dateRange = $this->getDateRange($filter, $dateInput);
        $data = $this->getReportData($type, $dateRange['start'], $dateRange['end']);

        $filename = "Laporan_" . $type . "_" . date('Ymd_His') . ".csv";

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function () use ($type, $data) {
            $file = fopen('php://output', 'w');

            // Add UTF-8 BOM so Excel opens it with correct Indonesian formatting and special characters
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            if ($type === 'barang_masuk') {
                fputcsv($file, ['No. Transaksi', 'Tanggal', 'Kode Produk', 'Nama Produk', 'Supplier', 'Jumlah', 'Harga Beli (Rp)', 'Total Harga (Rp)']);
                foreach ($data as $row) {
                    fputcsv($file, [
                        $row->nomor_transaksi,
                        $row->tanggal,
                        $row->product->kode_produk ?? '-',
                        $row->product->nama_produk ?? '-',
                        $row->supplier->nama_supplier ?? '-',
                        $row->jumlah,
                        number_format($row->harga_beli, 0, ',', ''),
                        number_format($row->total_harga, 0, ',', '')
                    ]);
                }
            } elseif ($type === 'barang_keluar') {
                fputcsv($file, ['No. Transaksi', 'Tanggal', 'Kode Produk', 'Nama Produk', 'Jumlah', 'Keterangan']);
                foreach ($data as $row) {
                    fputcsv($file, [
                        $row->nomor_transaksi,
                        $row->tanggal,
                        $row->product->kode_produk ?? '-',
                        $row->product->nama_produk ?? '-',
                        $row->jumlah,
                        $row->keterangan ?? '-'
                    ]);
                }
            } elseif ($type === 'stok_gudang') {
                fputcsv($file, ['Kode Produk', 'Nama Produk', 'Kategori', 'Supplier', 'Satuan', 'Harga Beli (Rp)', 'Harga Jual (Rp)', 'Stok', 'Min. Stok', 'Status Stok']);
                foreach ($data as $row) {
                    fputcsv($file, [
                        $row->kode_produk,
                        $row->nama_produk,
                        $row->category->nama_kategori ?? '-',
                        $row->supplier->nama_supplier ?? '-',
                        $row->satuan,
                        number_format($row->harga_beli, 0, ',', ''),
                        number_format($row->harga_jual, 0, ',', ''),
                        $row->stok,
                        $row->minimum_stok,
                        $row->isLowStock() ? 'STOK TIPIS' : 'AMAN'
                    ]);
                }
            } elseif ($type === 'supplier') {
                fputcsv($file, ['Kode Supplier', 'Nama Supplier', 'Alamat', 'Telepon', 'Email', 'Total Transaksi Masuk']);
                foreach ($data as $row) {
                    fputcsv($file, [
                        $row->kode_supplier,
                        $row->nama_supplier,
                        $row->alamat ?? '-',
                        $row->telepon ?? '-',
                        $row->email ?? '-',
                        $row->barang_masuks_count ?? 0
                    ]);
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Display a clean, printer-optimized HTML page of the report.
     */
    public function print(Request $request)
    {
        $type = $request->input('type', 'barang_masuk');
        $filter = $request->input('filter', 'bulan');
        $dateInput = $request->input('date', date('Y-m-d'));

        $dateRange = $this->getDateRange($filter, $dateInput);
        $data = $this->getReportData($type, $dateRange['start'], $dateRange['end']);

        $startDateStr = $dateRange['start']->translatedFormat('d F Y');
        $endDateStr = $dateRange['end']->translatedFormat('d F Y');

        return view('reports.print', compact('data', 'type', 'filter', 'dateInput', 'startDateStr', 'endDateStr'));
    }

    /**
     * Compute Carbon Start and End Dates based on Filter Selection
     */
    private function getDateRange(string $filter, string $dateStr): array
    {
        $date = Carbon::parse($dateStr);

        switch ($filter) {
            case 'hari':
                return [
                    'start' => $date->copy()->startOfDay(),
                    'end' => $date->copy()->endOfDay()
                ];
            case 'minggu':
                return [
                    'start' => $date->copy()->startOfWeek(),
                    'end' => $date->copy()->endOfWeek()
                ];
            case 'tahun':
                return [
                    'start' => $date->copy()->startOfYear(),
                    'end' => $date->copy()->endOfYear()
                ];
            case 'bulan':
            default:
                return [
                    'start' => $date->copy()->startOfMonth(),
                    'end' => $date->copy()->endOfMonth()
                ];
        }
    }

    /**
     * Fetch relevant records from the database
     */
    private function getReportData(string $type, Carbon $start, Carbon $end)
    {
        $startStr = $start->format('Y-m-d');
        $endStr = $end->format('Y-m-d');

        switch ($type) {
            case 'barang_keluar':
                return BarangKeluar::with('product')
                    ->whereBetween('tanggal', [$startStr, $endStr])
                    ->orderBy('tanggal', 'asc')
                    ->get();
            case 'stok_gudang':
                // Stock is current state, so range is ignored, but showing all products with details
                return Product::with(['category', 'supplier'])
                    ->orderBy('stok', 'asc')
                    ->get();
            case 'supplier':
                // Supplier listing with count of deliveries
                return Supplier::withCount('barangMasuks')
                    ->orderBy('kode_supplier', 'asc')
                    ->get();
            case 'barang_masuk':
            default:
                return BarangMasuk::with(['product', 'supplier'])
                    ->whereBetween('tanggal', [$startStr, $endStr])
                    ->orderBy('tanggal', 'asc')
                    ->get();
        }
    }
}
