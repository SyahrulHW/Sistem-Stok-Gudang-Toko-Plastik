@extends('layouts.app')

@section('title', 'Barang Masuk')

@section('breadcrumb', 'Barang Masuk')

@section('content')
<div class="card">
    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between border-bottom">
        <h6 class="m-0 fw-bold text-slate-800">
            <i class="fa-solid fa-circle-arrow-down text-emerald-500 me-2"></i> Riwayat Transaksi Barang Masuk
        </h6>
        <a href="{{ route('barang-masuk.create') }}" class="btn btn-primary btn-sm btn-animate">
            <i class="fa-solid fa-plus me-1"></i> Catat Barang Masuk
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
                        <th>Supplier</th>
                        <th class="text-center">Jumlah</th>
                        <th class="text-end">Harga Beli</th>
                        <th class="text-end">Total Harga</th>
                        <th width="120" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($barangMasuks as $index => $row)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><span class="badge bg-slate-100 text-slate-700 border fw-bold">{{ $row->nomor_transaksi }}</span></td>
                            <td>{{ date('d/m/Y', strtotime($row->tanggal)) }}</td>
                            <td>
                                <div class="fw-semibold text-slate-850">{{ $row->product->nama_produk ?? '-' }}</div>
                                <span class="text-muted small" style="font-size: 0.75rem;">Kode: {{ $row->product->kode_produk ?? '-' }}</span>
                            </td>
                            <td>{{ $row->supplier->nama_supplier ?? '-' }}</td>
                            <td class="text-center fw-bold">{{ $row->jumlah }} {{ $row->product->satuan ?? '' }}</td>
                            <td class="text-end text-slate-600">Rp {{ number_format($row->harga_beli, 0, ',', '.') }}</td>
                            <td class="text-end fw-semibold text-emerald-600">Rp {{ number_format($row->total_harga, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('barang-masuk.edit', $row->id) }}" class="btn btn-outline-warning btn-sm" title="Edit Transaksi">
                                        <i class="fa-solid fa-pencil"></i>
                                    </a>
                                    @if(Auth::user()->isAdmin())
                                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="confirmDelete('{{ $row->id }}', 'Transaksi barang masuk &quot;{{ $row->nomor_transaksi }}&quot; akan dihapus! Stok produk akan otomatis dikurangi!')" title="Hapus Transaksi">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                        <form action="{{ route('barang-masuk.destroy', $row->id) }}" method="POST" id="deleteForm-{{ $row->id }}" class="d-none">
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
