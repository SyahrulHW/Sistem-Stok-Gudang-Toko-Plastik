<?php

namespace App\Http\Controllers;

use App\Models\BarangKeluar;
use App\Models\Product;
use Illuminate\Http\Request;

class BarangKeluarController extends Controller
{
    /**
     * Display a listing of outgoing goods.
     */
    public function index()
    {
        $barangKeluars = BarangKeluar::with('product')
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('barang_keluar.index', compact('barangKeluars'));
    }

    /**
     * Show the form for creating a new outgoing transaction.
     */
    public function create()
    {
        $products = Product::where('stok', '>', 0)->orderBy('nama_produk', 'asc')->get();
        
        // Auto-generate transaction number BK-YYYYMMDD-XXXX
        $today = date('Ymd');
        $lastTransaction = BarangKeluar::whereRaw("nomor_transaksi LIKE 'BK-{$today}%'")
            ->orderBy('nomor_transaksi', 'desc')
            ->first();

        if ($lastTransaction) {
            $lastNum = intval(substr($lastTransaction->nomor_transaksi, -4));
            $nextNum = str_pad($lastNum + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextNum = '0001';
        }
        $autoNomor = 'BK-' . $today . '-' . $nextNum;

        return view('barang_keluar.create', compact('products', 'autoNomor'));
    }

    /**
     * Store a newly created outgoing transaction in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nomor_transaksi' => 'required|string|unique:barang_keluars,nomor_transaksi|max:50',
            'product_id' => 'required|exists:products,id',
            'tanggal' => 'required|date',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ], [
            'nomor_transaksi.required' => 'Nomor Transaksi wajib diisi.',
            'nomor_transaksi.unique' => 'Nomor Transaksi sudah terdaftar.',
            'product_id.required' => 'Produk wajib dipilih.',
            'jumlah.required' => 'Jumlah barang wajib diisi.',
            'jumlah.min' => 'Jumlah barang minimal 1.',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Core Business Rule: Outgoing quantity must not exceed available stock
        if ($request->jumlah > $product->stok) {
            return back()->withErrors([
                'jumlah' => 'Jumlah barang keluar (' . $request->jumlah . ' ' . $product->satuan . ') melebihi stok tersedia (' . $product->stok . ' ' . $product->satuan . ').'
            ])->withInput();
        }

        BarangKeluar::create([
            'nomor_transaksi' => $request->nomor_transaksi,
            'product_id' => $request->product_id,
            'tanggal' => $request->tanggal,
            'jumlah' => $request->jumlah,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('barang-keluar.index')->with('success', 'Transaksi barang keluar berhasil disimpan.');
    }

    /**
     * Show the form for editing the outgoing transaction.
     */
    public function edit(BarangKeluar $barangKeluar)
    {
        // When editing, we also allow the product currently assigned, even if its stock is 0
        $products = Product::orderBy('nama_produk', 'asc')->get();
        return view('barang_keluar.edit', compact('barangKeluar', 'products'));
    }

    /**
     * Update the outgoing transaction in storage.
     */
    public function update(Request $request, BarangKeluar $barangKeluar)
    {
        $request->validate([
            'nomor_transaksi' => 'required|string|max:50|unique:barang_keluars,nomor_transaksi,' . $barangKeluar->id,
            'product_id' => 'required|exists:products,id',
            'tanggal' => 'required|date',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ], [
            'nomor_transaksi.required' => 'Nomor Transaksi wajib diisi.',
            'nomor_transaksi.unique' => 'Nomor Transaksi sudah terdaftar.',
            'product_id.required' => 'Produk wajib dipilih.',
            'jumlah.required' => 'Jumlah barang wajib diisi.',
            'jumlah.min' => 'Jumlah barang minimal 1.',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Core Business Rule: Outgoing quantity must not exceed available stock
        // For updates, available stock = current stock + original quantity (if product is the same)
        $originalJumlah = ($barangKeluar->product_id == $request->product_id) ? $barangKeluar->jumlah : 0;
        $maxAvailableStock = $product->stok + $originalJumlah;

        if ($request->jumlah > $maxAvailableStock) {
            return back()->withErrors([
                'jumlah' => 'Jumlah barang keluar (' . $request->jumlah . ' ' . $product->satuan . ') melebihi batas stok yang tersedia (' . $maxAvailableStock . ' ' . $product->satuan . ').'
            ])->withInput();
        }

        $barangKeluar->update([
            'nomor_transaksi' => $request->nomor_transaksi,
            'product_id' => $request->product_id,
            'tanggal' => $request->tanggal,
            'jumlah' => $request->jumlah,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('barang-keluar.index')->with('success', 'Transaksi barang keluar berhasil diperbarui.');
    }

    /**
     * Remove the outgoing transaction from storage.
     */
    public function destroy(BarangKeluar $barangKeluar)
    {
        // Eloquent booted model event automatically restores stock back to product when transaction deleted
        $barangKeluar->delete();

        return redirect()->route('barang-keluar.index')->with('success', 'Transaksi barang keluar berhasil dihapus.');
    }
}
