<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $categoryId = $request->input('category_id');

        $categories = Category::orderBy('nama_kategori', 'asc')->get();

        $products = Product::with(['category', 'supplier'])
            ->when($search, function ($query, $search) {
                $query->where('kode_produk', 'like', "%{$search}%")
                      ->orWhere('nama_produk', 'like', "%{$search}%")
                      ->orWhere('satuan', 'like', "%{$search}%");
            })
            ->when($categoryId, function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->orderBy('kode_produk', 'asc')
            ->get();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $products
            ]);
        }

        return view('products.index', compact('products', 'categories', 'search', 'categoryId'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = Category::orderBy('nama_kategori', 'asc')->get();
        $suppliers = Supplier::orderBy('nama_supplier', 'asc')->get();
        return view('products.create', compact('categories', 'suppliers'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_produk' => 'required|string|unique:products,kode_produk|max:50',
            'nama_produk' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'satuan' => 'required|string|max:50',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'minimum_stok' => 'required|integer|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'deskripsi' => 'nullable|string',
        ], [
            'kode_produk.required' => 'Kode produk wajib diisi.',
            'kode_produk.unique' => 'Kode produk sudah digunakan.',
            'nama_produk.required' => 'Nama produk wajib diisi.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'supplier_id.required' => 'Supplier wajib dipilih.',
            'satuan.required' => 'Satuan (pax, ikat, dll.) wajib diisi.',
            'harga_beli.required' => 'Harga beli wajib diisi.',
            'harga_jual.required' => 'Harga jual wajib diisi.',
            'stok.required' => 'Stok awal wajib diisi.',
            'minimum_stok.required' => 'Minimum stok wajib diisi.',
            'foto.image' => 'File harus berupa foto/gambar.',
            'foto.max' => 'Ukuran foto maksimal adalah 2MB.',
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('products', 'public');
            $validated['foto'] = $path;
        }

        $product = Product::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan.',
                'data' => $product
            ], 201);
        }

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $categories = Category::orderBy('nama_kategori', 'asc')->get();
        $suppliers = Supplier::orderBy('nama_supplier', 'asc')->get();
        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'kode_produk' => 'required|string|max:50|unique:products,kode_produk,' . $product->id,
            'nama_produk' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'satuan' => 'required|string|max:50',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'minimum_stok' => 'required|integer|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'deskripsi' => 'nullable|string',
        ], [
            'kode_produk.required' => 'Kode produk wajib diisi.',
            'kode_produk.unique' => 'Kode produk sudah digunakan.',
            'nama_produk.required' => 'Nama produk wajib diisi.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'supplier_id.required' => 'Supplier wajib dipilih.',
            'satuan.required' => 'Satuan wajib diisi.',
            'harga_beli.required' => 'Harga beli wajib diisi.',
            'harga_jual.required' => 'Harga jual wajib diisi.',
            'stok.required' => 'Stok wajib diisi.',
            'minimum_stok.required' => 'Minimum stok wajib diisi.',
            'foto.image' => 'File harus berupa foto/gambar.',
            'foto.max' => 'Ukuran foto maksimal adalah 2MB.',
        ]);

        if ($request->hasFile('foto')) {
            // Delete old photo if exists
            if ($product->foto) {
                Storage::disk('public')->delete($product->foto);
            }

            $path = $request->file('foto')->store('products', 'public');
            $validated['foto'] = $path;
        }

        $product->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil diperbarui.',
                'data' => $product
            ]);
        }

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        // Business Rule: Can't delete product if it has transaction history
        if ($product->barangMasuks()->count() > 0 || $product->barangKeluars()->count() > 0) {
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produk tidak dapat dihapus karena memiliki riwayat transaksi barang masuk atau keluar.'
                ], 400);
            }
            return redirect()->route('products.index')
                ->with('error', 'Produk tidak dapat dihapus karena memiliki riwayat transaksi barang masuk atau keluar.');
        }

        // Delete photo from storage
        if ($product->foto) {
            Storage::disk('public')->delete($product->foto);
        }

        $product->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dihapus.'
            ]);
        }

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
