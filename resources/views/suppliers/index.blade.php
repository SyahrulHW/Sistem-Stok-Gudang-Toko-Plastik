@extends('layouts.app')

@section('title', 'Supplier')

@section('breadcrumb', 'Supplier')

@section('content')
<div class="card">
    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between border-bottom">
        <h6 class="m-0 fw-bold text-slate-800">
            <i class="fa-solid fa-truck-field text-primary me-2"></i> Daftar Supplier Partner
        </h6>
        @if(Auth::user()->isAdmin())
            <a href="{{ route('suppliers.create') }}" class="btn btn-primary btn-sm btn-animate">
                <i class="fa-solid fa-plus me-1"></i> Tambah Supplier
            </a>
        @endif
    </div>
    
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle datatable">
                <thead>
                    <tr>
                        <th width="80">No</th>
                        <th width="120">Kode Supplier</th>
                        <th>Nama Supplier</th>
                        <th>No. Telepon</th>
                        <th>Email Address</th>
                        <th>Alamat Lengkap</th>
                        @if(Auth::user()->isAdmin())
                            <th width="150" class="text-center">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($suppliers as $index => $supplier)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><span class="badge bg-slate-100 text-slate-700 border fw-bold">{{ $supplier->kode_supplier }}</span></td>
                            <td class="fw-semibold text-slate-800">{{ $supplier->nama_supplier }}</td>
                            <td>{{ $supplier->telepon ?? '-' }}</td>
                            <td>
                                @if($supplier->email)
                                    <a href="mailto:{{ $supplier->email }}" class="text-decoration-none text-emerald-600"><i class="fa-regular fa-envelope me-1"></i>{{ $supplier->email }}</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td><div class="text-truncate" style="max-width: 250px;">{{ $supplier->alamat ?? '-' }}</div></td>
                            @if(Auth::user()->isAdmin())
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-outline-warning btn-sm" title="Edit Supplier">
                                            <i class="fa-solid fa-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="confirmDelete('{{ $supplier->id }}', 'Supplier &quot;{{ $supplier->nama_supplier }}&quot; akan dihapus!')" title="Hapus Supplier">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                        <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" id="deleteForm-{{ $supplier->id }}" class="d-none">
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
