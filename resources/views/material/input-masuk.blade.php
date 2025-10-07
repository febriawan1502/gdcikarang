@extends('layouts.app')

@section('title', 'Input Material Masuk - ASI System')
@section('page-title', 'Input Material Masuk')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="fa fa-box-open me-2"></i>
                    Input Material Masuk
                </h5>
            </div>
            
            <div class="card-body">
                <!-- Alert Info -->
                <div class="alert alert-info" role="alert">
                    <i class="fa fa-info-circle me-2"></i>
                    <strong>Informasi:</strong> Fitur Input Material Masuk sedang dalam tahap pengembangan. 
                    Template ini akan digunakan untuk mencatat material yang masuk ke gudang.
                </div>
                
                <!-- Form Template -->
                <form method="POST" action="#" class="needs-validation" novalidate>
                    @csrf
                    
                    <!-- Informasi Penerimaan -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-success border-bottom pb-2">
                                <i class="fa fa-clipboard-list me-1"></i>
                                Informasi Penerimaan
                            </h6>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="nomor_dokumen" class="form-label fw-semibold">
                                Nomor Dokumen <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="nomor_dokumen" 
                                   name="nomor_dokumen" 
                                   placeholder="Contoh: DOK/2025/001"
                                   required>
                            <div class="invalid-feedback">
                                Nomor dokumen wajib diisi.
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_masuk" class="form-label fw-semibold">
                                Tanggal Masuk <span class="text-danger">*</span>
                            </label>
                            <input type="date" 
                                   class="form-control" 
                                   id="tanggal_masuk" 
                                   name="tanggal_masuk" 
                                   value="{{ date('Y-m-d') }}"
                                   required>
                            <div class="invalid-feedback">
                                Tanggal masuk wajib diisi.
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="supplier" class="form-label fw-semibold">
                                Supplier/Vendor <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="supplier" 
                                   name="supplier" 
                                   placeholder="Nama supplier/vendor"
                                   required>
                            <div class="invalid-feedback">
                                Nama supplier wajib diisi.
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="nomor_po" class="form-label fw-semibold">
                                Nomor PO
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="nomor_po" 
                                   name="nomor_po" 
                                   placeholder="Nomor Purchase Order (opsional)">
                        </div>
                    </div>
                    
                    <!-- Pilih Material -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-success border-bottom pb-2">
                                <i class="fa fa-boxes me-1"></i>
                                Pilih Material
                            </h6>
                        </div>
                        
                        <div class="col-12 mb-3">
                            <label for="material_id" class="form-label fw-semibold">
                                Material <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="material_id" name="material_id" required>
                                <option value="">Pilih Material...</option>
                                <!-- Options akan diisi dari database -->
                                <option value="1">1330.SPK - ISOLATOR;PINPOST;PORC;24KV;;12.5kN</option>
                                <option value="2">1336.SPK - ISOLATOR;STRAIN KAP PIN;PORC;24KV;;70kN</option>
                                <option value="3">1318.SPK - CABLE PWR;NFA2X-T;3X35+1X35;0.6/1kV;OH</option>
                            </select>
                            <div class="invalid-feedback">
                                Material wajib dipilih.
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="qty_masuk" class="form-label fw-semibold">
                                Quantity Masuk <span class="text-danger">*</span>
                            </label>
                            <input type="number" 
                                   class="form-control" 
                                   id="qty_masuk" 
                                   name="qty_masuk" 
                                   min="1"
                                   placeholder="Jumlah yang masuk"
                                   required>
                            <div class="invalid-feedback">
                                Quantity masuk wajib diisi.
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="unit" class="form-label fw-semibold">
                                Unit
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="unit" 
                                   name="unit" 
                                   placeholder="Unit akan otomatis terisi"
                                   readonly>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="kondisi" class="form-label fw-semibold">
                                Kondisi <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="kondisi" name="kondisi" required>
                                <option value="">Pilih Kondisi...</option>
                                <option value="BAIK">Baik</option>
                                <option value="RUSAK">Rusak</option>
                                <option value="PERLU_PERBAIKAN">Perlu Perbaikan</option>
                            </select>
                            <div class="invalid-feedback">
                                Kondisi material wajib dipilih.
                            </div>
                        </div>
                    </div>
                    
                    <!-- Informasi Tambahan -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-success border-bottom pb-2">
                                <i class="fa fa-sticky-note me-1"></i>
                                Informasi Tambahan
                            </h6>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="lokasi_penyimpanan" class="form-label fw-semibold">
                                Lokasi Penyimpanan
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="lokasi_penyimpanan" 
                                   name="lokasi_penyimpanan" 
                                   placeholder="Contoh: Rak A-1, Gudang Utama">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="petugas_penerima" class="form-label fw-semibold">
                                Petugas Penerima
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="petugas_penerima" 
                                   name="petugas_penerima" 
                                   value="{{ auth()->check() ? auth()->user()->name : '' }}"
                                   readonly>
                        </div>
                        
                        <div class="col-12 mb-3">
                            <label for="catatan" class="form-label fw-semibold">
                                Catatan
                            </label>
                            <textarea class="form-control" 
                                      id="catatan" 
                                      name="catatan" 
                                      rows="3"
                                      placeholder="Catatan tambahan mengenai penerimaan material (opsional)"></textarea>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                    <i class="fa fa-arrow-left me-1"></i>
                                    Kembali
                                </a>
                                
                                <div>
                                    <button type="reset" class="btn btn-warning me-2">
                                        <i class="fa fa-undo me-1"></i>
                                        Reset Form
                                    </button>
                                    
                                    <button type="button" class="btn btn-success" onclick="showComingSoon()">
                                        <i class="fa fa-save me-1"></i>
                                        Simpan Material Masuk
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Coming Soon -->
<div class="modal fade" id="comingSoonModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fa fa-clock me-2"></i>
                    Fitur Segera Hadir
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <i class="fa fa-tools fa-3x text-info mb-3"></i>
                <h6>Fitur Input Material Masuk</h6>
                <p class="text-muted">Fitur ini sedang dalam tahap pengembangan dan akan segera tersedia.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-fill unit when material is selected
    $('#material_id').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        // Simulasi pengisian unit berdasarkan material yang dipilih
        if ($(this).val()) {
            $('#unit').val('PCS'); // Default unit, akan diambil dari database
        } else {
            $('#unit').val('');
        }
    });
    
    // Form validation
    $('form').on('submit', function(e) {
        e.preventDefault();
        showComingSoon();
    });
    
    // Bootstrap form validation
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            var forms = document.getElementsByClassName('needs-validation');
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
});

function showComingSoon() {
    $('#comingSoonModal').modal('show');
}
</script>
@endpush