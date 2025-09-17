@extends('layouts.app')

@section('title', 'Edit Surat Jalan')

@section('page-title', 'Edit Surat Jalan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-edit me-2"></i>
                        Edit Surat Jalan: {{ $suratJalan->nomor_surat }}
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('material.surat-jalan.update', $suratJalan->id) }}" method="POST" id="suratJalanForm">
                        @csrf
                        @method('PUT')
                        
                        <!-- Informasi Surat Jalan -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-info border-bottom pb-2">
                                    <i class="fas fa-file-alt me-1"></i>
                                    Informasi Surat Jalan
                                </h6>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="nomor_surat" class="form-label fw-semibold">
                                    Nomor Surat Jalan <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="nomor_surat" 
                                       name="nomor_surat" 
                                       value="{{ $suratJalan->nomor_surat }}"
                                       readonly>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="jenis_surat_jalan" class="form-label fw-semibold">
                                    Jenis Surat Jalan <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" 
                                        id="jenis_surat_jalan" 
                                        name="jenis_surat_jalan" 
                                        required>
                                    <option value="">Pilih Jenis Surat Jalan</option>
                                    <option value="Normal" {{ $suratJalan->jenis_surat_jalan == 'Normal' ? 'selected' : '' }}>Normal</option>
                                    <option value="Gangguan" {{ $suratJalan->jenis_surat_jalan == 'Gangguan' ? 'selected' : '' }}>Gangguan</option>
                                    <option value="Garansi" {{ $suratJalan->jenis_surat_jalan == 'Garansi' ? 'selected' : '' }}>Garansi</option>
                                    <option value="Peminjaman" {{ $suratJalan->jenis_surat_jalan == 'Peminjaman' ? 'selected' : '' }}>Peminjaman</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="tanggal" class="form-label fw-semibold">
                                    Tanggal Surat Jalan <span class="text-danger">*</span>
                                </label>
                                <input type="date" 
                                       class="form-control" 
                                       id="tanggal" 
                                       name="tanggal" 
                                       value="{{ $suratJalan->tanggal }}"
                                       required>
                            </div>
                        </div>
                        
                        <!-- Informasi Penerima -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-info border-bottom pb-2">
                                    <i class="fas fa-user-check me-1"></i>
                                    Informasi Penerima
                                </h6>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="kepada" class="form-label fw-semibold">
                                    Nama Penerima <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="kepada" 
                                       name="kepada" 
                                       value="{{ $suratJalan->kepada }}"
                                       placeholder="Nama perusahaan/instansi penerima"
                                       required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="alamat" class="form-label fw-semibold">
                                    Alamat Penerima <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control" 
                                          id="alamat" 
                                          name="alamat" 
                                          rows="2"
                                          placeholder="Alamat lengkap penerima"
                                          required>{{ $suratJalan->alamat }}</textarea>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="berdasarkan" class="form-label fw-semibold">
                                    Berdasarkan <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control" 
                                          id="berdasarkan" 
                                          name="berdasarkan" 
                                          rows="2"
                                          required>{{ $suratJalan->berdasarkan }}</textarea>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="security" class="form-label fw-semibold">
                                    Security
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="security" 
                                       name="security" 
                                       value="{{ $suratJalan->security }}"
                                       placeholder="Nama security">
                            </div>
                        </div>
                        
                        <!-- Daftar Material -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-info border-bottom pb-2">
                                    <i class="fas fa-boxes me-1"></i>
                                    Daftar Material
                                </h6>
                            </div>
                            
                            <div class="col-12 mb-3">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="materialTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="35%">Material</th>
                                                <th width="15%">Qty</th>
                                                <th width="15%">Satuan</th>
                                                <th width="20%">Keterangan</th>
                                                <th width="10%">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($suratJalan->details as $index => $detail)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <select class="form-select form-select-sm" name="materials[{{ $index }}][material_id]" required>
                                                        <option value="">Pilih Material...</option>
                                                        @foreach($materials as $material)
                                                            <option value="{{ $material->id }}" 
                                                                    data-satuan="{{ $material->satuan }}"
                                                                    {{ $detail->material_id == $material->id ? 'selected' : '' }}>
                                                                {{ $material->nama_material }} (Stock: {{ $material->unrestricted_use_stock ?? 0 }} {{ $material->satuan }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control form-control-sm" 
                                                           name="materials[{{ $index }}][quantity]" 
                                                           value="{{ $detail->quantity }}"
                                                           min="1" required>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control form-control-sm" 
                                                           name="materials[{{ $index }}][satuan]" 
                                                           value="{{ $detail->satuan }}"
                                                           readonly>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control form-control-sm" 
                                                           name="materials[{{ $index }}][keterangan]" 
                                                           value="{{ $detail->keterangan }}"
                                                           placeholder="Keterangan">
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                
                                <button type="button" class="btn btn-success btn-sm" onclick="addRow()">
                                    <i class="fas fa-plus me-1"></i>
                                    Tambah Material
                                </button>
                            </div>
                        </div>
                        
                        <!-- Keterangan -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-info border-bottom pb-2">
                                    <i class="fas fa-sticky-note me-1"></i>
                                    Keterangan
                                </h6>
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="keterangan" class="form-label fw-semibold">
                                    Keterangan Tambahan
                                </label>
                                <textarea class="form-control" 
                                          id="keterangan" 
                                          name="keterangan" 
                                          rows="3"
                                          placeholder="Keterangan tambahan untuk surat jalan (opsional)">{{ $suratJalan->keterangan }}</textarea>
                            </div>
                        </div>
                        
                        <!-- Informasi Kendaraan -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-info border-bottom pb-2">
                                    <i class="fas fa-truck me-1"></i>
                                    Informasi Kendaraan
                                </h6>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="kendaraan" class="form-label fw-semibold">
                                    Kendaraan
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="kendaraan" 
                                       name="kendaraan" 
                                       value="{{ $suratJalan->kendaraan }}"
                                       placeholder="Jenis/Merk kendaraan">
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="no_polisi" class="form-label fw-semibold">
                                    No. Polisi
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="no_polisi" 
                                       name="no_polisi" 
                                       value="{{ $suratJalan->no_polisi }}"
                                       placeholder="Nomor polisi kendaraan">
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="pengemudi" class="form-label fw-semibold">
                                    Pengemudi
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="pengemudi" 
                                       name="pengemudi" 
                                       value="{{ $suratJalan->pengemudi }}"
                                       placeholder="Nama pengemudi">
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('material.surat-jalan') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i>
                                        Kembali
                                    </a>
                                    
                                    <div>
                                        <button type="reset" class="btn btn-warning me-2">
                                            <i class="fas fa-undo me-1"></i>
                                            Reset Form
                                        </button>
                                        
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-1"></i>
                                            Update Surat Jalan
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
</div>
@endsection

@push('scripts')
<script>
let rowCount = {{ count($suratJalan->details) }};

// Function to add new row
function addRow() {
    const tbody = document.querySelector('#materialTable tbody');
    const newRow = document.createElement('tr');
    
    newRow.innerHTML = `
        <td>${rowCount + 1}</td>
        <td>
            <select class="form-select form-select-sm" name="materials[${rowCount}][material_id]" required>
                <option value="">Pilih Material...</option>
                @foreach($materials as $material)
                    <option value="{{ $material->id }}" data-satuan="{{ $material->satuan }}">
                        {{ $material->nama_material }} (Stock: {{ $material->unrestricted_use_stock ?? 0 }} {{ $material->satuan }})
                    </option>
                @endforeach
            </select>
        </td>
        <td>
            <input type="number" class="form-control form-control-sm" name="materials[${rowCount}][quantity]" min="1" required>
        </td>
        <td>
            <input type="text" class="form-control form-control-sm" name="materials[${rowCount}][satuan]" readonly>
        </td>
        <td>
            <input type="text" class="form-control form-control-sm" name="materials[${rowCount}][keterangan]" placeholder="Keterangan">
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    
    tbody.appendChild(newRow);
    rowCount++;
    
    // Add event listener for material selection
    const select = newRow.querySelector('select');
    select.addEventListener('change', function() {
        const satuanInput = newRow.querySelector('input[name*="[satuan]"]');
        const selectedOption = this.options[this.selectedIndex];
        satuanInput.value = selectedOption.getAttribute('data-satuan') || '';
    });
}

// Function to remove row
function removeRow(button) {
    const row = button.closest('tr');
    const tbody = row.parentNode;
    
    if (tbody.children.length > 1) {
        row.remove();
        
        // Update row numbers
        const rows = tbody.querySelectorAll('tr');
        rows.forEach((row, index) => {
            row.querySelector('td:first-child').textContent = index + 1;
        });
    } else {
        alert('Minimal harus ada satu material!');
    }
}

// Initialize material selection event listeners
document.addEventListener('DOMContentLoaded', function() {
    const materialSelects = document.querySelectorAll('select[name*="[material_id]"]');
    
    materialSelects.forEach(select => {
        select.addEventListener('change', function() {
            const row = this.closest('tr');
            const satuanInput = row.querySelector('input[name*="[satuan]"]');
            const selectedOption = this.options[this.selectedIndex];
            satuanInput.value = selectedOption.getAttribute('data-satuan') || '';
        });
    });
});

// Form validation
document.getElementById('suratJalanForm').addEventListener('submit', function(e) {
    const materials = document.querySelectorAll('select[name*="[material_id]"]');
    let hasValidMaterial = false;
    
    materials.forEach(select => {
        if (select.value) {
            hasValidMaterial = true;
        }
    });
    
    if (!hasValidMaterial) {
        e.preventDefault();
        alert('Minimal harus ada satu material yang dipilih!');
        return false;
    }
});
</script>
@endpush