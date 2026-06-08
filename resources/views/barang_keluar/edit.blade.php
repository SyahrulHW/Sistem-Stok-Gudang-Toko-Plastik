@extends('layouts.app')

@section('title', 'Edit Barang Keluar')

@section('breadcrumb', 'Barang Keluar / Edit')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <h6 class="m-0 fw-bold text-slate-800">
                    <i class="fa-solid fa-pencil text-warning me-2"></i> Edit Transaksi Barang Keluar
                </h6>
                <a href="{{ route('barang-keluar.index') }}" class="btn btn-light border btn-sm">
                    <i class="fa-solid fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
            
            <div class="card-body p-4">
                <form action="{{ route('barang-keluar.update', $barangKeluar->id) }}" method="POST" id="barangKeluarForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <!-- Nomor Transaksi -->
                        <div class="col-md-6 mb-3">
                            <label for="nomor_transaksi" class="form-label fw-semibold">Nomor Transaksi <span class="text-danger">*</span></label>
                            <input type="text" name="nomor_transaksi" id="nomor_transaksi" class="form-control @error('nomor_transaksi') is-invalid @enderror" placeholder="Contoh: BK-0001" value="{{ old('nomor_transaksi', $barangKeluar->nomor_transaksi) }}" required>
                            @error('nomor_transaksi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Tanggal Transaksi -->
                        <div class="col-md-6 mb-3">
                            <label for="tanggal" class="form-label fw-semibold">Tanggal Barang Keluar <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', $barangKeluar->tanggal) }}" required>
                            @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <!-- Pilih Produk -->
                        <div class="col-md-8 mb-3">
                            <label for="product_id" class="form-label fw-semibold">Pilih Produk <span class="text-danger">*</span></label>
                            <select name="product_id" id="product_id" class="form-select @error('product_id') is-invalid @enderror" required>
                                <option value="">Pilih Produk</option>
                                @foreach($products as $product)
                                    @php
                                        // Virtual stock calculation: current stock + original transaction amount if same product
                                        $isSameProduct = ($barangKeluar->product_id == $product->id);
                                        $virtualStock = $product->stok + ($isSameProduct ? $barangKeluar->jumlah : 0);
                                    @endphp
                                    <option value="{{ $product->id }}" 
                                            data-stok="{{ $virtualStock }}" 
                                            data-satuan="{{ $product->satuan }}"
                                            {{ old('product_id', $barangKeluar->product_id) == $product->id ? 'selected' : '' }}>
                                        {{ $product->nama_produk }} ({{ $product->kode_produk }}) - Maksimal Edit: {{ $virtualStock }} {{ $product->satuan }}
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Info Stok Badge -->
                        <div class="col-md-4 mb-3 d-flex flex-column justify-content-end">
                            <div class="border rounded p-2 text-center bg-light" id="stokInfoBox" style="height: 38px; display: none;">
                                <span class="fw-semibold small text-slate-600">Stok: </span>
                                <span class="badge bg-emerald-500 fs-7 text-white" id="stokTersediaBadge">0 pcs</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Jumlah Barang Keluar -->
                        <div class="col-md-6 mb-3">
                            <label for="jumlah" class="form-label fw-semibold">Jumlah Keluar <span class="text-danger">*</span></label>
                            <input type="number" name="jumlah" id="jumlah" class="form-control @error('jumlah') is-invalid @enderror" placeholder="0" value="{{ old('jumlah', $barangKeluar->jumlah) }}" min="1" required>
                            <div class="invalid-feedback" id="jumlahErrorText">Jumlah barang wajib diisi.</div>
                            @error('jumlah')
                                <div class="text-danger small mt-1" style="font-size: 0.8rem;">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Keterangan / Notes -->
                        <div class="col-md-6 mb-3">
                            <label for="keterangan" class="form-label fw-semibold">Keterangan / Tujuan</label>
                            <input type="text" name="keterangan" id="keterangan" class="form-control @error('keterangan') is-invalid @enderror" placeholder="Contoh: Cabang Toko" value="{{ old('keterangan', $barangKeluar->keterangan) }}">
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="bg-light p-3 border rounded mb-4" style="font-size: 0.8rem; color: #475569;">
                        <i class="fa-solid fa-triangle-exclamation text-rose-500 me-1"></i>
                        <strong>Aturan Gudang</strong>: Jumlah barang yang dikeluarkan **tidak boleh melebihi** stok yang tersedia di gudang saat ini.
                    </div>
                    
                    <div class="d-grid mt-2">
                        <button type="submit" class="btn btn-primary btn-animate py-2" id="submitBtn">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Transaksi Keluar
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
        let maxStok = 0;
        let satuan = 'pcs';

        // Listen for product selection changes
        $('#product_id').on('change', function() {
            const selectedOption = $(this).find('option:selected');
            
            if (selectedOption.val()) {
                maxStok = parseInt(selectedOption.data('stok')) || 0;
                satuan = selectedOption.data('satuan') || 'pcs';
                
                // Show available stock badge
                $('#stokTersediaBadge').text(maxStok + ' ' + satuan);
                $('#stokInfoBox').show();
                
                // Update quantity input attributes and validations
                $('#jumlah').attr('max', maxStok);
                $('#jumlahErrorText').text('Jumlah keluar melebihi stok yang tersedia (Maksimal: ' + maxStok + ' ' + satuan + ').');
                
                validateQuantity();
            } else {
                $('#stokInfoBox').hide();
                maxStok = 0;
                $('#jumlah').removeAttr('max');
            }
        });

        // Trigger validation check on input change
        $('#jumlah').on('input change', function() {
            validateQuantity();
        });

        // Validation routine
        function validateQuantity() {
            const qty = parseInt($('#jumlah').val()) || 0;
            
            if ($('#product_id').val()) {
                if (qty > maxStok) {
                    $('#jumlah').addClass('is-invalid');
                    $('#submitBtn').attr('disabled', true);
                    return false;
                } else {
                    $('#jumlah').removeClass('is-invalid');
                    $('#submitBtn').removeAttr('disabled');
                    return true;
                }
            }
            return true;
        }

        // Form submit intercept
        $('#barangKeluarForm').on('submit', function(e) {
            if (!validateQuantity()) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Stok Kurang!',
                    text: 'Jumlah pengeluaran melebihi batas stok tersedia.'
                });
                return false;
            }
        });

        // Trigger manual check on load
        $('#product_id').trigger('change');
    });
</script>
@endsection
