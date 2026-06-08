<?php

namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;

class BarangMasukController extends Controller
{
    /**
     * Display a listing of incoming goods.
     */
    public function index()
    {
        $barangMasuks = BarangMasuk::with(['product', 'supplier'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $barangMasuks
            ]);
        }

        return view('barang_masuk.index', compact('barangMasuks'));
    }

    /**
     * Show the form for creating a new incoming transaction.
     */
    public function create()
    {
        $products = Product::orderBy('nama_produk', 'asc')->get();
        $suppliers = Supplier::orderBy('nama_supplier', 'asc')->get();
        
        // Auto-generate transaction number BM-YYYYMMDD-XXXX
        $today = date('Ymd');
        $lastTransaction = BarangMasuk::whereRaw("nomor_transaksi LIKE 'BM-{$today}%'")
            ->orderBy('nomor_transaksi', 'desc')
            ->first();

        if ($lastTransaction) {
            $lastNum = intval(substr($lastTransaction->nomor_transaksi, -4));
            $nextNum = str_pad($lastNum + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextNum = '0001';
        }
        $autoNomor = 'BM-' . $today . '-' . $nextNum;

        return view('barang_masuk.create', compact('products', 'suppliers', 'autoNomor'));
    }

    /**
     * Store a newly created incoming transaction in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_transaksi' => 'required|string|unique:barang_masuks,nomor_transaksi|max:50',
            'product_id' => 'required|exists:products,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'tanggal' => 'required|date',
            'jumlah' => 'required|integer|min:1',
            'harga_beli' => 'required|numeric|min:0',
        ], [
            'nomor_transaksi.required' => 'Nomor Transaksi wajib diisi.',
            'nomor_transaksi.unique' => 'Nomor Transaksi sudah terdaftar.',
            'product_id.required' => 'Produk wajib dipilih.',
            'supplier_id.required' => 'Supplier wajib dipilih.',
            'jumlah.required' => 'Jumlah barang wajib diisi.',
            'jumlah.min' => 'Jumlah barang minimal 1.',
            'harga_beli.required' => 'Harga beli wajib diisi.',
        ]);

        $validated['total_harga'] = $validated['jumlah'] * $validated['harga_beli'];

        $barangMasuk = BarangMasuk::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Transaksi barang masuk berhasil disimpan.',
                'data' => $barangMasuk
            ], 201);
        }

        return redirect()->route('barang-masuk.index')->with('success', 'Transaksi barang masuk berhasil disimpan.');
    }

    /**
     * Show the form for editing the incoming transaction.
     */
    public function edit(BarangMasuk $barangMasuk)
    {
        $products = Product::orderBy('nama_produk', 'asc')->get();
        $suppliers = Supplier::orderBy('nama_supplier', 'asc')->get();
        return view('barang_masuk.edit', compact('barangMasuk', 'products', 'suppliers'));
    }

    /**
     * Update the incoming transaction in storage.
     */
    public function update(Request $request, BarangMasuk $barangMasuk)
    {
        $validated = $request->validate([
            'nomor_transaksi' => 'required|string|max:50|unique:barang_masuks,nomor_transaksi,' . $barangMasuk->id,
            'product_id' => 'required|exists:products,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'tanggal' => 'required|date',
            'jumlah' => 'required|integer|min:1',
            'harga_beli' => 'required|numeric|min:0',
        ], [
            'nomor_transaksi.required' => 'Nomor Transaksi wajib diisi.',
            'nomor_transaksi.unique' => 'Nomor Transaksi sudah terdaftar.',
            'product_id.required' => 'Produk wajib dipilih.',
            'supplier_id.required' => 'Supplier wajib dipilih.',
            'jumlah.required' => 'Jumlah barang wajib diisi.',
            'jumlah.min' => 'Jumlah barang minimal 1.',
            'harga_beli.required' => 'Harga beli wajib diisi.',
        ]);

        $validated['total_harga'] = $validated['jumlah'] * $validated['harga_beli'];

        $barangMasuk->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Transaksi barang masuk berhasil diperbarui.',
                'data' => $barangMasuk
            ]);
        }

        return redirect()->route('barang-masuk.index')->with('success', 'Transaksi barang masuk berhasil diperbarui.');
    }

    /**
     * Remove the incoming transaction from storage.
     */
    public function destroy(BarangMasuk $barangMasuk)
    {
        // Eloquent booted model event automatically subtracts stock from product when transaction deleted
        $barangMasuk->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Transaksi barang masuk berhasil dihapus.'
            ]);
        }

        return redirect()->route('barang-masuk.index')->with('success', 'Transaksi barang masuk berhasil dihapus.');
    }
}
