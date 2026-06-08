@extends('layouts.app')

@section('title', 'Edit Produk')

@section('breadcrumb', 'Produk / Edit')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
        <h6 class="m-0 fw-bold text-slate-800">
            <i class="fa-solid fa-pencil text-warning me-2"></i> Edit Data Produk Gudang
        </h6>
        <a href="{{ route('products.index') }}" class="btn btn-light border btn-sm">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
    
    <div class="card-body p-4">
        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row">
                <!-- Left Column - General Info -->
                <div class="col-12 col-lg-7 border-end-lg">
                    <h5 class="fw-bold mb-4 text-emerald-600 pb-2 border-bottom border-light" style="font-size: 1rem;"><i class="fa-solid fa-circle-info me-2"></i>Informasi Umum Produk</h5>
                    
                    <div class="row">
                        <!-- Kode Produk -->
                        <div class="col-md-5 mb-3">
                            <label for="kode_produk" class="form-label fw-semibold">Kode Produk <span class="text-danger">*</span></label>
                            <input type="text" name="kode_produk" id="kode_produk" class="form-control @error('kode_produk') is-invalid @enderror" placeholder="Contoh: PRD-001" value="{{ old('kode_produk', $product->kode_produk) }}" required>
                            @error('kode_produk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Nama Produk -->
                        <div class="col-md-7 mb-3">
                            <label for="nama_produk" class="form-label fw-semibold">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" name="nama_produk" id="nama_produk" class="form-control @error('nama_produk') is-invalid @enderror" placeholder="Contoh: Gelas Plastik 16oz" value="{{ old('nama_produk', $product->nama_produk) }}" required>
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
                                    <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
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
                                    <option value="{{ $sup->id }}" {{ old('supplier_id', $product->supplier_id) == $sup->id ? 'selected' : '' }}>
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
                            <input type="text" name="satuan" id="satuan" class="form-control @error('satuan') is-invalid @enderror" placeholder="Contoh: Pax, Ikat, Dus, Slop" value="{{ old('satuan', $product->satuan) }}" required>
                            @error('satuan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-7 mb-3">
                            <label for="foto" class="form-label fw-semibold">Foto Produk Baru (Maks 2MB)</label>
                            <input type="file" name="foto" id="foto" class="form-control @error('foto') is-invalid @enderror" accept="image/*">
                            <div class="form-text small" style="font-size: 0.75rem;">Biarkan kosong jika tidak ingin mengubah foto saat ini.</div>
                            @error('foto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Current Photo Indicator -->
                    @if($product->foto)
                        <div class="mb-3 d-flex align-items-center gap-3 bg-light p-2 rounded border">
                            <img src="{{ asset('storage/' . $product->foto) }}" alt="Foto Lama" width="60" height="60" class="rounded object-fit-cover shadow-sm">
                            <div>
                                <span class="d-block small text-muted">Foto saat ini terdaftar:</span>
                                <span class="badge bg-slate-200 text-slate-700 font-monospace" style="font-size: 0.7rem;">{{ basename($product->foto) }}</span>
                            </div>
                        </div>
                    @endif

                    <!-- Deskripsi -->
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label fw-semibold">Deskripsi Produk</label>
                        <textarea name="deskripsi" id="deskripsi" rows="3" class="form-control @error('deskripsi') is-invalid @enderror" placeholder="Keterangan lengkap produk...">{{ old('deskripsi', $product->deskripsi) }}</textarea>
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
                            <input type="number" name="harga_beli" id="harga_beli" class="form-control @error('harga_beli') is-invalid @enderror" placeholder="0" value="{{ old('harga_beli', intval($product->harga_beli)) }}" min="0" required>
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
                            <input type="number" name="harga_jual" id="harga_jual" class="form-control @error('harga_jual') is-invalid @enderror" placeholder="0" value="{{ old('harga_jual', intval($product->harga_jual)) }}" min="0" required>
                        </div>
                        @error('harga_jual')
                            <div class="text-danger small mt-1" style="font-size: 0.8rem;">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <!-- Stok Awal -->
                        <div class="col-md-6 mb-3">
                            <label for="stok" class="form-label fw-semibold">Stok Saat Ini <span class="text-danger">*</span></label>
                            <input type="number" name="stok" id="stok" class="form-control @error('stok') is-invalid @enderror" placeholder="0" value="{{ old('stok', $product->stok) }}" min="0" required>
                            @error('stok')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Minimum Stok -->
                        <div class="col-md-6 mb-3">
                            <label for="minimum_stok" class="form-label fw-semibold">Minimum Stok <span class="text-danger">*</span></label>
                            <input type="number" name="minimum_stok" id="minimum_stok" class="form-control @error('minimum_stok') is-invalid @enderror" placeholder="10" value="{{ old('minimum_stok', $product->minimum_stok) }}" min="0" required>
                            @error('minimum_stok')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary btn-animate py-2 fs-6">
                    <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Perubahan Produk
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
