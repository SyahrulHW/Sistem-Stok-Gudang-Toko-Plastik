<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan - Sistem Gudang Toko Plastik</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS for Printable Reports -->
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            color: #1e293b;
            background-color: #ffffff;
            font-size: 0.85rem;
            padding: 20px;
        }

        .letterhead {
            border-bottom: 3px double #334155;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .letterhead h2 {
            font-weight: 800;
            color: #0f172a;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .letterhead .shop-info {
            color: #64748b;
            font-size: 0.8rem;
            line-height: 1.4;
        }

        .report-title-box {
            text-align: center;
            margin-bottom: 30px;
        }

        .report-title-box h4 {
            font-weight: 700;
            color: #0f172a;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .report-title-box span {
            color: #64748b;
            font-size: 0.85rem;
        }

        .table {
            border-color: #cbd5e1;
        }

        .table th {
            background-color: #f1f5f9 !important;
            color: #0f172a !important;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.3px;
            padding: 10px 12px;
            border-bottom: 2px solid #cbd5e1 !important;
        }

        .table td {
            padding: 10px 12px;
            vertical-align: middle;
        }

        .grand-total-row {
            background-color: #f8fafc !important;
            font-weight: bold;
        }

        .signature-box {
            margin-top: 50px;
            float: right;
            width: 250px;
            text-align: center;
        }

        .signature-line {
            margin-top: 70px;
            border-top: 1px solid #475569;
            font-weight: 600;
            color: #0f172a;
        }

        .print-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 0.7rem;
            color: #94a3b8;
            border-top: 1px dashed #e2e8f0;
            padding-top: 5px;
            display: none; /* Only visible when printing */
        }

        /* Print Media Styles */
        @media print {
            body {
                padding: 0;
                font-size: 0.8rem;
            }

            .no-print {
                display: none !important;
            }

            .print-footer {
                display: block;
            }

            /* Prevent table rows breaking awkwardly across pages */
            tr {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>

    <!-- Floating Action Bar for manual print controls (hidden on actual print/PDF save) -->
    <div class="container-fluid no-print bg-light border-bottom p-3 mb-4 rounded d-flex align-items-center justify-content-between">
        <div>
            <i class="fa-solid fa-print text-primary me-2"></i>
            <span class="fw-semibold">Dokumen Siap Dicetak</span>
        </div>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-danger btn-sm">
                <i class="fa-solid fa-file-pdf me-1"></i> Cetak Sekarang
            </button>
            <button onclick="window.close()" class="btn btn-secondary btn-sm">
                Tutup Halaman
            </button>
        </div>
    </div>

    <!-- Letterhead -->
    <div class="letterhead d-flex justify-content-between align-items-center">
        <div>
            <h2>TOKO PLASTIK SENTOSA</h2>
            <div class="shop-info mt-1">
                Kawasan Pusat Grosir Dagang Utama, Ruko Sentosa Blok B No. 8, Kota Semarang<br>
                Telp: (024) 76543210 | Email: warehouse@plastiksentosa.com
            </div>
        </div>
        <div class="text-end shop-info">
            <strong>Tanggal Cetak:</strong> {{ date('d F Y H:i') }} WIB<br>
            <strong>Operator:</strong> {{ Auth::user()->name }} ({{ ucfirst(Auth::user()->role) }})
        </div>
    </div>

    <!-- Report Title Details -->
    <div class="report-title-box">
        @if($type === 'barang_masuk')
            <h4>Laporan Transaksi Barang Masuk</h4>
            <span>Rentang Periode: <strong>{{ $startDateStr }}</strong> s.d. <strong>{{ $endDateStr }}</strong></span>
        @elseif($type === 'barang_keluar')
            <h4>Laporan Transaksi Barang Keluar</h4>
            <span>Rentang Periode: <strong>{{ $startDateStr }}</strong> s.d. <strong>{{ $endDateStr }}</strong></span>
        @elseif($type === 'stok_gudang')
            <h4>Laporan Stok Fisik Gudang (Terkini)</h4>
            <span>Status Data Real-Time Persediaan Terdaftar</span>
        @elseif($type === 'supplier')
            <h4>Laporan Partner Supplier Utama</h4>
            <span>Data Rekanan Supplier & Total Penyerahan Barang</span>
        @endif
    </div>

    <!-- Report Table Content -->
    <div class="table-responsive shadow-none">
        <table class="table table-bordered align-middle">
            
            <!-- 1. BARANG MASUK -->
            @if($type === 'barang_masuk')
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th width="130">No. Transaksi</th>
                        <th width="100">Tanggal</th>
                        <th>Kode Produk</th>
                        <th>Nama Produk</th>
                        <th>Supplier</th>
                        <th class="text-center" width="80">Jumlah</th>
                        <th class="text-end" width="120">Harga Beli</th>
                        <th class="text-end" width="130">Total Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    @php $grandTotal = 0; @endphp
                    @foreach($data as $index => $row)
                        @php $grandTotal += $row->total_harga; @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="font-monospace fw-semibold">{{ $row->nomor_transaksi }}</td>
                            <td>{{ date('d/m/Y', strtotime($row->tanggal)) }}</td>
                            <td>{{ $row->product->kode_produk ?? '-' }}</td>
                            <td class="fw-semibold">{{ $row->product->nama_produk ?? '-' }}</td>
                            <td>{{ $row->supplier->nama_supplier ?? '-' }}</td>
                            <td class="text-center fw-bold">{{ $row->jumlah }}</td>
                            <td class="text-end">Rp {{ number_format($row->harga_beli, 0, ',', '.') }}</td>
                            <td class="text-end fw-semibold">Rp {{ number_format($row->total_harga, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    <tr class="grand-total-row">
                        <td colspan="6" class="text-end">GRAND TOTAL:</td>
                        <td class="text-center">{{ $data->sum('jumlah') }}</td>
                        <td></td>
                        <td class="text-end text-dark">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                    </tr>
                </tbody>

            <!-- 2. BARANG KELUAR -->
            @elseif($type === 'barang_keluar')
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th width="140">No. Transaksi</th>
                        <th width="110">Tanggal</th>
                        <th>Kode Produk</th>
                        <th>Nama Produk</th>
                        <th class="text-center" width="100">Jumlah Keluar</th>
                        <th>Keterangan / Tujuan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $index => $row)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="font-monospace fw-semibold">{{ $row->nomor_transaksi }}</td>
                            <td>{{ date('d/m/Y', strtotime($row->tanggal)) }}</td>
                            <td>{{ $row->product->kode_produk ?? '-' }}</td>
                            <td class="fw-semibold">{{ $row->product->nama_produk ?? '-' }}</td>
                            <td class="text-center fw-bold">{{ $row->jumlah }}</td>
                            <td>{{ $row->keterangan ?? '-' }}</td>
                        </tr>
                    @endforeach
                    <tr class="grand-total-row">
                        <td colspan="5" class="text-end">TOTAL PENGELUARAN:</td>
                        <td class="text-center">{{ $data->sum('jumlah') }}</td>
                        <td></td>
                    </tr>
                </tbody>

            <!-- 3. STOK GUDANG -->
            @elseif($type === 'stok_gudang')
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th width="110">Kode Produk</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Supplier</th>
                        <th class="text-center" width="80">Satuan</th>
                        <th class="text-end" width="110">Harga Beli</th>
                        <th class="text-end" width="110">Harga Jual</th>
                        <th class="text-center" width="80">Stok</th>
                        <th class="text-center" width="80">Batas Min</th>
                        <th class="text-center" width="100">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $index => $row)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="fw-bold">{{ $row->kode_produk }}</td>
                            <td class="fw-semibold">{{ $row->nama_produk }}</td>
                            <td>{{ $row->category->nama_kategori ?? '-' }}</td>
                            <td>{{ $row->supplier->nama_supplier ?? '-' }}</td>
                            <td class="text-center">{{ $row->satuan }}</td>
                            <td class="text-end">Rp {{ number_format($row->harga_beli, 0, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($row->harga_jual, 0, ',', '.') }}</td>
                            <td class="text-center fw-bold {{ $row->isLowStock() ? 'text-danger' : '' }}">{{ $row->stok }}</td>
                            <td class="text-center text-muted">{{ $row->minimum_stok }}</td>
                            <td class="text-center fw-semibold">
                                {{ $row->isLowStock() ? 'STOK TIPIS' : 'AMAN' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            <!-- 4. SUPPLIER -->
            @elseif($type === 'supplier')
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th width="110">Kode Supplier</th>
                        <th>Nama Supplier Partner</th>
                        <th>No. Telepon</th>
                        <th>Email Address</th>
                        <th>Alamat Lengkap</th>
                        <th class="text-center" width="150">Kuantitas Pasokan Masuk</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $index => $row)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="fw-bold">{{ $row->kode_supplier }}</td>
                            <td class="fw-semibold">{{ $row->nama_supplier }}</td>
                            <td>{{ $row->telepon ?? '-' }}</td>
                            <td>{{ $row->email ?? '-' }}</td>
                            <td>{{ $row->alamat ?? '-' }}</td>
                            <td class="text-center fw-bold">{{ $row->barang_masuks_count }} Transaksi</td>
                        </tr>
                    @endforeach
                </tbody>
            @endif
        </table>
    </div>

    <!-- Sign-off Signature Box -->
    <div class="signature-box no-print">
        <div>Semarang, {{ date('d F Y') }}</div>
        <div class="signature-line">
            Kepala Gudang Persediaan
        </div>
    </div>

    <!-- Hidden Printer Footnote -->
    <div class="print-footer">
        Dokumen Laporan Sistem Gudang Toko Plastik Sentosa. Dicetak secara sistematis dan sah. Page 1 of 1.
    </div>

    <!-- Javascript to auto trigger print modal immediately -->
    <script>
        window.onload = function() {
            // Auto open the native print dialog after rendering completes
            setTimeout(function() {
                window.print();
            }, 300);
        };
    </script>
</body>
</html>
