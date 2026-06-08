@extends('layouts.app')

@section('title', 'Edit Barang Masuk')

@section('breadcrumb', 'Barang Masuk / Edit')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <h6 class="m-0 fw-bold text-slate-800">
                    <i class="fa-solid fa-pencil text-warning me-2"></i> Edit Transaksi Barang Masuk
                </h6>
                <a href="{{ route('barang-masuk.index') }}" class="btn btn-light border btn-sm">
                    <i class="fa-solid fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
            
            <div class="card-body p-4">
                <form action="{{ route('barang-masuk.update', $barangMasuk->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <!-- Nomor Transaksi -->
                        <div class="col-md-6 mb-3">
                            <label for="nomor_transaksi" class="form-label fw-semibold">Nomor Transaksi <span class="text-danger">*</span></label>
                            <input type="text" name="nomor_transaksi" id="nomor_transaksi" class="form-control @error('nomor_transaksi') is-invalid @enderror" placeholder="Contoh: BM-0001" value="{{ old('nomor_transaksi', $barangMasuk->nomor_transaksi) }}" required>
                            @error('nomor_transaksi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Tanggal Transaksi -->
                        <div class="col-md-6 mb-3">
                            <label for="tanggal" class="form-label fw-semibold">Tanggal Barang Masuk <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', $barangMasuk->tanggal) }}" required>
                            @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <!-- Pilih Produk -->
                        <div class="col-md-6 mb-3">
                            <label for="product_id" class="form-label fw-semibold">Pilih Produk <span class="text-danger">*</span></label>
                            <select name="product_id" id="product_id" class="form-select @error('product_id') is-invalid @enderror" required>
                                <option value="">Pilih Produk</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" 
                                            data-supplier="{{ $product->supplier_id }}" 
                                            data-harga="{{ intval($product->harga_beli) }}"
                                            {{ old('product_id', $barangMasuk->product_id) == $product->id ? 'selected' : '' }}>
                                        {{ $product->nama_produk }} ({{ $product->kode_produk }}) - Stok: {{ $product->stok }}
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Pilih Supplier -->
                        <div class="col-md-6 mb-3">
                            <label for="supplier_id" class="form-label fw-semibold">Supplier Pengirim <span class="text-danger">*</span></label>
                            <select name="supplier_id" id="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror" required>
                                <option value="">Pilih Supplier</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ old('supplier_id', $barangMasuk->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->nama_supplier }} ({{ $supplier->kode_supplier }})
                                    </option>
                                @endforeach
                            </select>
                            @error('supplier_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <!-- Jumlah Barang -->
                        <div class="col-md-4 mb-3">
                            <label for="jumlah" class="form-label fw-semibold">Jumlah Barang <span class="text-danger">*</span></label>
                            <input type="number" name="jumlah" id="jumlah" class="form-control @error('jumlah') is-invalid @enderror" placeholder="Contoh: 50" value="{{ old('jumlah', $barangMasuk->jumlah) }}" min="1" required>
                            @error('jumlah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Harga Beli Satuan -->
                        <div class="col-md-4 mb-3">
                            <label for="harga_beli" class="form-label fw-semibold">Harga Beli Satuan (Rp) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="harga_beli" id="harga_beli" class="form-control @error('harga_beli') is-invalid @enderror" placeholder="0" value="{{ old('harga_beli', intval($barangMasuk->harga_beli)) }}" min="0" required>
                            </div>
                            @error('harga_beli')
                                <div class="text-danger small mt-1" style="font-size: 0.8rem;">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Total Harga (Calculated) -->
                        <div class="col-md-4 mb-3">
                            <label for="total_harga" class="form-label fw-semibold">Total Nilai Transaksi</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">Rp</span>
                                <input type="text" id="total_harga" class="form-control bg-light text-emerald-700 fw-bold" placeholder="0" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-light p-3 border rounded mb-4" style="font-size: 0.8rem; color: #475569;">
                        <i class="fa-solid fa-circle-info text-emerald-500 me-1"></i>
                        <strong>Penyesuaian Stok Otomatis</strong>: Jika Anda memperbarui kuantitas barang masuk, sistem akan **otomatis menghitung selisihnya** dan menyesuaikan persediaan produk terkait agar tetap akurat.
                    </div>
                    
                    <div class="d-grid mt-2">
                        <button type="submit" class="btn btn-primary btn-animate py-2">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Transaksi Masuk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Function to compute Total Harga
        function calculateTotal() {
            const qty = parseFloat($('#jumlah').val()) || 0;
            const price = parseFloat($('#harga_beli').val()) || 0;
            const total = qty * price;
            
            // Format to Indonesian Currency representation
            $('#total_harga').val(total.toLocaleString('id-ID'));
        }

        // Event listener for quantity or price change
        $('#jumlah, #harga_beli').on('input', function() {
            calculateTotal();
        });

        // Event listener for product change
        $('#product_id').on('change', function() {
            const selectedOption = $(this).find('option:selected');
            
            if (selectedOption.val()) {
                const supplierId = selectedOption.data('supplier');
                const hargaBeli = selectedOption.data('harga');
                
                // Auto select Supplier dropdown
                if (supplierId) {
                    $('#supplier_id').val(supplierId);
                }
                
                // Auto fill Harga Beli input
                if (hargaBeli) {
                    $('#harga_beli').val(hargaBeli);
                }
                
                calculateTotal();
            }
        });

        // Initial calculation trigger on load
        calculateTotal();
    });
</script>
@endsection
