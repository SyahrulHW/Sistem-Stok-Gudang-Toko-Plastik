@extends('layouts.app')

@section('title', 'Kategori Produk')

@section('breadcrumb', 'Kategori Produk')

@section('content')
<div class="card">
    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between border-bottom">
        <h6 class="m-0 fw-bold text-slate-800">
            <i class="fa-solid fa-tags text-primary me-2"></i> Daftar Kategori Produk
        </h6>
        @if(Auth::user()->isAdmin())
            <a href="{{ route('categories.create') }}" class="btn btn-primary btn-sm btn-animate">
                <i class="fa-solid fa-plus me-1"></i> Tambah Kategori
            </a>
        @endif
    </div>
    
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle datatable">
                <thead>
                    <tr>
                        <th width="80">No</th>
                        <th>Nama Kategori</th>
                        <th>Deskripsi</th>
                        @if(Auth::user()->isAdmin())
                            <th width="150" class="text-center">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $index => $category)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="fw-semibold text-slate-800">{{ $category->nama_kategori }}</td>
                            <td>{{ $category->deskripsi ?? '-' }}</td>
                            @if(Auth::user()->isAdmin())
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-outline-warning btn-sm" title="Edit Kategori">
                                            <i class="fa-solid fa-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="confirmDelete('{{ $category->id }}', 'Kategori &quot;{{ $category->nama_kategori }}&quot; akan dihapus!')" title="Hapus Kategori">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" id="deleteForm-{{ $category->id }}" class="d-none">
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
