@extends('layouts.app')

@section('title', 'Produk & Stok')

@section('breadcrumb', 'Produk')

@section('content')
<div class="card mb-4">
    <!-- Filter Card Header -->
    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between border-bottom">
        <h6 class="m-0 fw-bold text-slate-800">
            <i class="fa-solid fa-box-archive text-primary me-2"></i> Manajemen Produk & Stok Gudang
        </h6>
        @if(Auth::user()->isAdmin())
            <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm btn-animate">
                <i class="fa-solid fa-plus me-1"></i> Tambah Produk
            </a>
        @endif
    </div>
    
    <!-- Filter Panel -->
    <div class="card-body bg-light bg-opacity-50 border-bottom">
        <form action="{{ route('products.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-12 col-md-4">
                <label for="category_id" class="form-label fw-semibold small">Filter Kategori</label>
                <select name="category_id" id="category_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="col-12 col-md-5">
                <label for="search" class="form-label fw-semibold small">Pencarian Cepat</label>
                <div class="input-group input-group-sm">
                    <input type="text" name="search" id="search" class="form-control" placeholder="Cari kode atau nama produk..." value="{{ request('search') }}">
                    <button class="btn btn-outline-secondary" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                    @if(request('search') || request('category_id'))
                        <a href="{{ route('products.index') }}" class="btn btn-outline-danger"><i class="fa-solid fa-xmark"></i></a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Table Body -->
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle datatable">
                <thead>
                    <tr>
                        <th width="60">No</th>
                        <th width="70">Foto</th>
                        <th width="120">Kode</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Supplier</th>
                        <th class="text-end">Harga Beli</th>
                        <th class="text-end">Harga Jual</th>
                        <th class="text-center">Stok</th>
                        <th class="text-center">Min. Stok</th>
                        <th class="text-center">Status</th>
                        @if(Auth::user()->isAdmin())
                            <th width="130" class="text-center">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $index => $product)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                @if($product->foto)
                                    <img src="{{ asset('storage/' . $product->foto) }}" alt="Foto" width="45" height="45" class="rounded object-fit-cover shadow-sm">
                                @else
                                    <div class="bg-emerald-50 text-emerald-500 rounded d-flex align-items-center justify-content-center border" style="width: 45px; height: 45px;">
                                        <i class="fa-solid fa-image text-emerald-300"></i>
                                    </div>
                                @endif
                            </td>
                            <td><span class="badge bg-slate-100 text-slate-700 border fw-bold">{{ $product->kode_produk }}</span></td>
                            <td>
                                <div class="fw-semibold text-slate-800">{{ $product->nama_produk }}</div>
                                <span class="text-muted small" style="font-size: 0.75rem;">Satuan: {{ $product->satuan }}</span>
                            </td>
                            <td>{{ $product->category->nama_kategori ?? '-' }}</td>
                            <td>{{ $product->supplier->nama_supplier ?? '-' }}</td>
                            <td class="text-end fw-medium text-slate-600">Rp {{ number_format($product->harga_beli, 0, ',', '.') }}</td>
                            <td class="text-end fw-semibold text-emerald-600">Rp {{ number_format($product->harga_jual, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <span class="fw-bold fs-6 {{ $product->isLowStock() ? 'text-danger' : 'text-slate-800' }}">
                                    {{ $product->stok }}
                                </span>
                            </td>
                            <td class="text-center text-muted small">{{ $product->minimum_stok }}</td>
                            <td class="text-center">
                                @if($product->isLowStock())
                                    <span class="badge bg-danger bg-opacity-10 text-danger badge-pill border border-danger border-opacity-25" style="font-size: 0.75rem;">
                                        <i class="fa-solid fa-triangle-exclamation me-1"></i> Stok Tipis
                                    </span>
                                @else
                                    <span class="badge bg-success bg-opacity-10 text-success badge-pill border border-success border-opacity-25" style="font-size: 0.75rem;">
                                        <i class="fa-solid fa-circle-check me-1"></i> Aman
                                    </span>
                                @endif
                            </td>
                            @if(Auth::user()->isAdmin())
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-outline-warning btn-sm" title="Edit Produk">
                                            <i class="fa-solid fa-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="confirmDelete('{{ $product->id }}', 'Produk &quot;{{ $product->nama_produk }}&quot; akan dihapus!')" title="Hapus Produk">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" id="deleteForm-{{ $product->id }}" class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
