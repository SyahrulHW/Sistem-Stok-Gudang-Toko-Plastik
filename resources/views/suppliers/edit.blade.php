@extends('layouts.app')

@section('title', 'Edit Supplier')

@section('breadcrumb', 'Supplier / Edit')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <h6 class="m-0 fw-bold text-slate-800">
                    <i class="fa-solid fa-pencil text-warning me-2"></i> Edit Supplier Partner
                </h6>
                <a href="{{ route('suppliers.index') }}" class="btn btn-light border btn-sm">
                    <i class="fa-solid fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
            
            <div class="card-body p-4">
                <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <!-- Kode Supplier -->
                        <div class="col-md-6 mb-4">
                            <label for="kode_supplier" class="form-label fw-semibold">Kode Supplier <span class="text-danger">*</span></label>
                            <input type="text" name="kode_supplier" id="kode_supplier" class="form-control @error('kode_supplier') is-invalid @enderror" placeholder="Contoh: SUP-001" value="{{ old('kode_supplier', $supplier->kode_supplier) }}" required>
                            @error('kode_supplier')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Nama Supplier -->
                        <div class="col-md-6 mb-4">
                            <label for="nama_supplier" class="form-label fw-semibold">Nama Supplier <span class="text-danger">*</span></label>
                            <input type="text" name="nama_supplier" id="nama_supplier" class="form-control @error('nama_supplier') is-invalid @enderror" placeholder="Contoh: PT Sinar Abadi" value="{{ old('nama_supplier', $supplier->nama_supplier) }}" required>
                            @error('nama_supplier')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <!-- Nomor Telepon -->
                        <div class="col-md-6 mb-4">
                            <label for="telepon" class="form-label fw-semibold">Nomor Telepon</label>
                            <input type="text" name="telepon" id="telepon" class="form-control @error('telepon') is-invalid @enderror" placeholder="Contoh: 08123456789" value="{{ old('telepon', $supplier->telepon) }}">
                            @error('telepon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Email -->
                        <div class="col-md-6 mb-4">
                            <label for="email" class="form-label fw-semibold">Email Address</label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="Contoh: sales@sinarabadi.com" value="{{ old('email', $supplier->email) }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Alamat -->
                    <div class="mb-4">
                        <label for="alamat" class="form-label fw-semibold">Alamat Lengkap</label>
                        <textarea name="alamat" id="alamat" rows="4" class="form-control @error('alamat') is-invalid @enderror" placeholder="Jl. Raya No. X, Kota, Provinsi...">{{ old('alamat', $supplier->alamat) }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-animate py-2">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Perbarui Supplier
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
