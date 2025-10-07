@extends('layouts.app')

@section('title', 'Tambah Material - ASI System')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-plus-circle"></i>
                    Tambah Material Baru
                </h3>
            </div>
            
            <div class="panel-body" style="padding-left: 40px; padding-right: 40px;">
                <form method="POST" action="{{ route('material.store') }}">
                    @csrf
                    <!-- Informasi Dasar -->
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-primary" style="border-bottom: 1px solid #ddd; padding-bottom: 8px; margin-bottom: 15px;">
                                <i class="fa fa-info-circle"></i>
                                Informasi Dasar
                            </h6>
                        </div>
                        

                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="material_code" class="control-label">
                                    Material Code <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('material_code') is-invalid @enderror" 
                                       id="material_code" 
                                       name="material_code" 
                                       value="{{ old('material_code') }}"
                                       placeholder="Contoh: 000000001060068"
                                       required>
                                @error('material_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="material_description" class="control-label">
                                    Deskripsi Material <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control @error('material_description') is-invalid @enderror" 
                                          id="material_description" 
                                          name="material_description" 
                                          rows="2"
                                          placeholder="Contoh: ISOLATOR;PINPOST;PORC;24KV;;12.5kN"
                                          required>{{ old('material_description') }}</textarea>
                                @error('material_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pabrikan" class="control-label">
                                    Pabrikan <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('pabrikan') is-invalid @enderror" 
                                       id="pabrikan" 
                                       name="pabrikan" 
                                       value="{{ old('pabrikan') }}"
                                       placeholder="Contoh: KENTJANA SAKTI INDONESIA"
                                       required>
                                @error('pabrikan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="normalisasi" class="control-label">
                                    Normalisasi
                                </label>
                                <input type="text" 
                                       class="form-control @error('normalisasi') is-invalid @enderror" 
                                       id="normalisasi" 
                                       name="normalisasi" 
                                       value="{{ old('normalisasi') }}"
                                       placeholder="Opsional">
                                @error('normalisasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Informasi Company -->
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-primary" style="border-bottom: 1px solid #ddd; padding-bottom: 8px; margin-bottom: 15px;">
                                <i class="fa fa-building"></i>
                                Informasi Company
                            </h6>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="company_code" class="control-label">
                                    Company Code <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('company_code') is-invalid @enderror" 
                                       id="company_code" 
                                       name="company_code" 
                                       value="{{ old('company_code', '5300') }}"
                                       required>
                                @error('company_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="company_code_description" class="control-label">
                                    Company Description <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('company_code_description') is-invalid @enderror" 
                                       id="company_code_description" 
                                       name="company_code_description" 
                                       value="{{ old('company_code_description', 'UID Jawa Barat') }}"
                                       required>
                                @error('company_code_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="plant" class="control-label">
                                    Plant <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('plant') is-invalid @enderror" 
                                       id="plant" 
                                       name="plant" 
                                       value="{{ old('plant', '5319') }}"
                                       required>
                                @error('plant')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="plant_description" class="control-label">
                                    Plant Description <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('plant_description') is-invalid @enderror" 
                                       id="plant_description" 
                                       name="plant_description" 
                                       value="{{ old('plant_description', 'PLN UP3 Cimahi') }}"
                                       required>
                                @error('plant_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="storage_location" class="control-label">
                                    Storage Location <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('storage_location') is-invalid @enderror" 
                                       id="storage_location" 
                                       name="storage_location" 
                                       value="{{ old('storage_location', '2080') }}"
                                       required>
                                @error('storage_location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="storage_location_description" class="control-label">
                                    Storage Description <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('storage_location_description') is-invalid @enderror" 
                                       id="storage_location_description" 
                                       name="storage_location_description" 
                                   value="{{ old('storage_location_description', 'APJ Cimahi') }}"
                                   required>
                            @error('storage_location_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="rak" class="control-label">
                                Rak
                            </label>
                            <input type="text" 
                                   class="form-control @error('rak') is-invalid @enderror" 
                                   id="rak" 
                                   name="rak" 
                                   value="{{ old('rak') }}"
                                   placeholder="Contoh: A-01-02">
                            @error('rak')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                    
                    <!-- Informasi Material Type -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2">
                                <i class="fa fa-tags me-1"></i>
                                Informasi Material Type
                            </h6>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="material_type" class="control-label">
                                    Material Type <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('material_type') is-invalid @enderror" 
                                       id="material_type" 
                                       name="material_type" 
                                       value="{{ old('material_type', 'ZST1') }}"
                                       required>
                                @error('material_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="material_type_description" class="control-label">
                                    Material Type Description <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('material_type_description') is-invalid @enderror" 
                                       id="material_type_description" 
                                       name="material_type_description" 
                                       value="{{ old('material_type_description', 'PLN Stock Materials') }}"
                                       required>
                                @error('material_type_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="material_group" class="control-label">
                                    Material Group <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('material_group') is-invalid @enderror" 
                                       id="material_group" 
                                       name="material_group" 
                                       value="{{ old('material_group', 'ZM0106') }}"
                                       required>
                                @error('material_group')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="base_unit_of_measure" class="control-label">
                                    Unit of Measure <span class="text-danger">*</span>
                                </label>
                                <select class="form-control @error('base_unit_of_measure') is-invalid @enderror" 
                                        id="base_unit_of_measure" 
                                        name="base_unit_of_measure" 
                                        required>
                                    <option value="">Pilih Unit</option>
                                    <option value="PCS" {{ old('base_unit_of_measure') == 'PCS' ? 'selected' : '' }}>PCS</option>
                                    <option value="SET" {{ old('base_unit_of_measure') == 'SET' ? 'selected' : '' }}>SET</option>
                                    <option value="M" {{ old('base_unit_of_measure') == 'M' ? 'selected' : '' }}>M</option>
                                    <option value="KG" {{ old('base_unit_of_measure') == 'KG' ? 'selected' : '' }}>KG</option>
                                    <option value="L" {{ old('base_unit_of_measure') == 'L' ? 'selected' : '' }}>L</option>
                                </select>
                                @error('base_unit_of_measure')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Informasi Stock & Harga -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2">
                                <i class="fa fa-cubes me-1"></i>
                                Informasi Stock & Harga
                            </h6>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="qty" class="control-label">
                                    Quantity <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       class="form-control @error('qty') is-invalid @enderror" 
                                       id="qty" 
                                       name="qty" 
                                       value="{{ old('qty') }}"
                                       min="1"
                                       required>
                                @error('qty')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="unrestricted_use_stock" class="control-label">
                                    Unrestricted Stock <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       class="form-control @error('unrestricted_use_stock') is-invalid @enderror" 
                                       id="unrestricted_use_stock" 
                                       name="unrestricted_use_stock" 
                                       value="{{ old('unrestricted_use_stock') }}"
                                       step="0.001"
                                       min="0"
                                       required>
                                @error('unrestricted_use_stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tanggal_terima" class="control-label">
                                    Tanggal Terima <span class="text-danger">*</span>
                                </label>
                                <input type="date" 
                                       class="form-control @error('tanggal_terima') is-invalid @enderror" 
                                       id="tanggal_terima" 
                                       name="tanggal_terima" 
                                       value="{{ old('tanggal_terima', date('Y-m-d')) }}"
                                       required>
                                @error('tanggal_terima')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="harga_satuan" class="control-label">
                                    Harga Satuan (Rp) <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       class="form-control @error('harga_satuan') is-invalid @enderror" 
                                       id="harga_satuan" 
                                       name="harga_satuan" 
                                       value="{{ old('harga_satuan') }}"
                                       min="0"
                                       required>
                                @error('harga_satuan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="total_harga" class="control-label">
                                    Total Harga (Rp) <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       class="form-control @error('total_harga') is-invalid @enderror" 
                                       id="total_harga" 
                                       name="total_harga" 
                                       value="{{ old('total_harga') }}"
                                       min="0"
                                       readonly>
                                @error('total_harga')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Informasi Tambahan -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2">
                                <i class="fa fa-plus-circle me-1"></i>
                                Informasi Tambahan
                            </h6>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status" class="control-label">
                                    Status <span class="text-danger">*</span>
                                </label>
                                <select class="form-control @error('status') is-invalid @enderror" 
                                        id="status" 
                                        name="status" 
                                        required>
                                    <option value="">Pilih Status</option>
                                    <option value="BAIK" {{ old('status', 'BAIK') == 'BAIK' ? 'selected' : '' }}>Baik</option>
                                    <option value="RUSAK" {{ old('status') == 'RUSAK' ? 'selected' : '' }}>Rusak</option>
                                    <option value="DALAM PERBAIKAN" {{ old('status') == 'DALAM PERBAIKAN' ? 'selected' : '' }}>Dalam Perbaikan</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="currency" class="control-label">
                                    Currency <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('currency') is-invalid @enderror" 
                                       id="currency" 
                                       name="currency" 
                                       value="{{ old('currency', 'IDR') }}"
                                       required>
                                @error('currency')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-group">
                                <label for="keterangan" class="control-label">
                                    Keterangan
                                </label>
                                <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                          id="keterangan" 
                                          name="keterangan" 
                                          rows="3"
                                          placeholder="Catatan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                                @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Hidden Fields -->
                    <input type="hidden" name="valuation_type" value="NORMAL">
                    <input type="hidden" name="quality_inspection_stock" value="0">
                    <input type="hidden" name="blocked_stock" value="0">
                    <input type="hidden" name="in_transit_stock" value="0">
                    <input type="hidden" name="project_stock" value="0">
                    <input type="hidden" name="wbs_element" value="">
                    <input type="hidden" name="valuation_class" value="1000">
                    <input type="hidden" name="valuation_description" value="HAR-Material">
                    
                    <!-- Action Buttons -->
                    <div class="row" style="margin-top: 20px;">
                        <div class="col-12">
                            <div class="form-group">
                                <div class="pull-left" style="position: relative; z-index: 999;">
                                    <a href="{{ route('dashboard') }}" class="btn btn-default" style="pointer-events: auto; position: relative; z-index: 1000; cursor: pointer;">
                                        <i class="fa fa-arrow-left"></i>
                                        Kembali
                                    </a>
                                </div>
                                
                                <div class="pull-right" style="position: relative; z-index: 999;">
                                    <button type="reset" class="btn btn-warning" style="margin-right: 5px; pointer-events: auto; position: relative; z-index: 1000;">
                                        <i class="fa fa-undo"></i>
                                        Reset Form
                                    </button>
                                    
                                    <button type="submit" id="submit-btn" class="btn btn-primary" style="pointer-events: auto; position: relative; z-index: 1000; cursor: pointer;">
                                        <i class="fa fa-save"></i>
                                        Simpan Material
                                    </button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto calculate total harga
    function calculateTotal() {
        const qty = parseFloat($('#qty').val()) || 0;
        const hargaSatuan = parseFloat($('#harga_satuan').val()) || 0;
        const total = qty * hargaSatuan;
        $('#total_harga').val(total);
    }
    
    $('#qty, #harga_satuan').on('input', calculateTotal);
    
    // Auto sync qty with unrestricted_use_stock
    $('#qty').on('input', function() {
        $('#unrestricted_use_stock').val($(this).val());
        calculateTotal();
    });
    
    // Format currency input
    $('#harga_satuan, #total_harga').on('input', function() {
        let value = $(this).val().replace(/[^0-9]/g, '');
        if (value) {
            $(this).val(parseInt(value));
        }
    });
    
    // Debug tombol submit
    $('#submit-btn').on('click', function(e) {
        console.log('Submit button clicked!');
        console.log('Button element:', this);
    });
    
    // Form validation
    $('form').on('submit', function(e) {
        console.log('Form submit event triggered!');
        let isValid = true;
        
        // Check required fields
        $('input[required], select[required], textarea[required]').each(function() {
            if (!$(this).val()) {
                $(this).addClass('is-invalid');
                isValid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            Swal.fire('Error!', 'Mohon lengkapi semua field yang wajib diisi', 'error');
        } else {
            console.log('Form validation passed, submitting...');
        }
    });
});
</script>
@endpush