@extends('layouts.app')

@section('title', 'Barang Keluar')

@section('breadcrumb', 'Barang Keluar')

@section('content')
<div class="card">
    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between border-bottom">
        <h6 class="m-0 fw-bold text-slate-800">
            <i class="fa-solid fa-circle-arrow-up text-rose-500 me-2"></i> Riwayat Transaksi Barang Keluar
        </h6>
        <a href="{{ route('barang-keluar.create') }}" class="btn btn-primary btn-sm btn-animate">
            <i class="fa-solid fa-plus me-1"></i> Catat Barang Keluar
        </a>
    </div>
    
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle datatable">
                <thead>
                    <tr>
                        <th width="60">No</th>
                        <th width="140">No. Transaksi</th>
                        <th width="120">Tanggal</th>
                        <th>Produk</th>
                        <th class="text-center">Jumlah Keluar</th>
                        <th>Keterangan</th>
                        <th width="120" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($barangKeluars as $index => $row)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><span class="badge bg-slate-100 text-slate-700 border fw-bold">{{ $row->nomor_transaksi }}</span></td>
                            <td>{{ date('d/m/Y', strtotime($row->tanggal)) }}</td>
                            <td>
                                <div class="fw-semibold text-slate-850">{{ $row->product->nama_produk ?? '-' }}</div>
                                <span class="text-muted small" style="font-size: 0.75rem;">Kode: {{ $row->product->kode_produk ?? '-' }}</span>
                            </td>
                            <td class="text-center fw-bold text-rose-600">{{ $row->jumlah }} {{ $row->product->satuan ?? '' }}</td>
                            <td>{{ $row->keterangan ?? '-' }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('barang-keluar.edit', $row->id) }}" class="btn btn-outline-warning btn-sm" title="Edit Transaksi">
                                        <i class="fa-solid fa-pencil"></i>
                                    </a>
                                    @if(Auth::user()->isAdmin())
                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="confirmDelete('{{ $row->id }}', 'Transaksi barang keluar &quot;{{ $row->nomor_transaksi }}&quot; akan dihapus! Stok produk akan otomatis dikembalikan ke gudang!')" title="Hapus Transaksi">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                        <form action="{{ route('barang-keluar.destroy', $row->id) }}" method="POST" id="deleteForm-{{ $row->id }}" class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
