@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('breadcrumb', 'Produk / Tambah')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
        <h6 class="m-0 fw-bold text-slate-800">
            <i class="fa-solid fa-plus text-primary me-2"></i> Tambah Produk Baru ke Gudang
        </h6>
        <a href="{{ route('products.index') }}" class="btn btn-light border btn-sm">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
    
    <div class="card-body p-4">
        @php
            // Pre-generate product code PRD-00X
            $maxId = \App\Models\Product::max('id') ?? 0;
            $nextCode = 'PRD-' . str_pad($maxId + 1, 3, '0', STR_PAD_LEFT);
        @endphp
        
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row">
                <!-- Left Column - General Info -->
                <div class="col-12 col-lg-7 border-end-lg">
                    <h5 class="fw-bold mb-4 text-emerald-600 pb-2 border-bottom border-light" style="font-size: 1rem;"><i class="fa-solid fa-circle-info me-2"></i>Informasi Umum Produk</h5>
                    
                    <div class="row">
                        <!-- Kode Produk -->
                        <div class="col-md-5 mb-3">
                            <label for="kode_produk" class="form-label fw-semibold">Kode Produk <span class="text-danger">*</span></label>
                            <input type="text" name="kode_produk" id="kode_produk" class="form-control @error('kode_produk') is-invalid @enderror" placeholder="Contoh: PRD-001" value="{{ old('kode_produk', $nextCode) }}" required>
                            @error('kode_produk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Nama Produk -->
                        <div class="col-md-7 mb-3">
                            <label for="nama_produk" class="form-label fw-semibold">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" name="nama_produk" id="nama_produk" class="form-control @error('nama_produk') is-invalid @enderror" placeholder="Contoh: Gelas Plastik 16oz" value="{{ old('nama_produk') }}" required>
                            @error('nama_produk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <!-- Category ID -->
                        <div class="col-md-6 mb-3">
                            <label for="category_id" class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                            <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Supplier ID -->
                        <div class="col-md-6 mb-3">
                            <label for="supplier_id" class="form-label fw-semibold">Supplier Utama <span class="text-danger">*</span></label>
                            <select name="supplier_id" id="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror" required>
                                <option value="">Pilih Supplier</option>
                                @foreach($suppliers as $sup)
                                    <option value="{{ $sup->id }}" {{ old('supplier_id') == $sup->id ? 'selected' : '' }}>
                                        {{ $sup->nama_supplier }} ({{ $sup->kode_supplier }})
                                    </option>
                                @endforeach
                            </select>
                            @error('supplier_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Satuan & Upload Foto -->
                    <div class="row">
                        <div class="col-md-5 mb-3">
                            <label for="satuan" class="form-label fw-semibold">Satuan Barang <span class="text-danger">*</span></label>
                            <input type="text" name="satuan" id="satuan" class="form-control @error('satuan') is-invalid @enderror" placeholder="Contoh: Pax, Ikat, Dus, Slop" value="{{ old('satuan') }}" required>
                            @error('satuan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-7 mb-3">
                            <label for="foto" class="form-label fw-semibold">Foto Produk (Maks 2MB)</label>
                            <input type="file" name="foto" id="foto" class="form-control @error('foto') is-invalid @enderror" accept="image/*">
                            @error('foto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label fw-semibold">Deskripsi Produk</label>
                        <textarea name="deskripsi" id="deskripsi" rows="3" class="form-control @error('deskripsi') is-invalid @enderror" placeholder="Keterangan lengkap produk (ukuran, warna, ketebalan)...">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <!-- Right Column - Pricing & Stocks -->
                <div class="col-12 col-lg-5">
                    <h5 class="fw-bold mb-4 text-emerald-600 pb-2 border-bottom border-light" style="font-size: 1rem;"><i class="fa-solid fa-coins me-2"></i>Persediaan & Keuangan</h5>
                    
                    <!-- Harga Beli -->
                    <div class="mb-3">
                        <label for="harga_beli" class="form-label fw-semibold">Harga Beli Satuan (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="harga_beli" id="harga_beli" class="form-control @error('harga_beli') is-invalid @enderror" placeholder="0" value="{{ old('harga_beli') }}" min="0" required>
                        </div>
                        @error('harga_beli')
                            <div class="text-danger small mt-1" style="font-size: 0.8rem;">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Harga Jual -->
                    <div class="mb-3">
                        <label for="harga_jual" class="form-label fw-semibold">Harga Jual Satuan (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="harga_jual" id="harga_jual" class="form-control @error('harga_jual') is-invalid @enderror" placeholder="0" value="{{ old('harga_jual') }}" min="0" required>
                        </div>
                        @error('harga_jual')
                            <div class="text-danger small mt-1" style="font-size: 0.8rem;">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <!-- Stok Awal -->
                        <div class="col-md-6 mb-3">
                            <label for="stok" class="form-label fw-semibold">Stok Awal <span class="text-danger">*</span></label>
                            <input type="number" name="stok" id="stok" class="form-control @error('stok') is-invalid @enderror" placeholder="0" value="{{ old('stok', 0) }}" min="0" required>
                            @error('stok')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Minimum Stok -->
                        <div class="col-md-6 mb-3">
                            <label for="minimum_stok" class="form-label fw-semibold">Minimum Stok <span class="text-danger">*</span></label>
                            <input type="number" name="minimum_stok" id="minimum_stok" class="form-control @error('minimum_stok') is-invalid @enderror" placeholder="10" value="{{ old('minimum_stok', 10) }}" min="0" required>
                            @error('minimum_stok')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="bg-light p-3 border rounded mb-3" style="font-size: 0.8rem; color: #475569;">
                        <i class="fa-solid fa-circle-info text-emerald-500 me-1"></i>
                        <strong>Catatan Stok</strong>: Jika stok barang di gudang menyusut di bawah atau sama dengan <strong>Minimum Stok</strong>, sistem akan memicu peringatan visual (notifikasi berwarna merah) di dashboard utama.
                    </div>
                </div>
            </div>
            
            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary btn-animate py-2 fs-6">
                    <i class="fa-solid fa-floppy-disk me-1"></i> Daftarkan Produk & Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
