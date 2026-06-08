<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of suppliers.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $suppliers = Supplier::when($search, function ($query, $search) {
            $query->where('kode_supplier', 'like', "%{$search}%")
                  ->orWhere('nama_supplier', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%")
                  ->orWhere('telepon', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        })->orderBy('kode_supplier', 'asc')->get();

        return view('suppliers.index', compact('suppliers', 'search'));
    }

    /**
     * Show the form for creating a new supplier.
     */
    public function create()
    {
        return view('suppliers.create');
    }

    /**
     * Store a newly created supplier in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_supplier' => 'required|string|unique:suppliers,kode_supplier|max:50',
            'nama_supplier' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
        ], [
            'kode_supplier.required' => 'Kode Supplier wajib diisi.',
            'kode_supplier.unique' => 'Kode Supplier sudah terdaftar di sistem.',
            'nama_supplier.required' => 'Nama Supplier wajib diisi.',
            'email.email' => 'Format email tidak valid.',
        ]);

        Supplier::create($validated);

        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified supplier.
     */
    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified supplier in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'kode_supplier' => 'required|string|max:50|unique:suppliers,kode_supplier,' . $supplier->id,
            'nama_supplier' => 'required|string|max:255',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
        ], [
            'kode_supplier.required' => 'Kode Supplier wajib diisi.',
            'kode_supplier.unique' => 'Kode Supplier sudah terdaftar di sistem.',
            'nama_supplier.required' => 'Nama Supplier wajib diisi.',
            'email.email' => 'Format email tidak valid.',
        ]);

        $supplier->update($validated);

        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil diperbarui.');
    }

    /**
     * Remove the specified supplier from storage.
     */
    public function destroy(Supplier $supplier)
    {
        // Business Rule: Can't delete if supplier is linked to products
        if ($supplier->products()->count() > 0) {
            return redirect()->route('suppliers.index')
                ->with('error', 'Supplier tidak dapat dihapus karena sedang menyediakan produk di sistem.');
        }

        // Business Rule: Can't delete if supplier is linked to incoming goods
        if ($supplier->barangMasuks()->count() > 0) {
            return redirect()->route('suppliers.index')
                ->with('error', 'Supplier tidak dapat dihapus karena terdapat riwayat barang masuk dari supplier ini.');
        }

        $supplier->delete();

        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil dihapus.');
    }
}
