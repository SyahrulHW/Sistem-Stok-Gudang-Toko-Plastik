@extends('layouts.app')

@yield('title', 'Dashboard')

@section('breadcrumb', 'Dashboard')

@section('content')
<div class="row g-4 mb-4">
    <!-- Total Produk Card -->
    <div class="col-6 col-lg-4 col-xl-2">
        <div class="card card-hover h-100 bg-white border-start border-emerald-500 border-4">
            <div class="card-body p-3 d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="card-subtitle text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.7rem; letter-spacing: 0.5px;">Produk</h6>
                    <h4 class="card-title mb-0 fw-bold text-slate-800">{{ $totalProducts }}</h4>
                </div>
                <div class="bg-emerald-50 text-emerald-500 rounded p-2 fs-3">
                    <i class="fa-solid fa-box"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Kategori Card -->
    <div class="col-6 col-lg-4 col-xl-2">
        <div class="card card-hover h-100 bg-white border-start border-sky-500 border-4">
            <div class="card-body p-3 d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="card-subtitle text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.7rem; letter-spacing: 0.5px;">Kategori</h6>
                    <h4 class="card-title mb-0 fw-bold text-slate-800">{{ $totalCategories }}</h4>
                </div>
                <div class="bg-sky-50 text-sky-500 rounded p-2 fs-3">
                    <i class="fa-solid fa-tags"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Supplier Card -->
    <div class="col-6 col-lg-4 col-xl-2">
        <div class="card card-hover h-100 bg-white border-start border-purple-500 border-4">
            <div class="card-body p-3 d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="card-subtitle text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.7rem; letter-spacing: 0.5px;">Supplier</h6>
                    <h4 class="card-title mb-0 fw-bold text-slate-800">{{ $totalSuppliers }}</h4>
                </div>
                <div class="bg-purple-50 text-purple-500 rounded p-2 fs-3">
                    <i class="fa-solid fa-truck-field"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Barang Masuk Card -->
    <div class="col-6 col-lg-4 col-xl-2">
        <div class="card card-hover h-100 bg-white border-start border-teal-500 border-4">
            <div class="card-body p-3 d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="card-subtitle text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.7rem; letter-spacing: 0.5px;">Barang Masuk</h6>
                    <h4 class="card-title mb-0 fw-bold text-slate-800">{{ number_format($totalBarangMasuk, 0, ',', '.') }}</h4>
                </div>
                <div class="bg-teal-50 text-teal-500 rounded p-2 fs-3">
                    <i class="fa-solid fa-circle-arrow-down"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Barang Keluar Card -->
    <div class="col-6 col-lg-4 col-xl-2">
        <div class="card card-hover h-100 bg-white border-start border-rose-500 border-4">
            <div class="card-body p-3 d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="card-subtitle text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.7rem; letter-spacing: 0.5px;">Barang Keluar</h6>
                    <h4 class="card-title mb-0 fw-bold text-slate-800">{{ number_format($totalBarangKeluar, 0, ',', '.') }}</h4>
                </div>
                <div class="bg-rose-50 text-rose-500 rounded p-2 fs-3">
                    <i class="fa-solid fa-circle-arrow-up"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Stok Tersedia Card -->
    <div class="col-6 col-lg-4 col-xl-2">
        <div class="card card-hover h-100 bg-white border-start border-indigo-500 border-4">
            <div class="card-body p-3 d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="card-subtitle text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.7rem; letter-spacing: 0.5px;">Stok Gudang</h6>
                    <h4 class="card-title mb-0 fw-bold text-slate-800">{{ number_format($totalStokTersedia, 0, ',', '.') }}</h4>
                </div>
                <div class="bg-indigo-50 text-indigo-500 rounded p-2 fs-3">
                    <i class="fa-solid fa-warehouse"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Chart Column (Stock Flow & Movement) -->
    <div class="col-12 col-xl-8">
        <div class="card h-100">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between border-bottom">
                <h6 class="m-0 fw-bold text-slate-800">
                    <i class="fa-solid fa-chart-column text-emerald-500 me-2"></i> Grafik Arus Barang Masuk & Keluar (Tahun {{ date('Y') }})
                </h6>
            </div>
            <div class="card-body">
                <div style="position: relative; height: 350px;">
                    <canvas id="stockFlowChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Limit / Warnings Alert list -->
    <div class="col-12 col-md-6 col-xl-4">
        <div class="card h-100">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <h6 class="m-0 fw-bold text-slate-800">
                    <i class="fa-solid fa-triangle-exclamation text-rose-500 me-2"></i> Notifikasi Stok Menipis ({{ $lowStockProducts->count() }})
                </h6>
                <span class="badge bg-danger rounded-pill">{{ $lowStockProducts->count() }} Peringatan</span>
            </div>
            <div class="card-body p-0" style="max-height: 350px; overflow-y: auto;">
                @if($lowStockProducts->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($lowStockProducts as $lowProduct)
                            <div class="list-group-item p-3 d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="fw-semibold text-slate-800" style="font-size: 0.9rem;">{{ $lowProduct->nama_produk }}</div>
                                    <div class="text-muted small">
                                        Kode: <strong>{{ $lowProduct->kode_produk }}</strong> | Kategori: {{ $lowProduct->category->nama_kategori }}
                                    </div>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-danger rounded-pill text-white fw-bold d-block mb-1" style="font-size: 0.8rem;">
                                        {{ $lowProduct->stok }} {{ $lowProduct->satuan }}
                                    </span>
                                    <span class="text-muted small" style="font-size: 0.7rem;">Minimum: {{ $lowProduct->minimum_stok }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fa-solid fa-circle-check text-emerald-500 fs-1 mb-3"></i>
                        <h6 class="fw-semibold text-slate-800">Semua Stok Aman</h6>
                        <p class="text-muted small px-4">Tidak ada produk yang berada di bawah atau sama dengan batas minimum stok.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Category Distribution Doughnut Chart -->
    <div class="col-12 col-md-6 col-xl-4">
        <div class="card h-100">
            <div class="card-header bg-white py-3 border-bottom">
                <h6 class="m-0 fw-bold text-slate-800">
                    <i class="fa-solid fa-pie-chart text-sky-500 me-2"></i> Porsi Stok Berdasarkan Kategori
                </h6>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <div style="position: relative; width: 100%; max-width: 280px; height: 280px;">
                    <canvas id="categoryDistributionChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Goods Received -->
    <div class="col-12 col-md-6 col-xl-4">
        <div class="card h-100">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <h6 class="m-0 fw-bold text-slate-800">
                    <i class="fa-solid fa-circle-arrow-down text-emerald-500 me-2"></i> 5 Barang Masuk Terbaru
                </h6>
                <a href="{{ route('barang-masuk.index') }}" class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size: 0.75rem;">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                @if($recentMasuk->count() > 0)
                    <div class="table-responsive shadow-none border-0 m-0">
                        <table class="table table-hover align-middle" style="font-size: 0.85rem;">
                            <tbody>
                                @foreach($recentMasuk as $entry)
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-slate-800">{{ $entry->nomor_transaksi }}</div>
                                            <span class="text-muted small">{{ date('d/m/Y', strtotime($entry->tanggal)) }}</span>
                                        </td>
                                        <td>
                                            <div class="fw-semibold text-slate-700">{{ $entry->product->nama_produk ?? '-' }}</div>
                                            <span class="text-muted small">{{ $entry->jumlah }} {{ $entry->product->satuan ?? 'pcs' }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="fa-regular fa-folder-open d-block fs-3 mb-2 text-slate-300"></i>
                        <span>Belum ada transaksi barang masuk.</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Goods Issued -->
    <div class="col-12 col-md-6 col-xl-4">
        <div class="card h-100">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <h6 class="m-0 fw-bold text-slate-800">
                    <i class="fa-solid fa-circle-arrow-up text-rose-500 me-2"></i> 5 Barang Keluar Terbaru
                </h6>
                <a href="{{ route('barang-keluar.index') }}" class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size: 0.75rem;">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                @if($recentKeluar->count() > 0)
                    <div class="table-responsive shadow-none border-0 m-0">
                        <table class="table table-hover align-middle" style="font-size: 0.85rem;">
                            <tbody>
                                @foreach($recentKeluar as $exit)
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-slate-800">{{ $exit->nomor_transaksi }}</div>
                                            <span class="text-muted small">{{ date('d/m/Y', strtotime($exit->tanggal)) }}</span>
                                        </td>
                                        <td>
                                            <div class="fw-semibold text-slate-700">{{ $exit->product->nama_produk ?? '-' }}</div>
                                            <span class="text-muted small">{{ $exit->jumlah }} {{ $exit->product->satuan ?? 'pcs' }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="fa-regular fa-folder-open d-block fs-3 mb-2 text-slate-300"></i>
                        <span>Belum ada transaksi barang keluar.</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- ChartJS Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        // 1. Stock In vs Stock Out Flow Chart (Bar Chart)
        const ctxFlow = document.getElementById('stockFlowChart').getContext('2d');
        new Chart(ctxFlow, {
            type: 'bar',
            data: {
                labels: {!! json_encode($monthsLabels) !!},
                datasets: [
                    {
                        label: 'Barang Masuk (Qty)',
                        data: {!! json_encode($chartMasukData) !!},
                        backgroundColor: 'rgba(16, 185, 129, 0.75)', // Emerald
                        borderColor: '#10b981',
                        borderWidth: 1,
                        borderRadius: 4
                    },
                    {
                        label: 'Barang Keluar (Qty)',
                        data: {!! json_encode($chartKeluarData) !!},
                        backgroundColor: 'rgba(244, 63, 94, 0.75)', // Rose
                        borderColor: '#f43f5e',
                        borderWidth: 1,
                        borderRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: { family: 'Outfit', size: 12 }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { family: 'Outfit', size: 11 }
                        }
                    },
                    y: {
                        grid: { color: '#f1f5f9' },
                        ticks: {
                            font: { family: 'Outfit', size: 11 },
                            beginAtZero: true
                        }
                    }
                }
            }
        });

        // 2. Category Distribution Chart (Doughnut Chart)
        const ctxDist = document.getElementById('categoryDistributionChart').getContext('2d');
        new Chart(ctxDist, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($categoryLabels) !!},
                datasets: [{
                    data: {!! json_encode($categoryStocks) !!},
                    backgroundColor: [
                        '#10b981', '#3b82f6', '#f59e0b', '#ec4899', '#8b5cf6', 
                        '#06b6d4', '#f97316', '#14b8a6', '#64748b', '#a855f7'
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false // Turn off legend to maximize canvas size, standard tooltips display names
                    }
                },
                cutout: '65%'
            }
        });
    });
</script>
@endsection
