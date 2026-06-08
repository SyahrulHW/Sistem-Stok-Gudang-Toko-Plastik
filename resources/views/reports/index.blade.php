@extends('layouts.app')

@section('title', 'Laporan Stok & Transaksi')

@section('breadcrumb', 'Analitik Laporan')

@section('content')
<!-- Filter Panel -->
<div class="card mb-4">
    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
        <h6 class="m-0 fw-bold text-slate-800">
            <i class="fa-solid fa-filter text-primary me-2"></i> Filter Parameter Laporan Gudang
        </h6>
    </div>
    
    <div class="card-body">
        <form action="{{ route('reports.index') }}" method="GET" class="row g-3 align-items-end">
            <!-- Tipe Laporan -->
            <div class="col-12 col-md-3">
                <label for="type" class="form-label fw-semibold small">Tipe Laporan</label>
                <select name="type" id="type" class="form-select">
                    <option value="barang_masuk" {{ $type == 'barang_masuk' ? 'selected' : '' }}>Barang Masuk</option>
                    <option value="barang_keluar" {{ $type == 'barang_keluar' ? 'selected' : '' }}>Barang Keluar</option>
                    <option value="stok_gudang" {{ $type == 'stok_gudang' ? 'selected' : '' }}>Stok Gudang (Terkini)</option>
                    <option value="supplier" {{ $type == 'supplier' ? 'selected' : '' }}>Laporan Supplier</option>
                </select>
            </div>
            
            <!-- Rentang Filter -->
            <div class="col-12 col-md-3" id="filterRangeGroup">
                <label for="filter" class="form-label fw-semibold small">Filter Waktu</label>
                <select name="filter" id="filter" class="form-select">
                    <option value="hari" {{ $filter == 'hari' ? 'selected' : '' }}>Harian</option>
                    <option value="minggu" {{ $filter == 'minggu' ? 'selected' : '' }}>Mingguan</option>
                    <option value="bulan" {{ $filter == 'bulan' ? 'selected' : '' }}>Bulanan</option>
                    <option value="tahun" {{ $filter == 'tahun' ? 'selected' : '' }}>Tahunan</option>
                </select>
            </div>
            
            <!-- Tanggal Acuan -->
            <div class="col-12 col-md-3" id="dateInputGroup">
                <label for="date" class="form-label fw-semibold small">Tanggal Acuan</label>
                <input type="date" name="date" id="date" class="form-control" value="{{ $dateInput }}">
            </div>
            
            <!-- Tombol Filter -->
            <div class="col-12 col-md-3">
                <button type="submit" class="btn btn-primary w-100 py-2">
                    <i class="fa-solid fa-magnifying-glass me-1"></i> Tampilkan Laporan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Laporan Hasil preview -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex flex-column flex-sm-row align-items-sm-center justify-content-between border-bottom gap-2">
        <div>
            <h6 class="m-0 fw-bold text-slate-800">
                <i class="fa-solid fa-file-lines text-emerald-500 me-2"></i> Preview Hasil Laporan
            </h6>
            @if($type !== 'stok_gudang' && $type !== 'supplier')
                <span class="text-muted small" style="font-size: 0.8rem;">Rentang: <strong>{{ $startDateStr }}</strong> s.d. <strong>{{ $endDateStr }}</strong></span>
            @else
                <span class="text-muted small" style="font-size: 0.8rem;">Data real-time stok gudang aktif saat ini</span>
            @endif
        </div>
        
        <!-- Export Actions -->
        @if($data->count() > 0)
            <div class="d-flex gap-2">
                <!-- PDF / Print -->
                <a href="{{ route('reports.print', request()->all()) }}" target="_blank" class="btn btn-outline-danger btn-sm px-3 btn-animate">
                    <i class="fa-solid fa-file-pdf me-1"></i> Cetak / PDF
                </a>
                <!-- Excel (CSV) -->
                <a href="{{ route('reports.export', request()->all()) }}" class="btn btn-outline-success btn-sm px-3 btn-animate">
                    <i class="fa-solid fa-file-excel me-1"></i> Unduh Excel
                </a>
            </div>
        @endif
    </div>
    
    <div class="card-body">
        @if($data->count() > 0)
            <div class="table-responsive shadow-none border rounded">
                <table class="table table-hover align-middle">
                    
                    <!-- TABLE HEADERS & ROWS - RENDERED DYNAMICALLY BY REPORT TYPE -->
                    
                    <!-- 1. BARANG MASUK -->
                    @if($type === 'barang_masuk')
                        <thead>
                            <tr>
                                <th width="60">No</th>
                                <th>No. Transaksi</th>
                                <th>Tanggal</th>
                                <th>Produk</th>
                                <th>Supplier</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-end">Harga Beli</th>
                                <th class="text-end">Total Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $grandTotal = 0; @endphp
                            @foreach($data as $index => $row)
                                @php $grandTotal += $row->total_harga; @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><span class="badge bg-slate-100 text-slate-700 border font-monospace fw-semibold">{{ $row->nomor_transaksi }}</span></td>
                                    <td>{{ date('d/m/Y', strtotime($row->tanggal)) }}</td>
                                    <td>
                                        <div class="fw-semibold text-slate-800">{{ $row->product->nama_produk ?? '-' }}</div>
                                        <span class="text-muted small" style="font-size: 0.75rem;">Kode: {{ $row->product->kode_produk ?? '-' }}</span>
                                    </td>
                                    <td>{{ $row->supplier->nama_supplier ?? '-' }}</td>
                                    <td class="text-center fw-bold">{{ $row->jumlah }} {{ $row->product->satuan ?? '' }}</td>
                                    <td class="text-end">Rp {{ number_format($row->harga_beli, 0, ',', '.') }}</td>
                                    <td class="text-end fw-semibold text-emerald-600">Rp {{ number_format($row->total_harga, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                            <!-- Grand Total Row -->
                            <tr class="table-light fw-bold fs-6">
                                <td colspan="5" class="text-end py-3">GRAND TOTAL TRANSAKSI MASUK:</td>
                                <td class="text-center py-3">{{ $data->sum('jumlah') }} unit</td>
                                <td></td>
                                <td class="text-end text-emerald-700 py-3">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    
                    <!-- 2. BARANG KELUAR -->
                    @elseif($type === 'barang_keluar')
                        <thead>
                            <tr>
                                <th width="60">No</th>
                                <th>No. Transaksi</th>
                                <th>Tanggal</th>
                                <th>Produk</th>
                                <th class="text-center">Jumlah Keluar</th>
                                <th>Keterangan / Tujuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $index => $row)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><span class="badge bg-slate-100 text-slate-700 border font-monospace fw-semibold">{{ $row->nomor_transaksi }}</span></td>
                                    <td>{{ date('d/m/Y', strtotime($row->tanggal)) }}</td>
                                    <td>
                                        <div class="fw-semibold text-slate-800">{{ $row->product->nama_produk ?? '-' }}</div>
                                        <span class="text-muted small" style="font-size: 0.75rem;">Kode: {{ $row->product->kode_produk ?? '-' }}</span>
                                    </td>
                                    <td class="text-center fw-bold text-rose-600">{{ $row->jumlah }} {{ $row->product->satuan ?? '' }}</td>
                                    <td>{{ $row->keterangan ?? '-' }}</td>
                                </tr>
                            @endforeach
                            <tr class="table-light fw-bold fs-6">
                                <td colspan="4" class="text-end py-3">TOTAL BARANG KELUAR:</td>
                                <td class="text-center text-rose-700 py-3">{{ $data->sum('jumlah') }} unit</td>
                                <td></td>
                            </tr>
                        </tbody>
                    
                    <!-- 3. STOK GUDANG -->
                    @elseif($type === 'stok_gudang')
                        <thead>
                            <tr>
                                <th width="60">No</th>
                                <th>Kode Produk</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th>Supplier Utama</th>
                                <th class="text-center">Stok Saat Ini</th>
                                <th class="text-center">Batas Minimum</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $index => $row)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><span class="badge bg-slate-100 text-slate-700 border fw-bold">{{ $row->kode_produk }}</span></td>
                                    <td>
                                        <div class="fw-semibold text-slate-800">{{ $row->nama_produk }}</div>
                                        <span class="text-muted small" style="font-size: 0.75rem;">Satuan: {{ $row->satuan }}</span>
                                    </td>
                                    <td>{{ $row->category->nama_kategori ?? '-' }}</td>
                                    <td>{{ $row->supplier->nama_supplier ?? '-' }}</td>
                                    <td class="text-center fw-bold fs-6 {{ $row->isLowStock() ? 'text-danger' : 'text-slate-800' }}">{{ $row->stok }}</td>
                                    <td class="text-center text-muted small">{{ $row->minimum_stok }}</td>
                                    <td class="text-center">
                                        @if($row->isLowStock())
                                            <span class="badge bg-danger bg-opacity-10 text-danger badge-pill border border-danger border-opacity-25" style="font-size: 0.75rem;">
                                                Stok Tipis
                                            </span>
                                        @else
                                            <span class="badge bg-success bg-opacity-10 text-success badge-pill border border-success border-opacity-25" style="font-size: 0.75rem;">
                                                Aman
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    
                    <!-- 4. SUPPLIER -->
                    @elseif($type === 'supplier')
                        <thead>
                            <tr>
                                <th width="60">No</th>
                                <th width="120">Kode</th>
                                <th>Nama Supplier</th>
                                <th>Telepon</th>
                                <th>Email</th>
                                <th>Alamat</th>
                                <th class="text-center">Jumlah Pengiriman Barang Masuk</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $index => $row)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><span class="badge bg-slate-100 text-slate-700 border fw-bold">{{ $row->kode_supplier }}</span></td>
                                    <td class="fw-semibold text-slate-800">{{ $row->nama_supplier }}</td>
                                    <td>{{ $row->telepon ?? '-' }}</td>
                                    <td>{{ $row->email ?? '-' }}</td>
                                    <td><div class="text-truncate" style="max-width: 200px;">{{ $row->alamat ?? '-' }}</div></td>
                                    <td class="text-center"><span class="badge bg-primary rounded-pill px-3">{{ $row->barang_masuks_count }} Transaksi</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    @endif
                </table>
            </div>
        @else
            <div class="text-center py-5 text-muted border rounded">
                <i class="fa-regular fa-folder-open d-block fs-1 mb-3 text-slate-300"></i>
                <h6 class="fw-bold">Tidak Ada Data Ditemukan</h6>
                <p class="small px-4 mb-0">Silakan sesuaikan filter waktu, tipe laporan, atau tanggal acuan di atas.</p>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Toggle visibility of filter ranges and dates based on Report Type
        function checkReportType() {
            const reportType = $('#type').val();
            if (reportType === 'stok_gudang' || reportType === 'supplier') {
                // Stock levels & Supplier records are current real-time state, date range filters are not applicable
                $('#filterRangeGroup').hide();
                $('#dateInputGroup').hide();
            } else {
                $('#filterRangeGroup').show();
                $('#dateInputGroup').show();
            }
        }

        // Trigger on load & dropdown change
        $('#type').on('change', function() {
            checkReportType();
        });
        checkReportType();
    });
</script>
@endsection
