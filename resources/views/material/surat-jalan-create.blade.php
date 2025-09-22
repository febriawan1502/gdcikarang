@extends('layouts.app')

@section('title', 'Buat Surat Jalan')

@section('page-title', 'Buat Surat Jalan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-plus-circle me-2"></i>
                        Buat Surat Jalan Baru
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('surat-jalan.store') }}" method="POST" id="suratJalanForm">
                        @csrf
                        
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
                                       value="{{ $nomorSurat }}"
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
                                    <option value="Normal" selected>Normal</option>
                                    <option value="Gangguan">Gangguan</option>
                                    <option value="Garansi">Garansi</option>
                                    <option value="Peminjaman">Peminjaman</option>
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
                                       value="{{ date('Y-m-d') }}"
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
                                       placeholder="Nama perusahaan/instansi penerima"
                                       required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="berdasarkan" class="form-label fw-semibold">
                                    Berdasarkan <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control" 
                                          id="berdasarkan" 
                                          name="berdasarkan" 
                                          rows="2"
                                          required></textarea>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="security" class="form-label fw-semibold">
                                    Security
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="security" 
                                       name="security" 
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
                                                <th width="30%">Material</th>
                                                <th width="10%">Stock</th>
                                                <th width="10%">Qty</th>
                                                <th width="10%">Satuan</th>
                                                <th width="25%">Keterangan</th>
                                                <th width="10%">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td style="position: relative;">
                                                    <input type="text" class="form-control form-control-sm material-autocomplete" 
                                                           name="materials[0][material_search]" 
                                                           placeholder="Ketik untuk mencari material..." 
                                                           autocomplete="off" required>
                                                    <input type="hidden" name="materials[0][material_id]" class="material-id">
                                                    <div class="autocomplete-results" style="display: none; position: absolute; z-index: 1000; background: white; border: 1px solid #ddd; max-height: 400px; overflow-y: auto; width: 100%;"></div>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control form-control-sm" name="materials[0][stock]" readonly disabled>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control form-control-sm" name="materials[0][quantity]" min="1" required>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control form-control-sm" name="materials[0][satuan]" readonly>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control form-control-sm" name="materials[0][keterangan]" placeholder="Keterangan">
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="mt-2" style="display: flex !important; justify-content: flex-end !important; width: 100%;">
                                    <button type="button" class="btn btn-success btn-sm" onclick="addRow()">
                                        <i class="fa fa-plus me-1"></i>
                                        Tambah Material
                                    </button>
                                </div>
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
                                          placeholder="Keterangan tambahan untuk surat jalan (opsional)"></textarea>
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
                                       placeholder="Nama pengemudi">
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('surat-jalan.index') }}" class="btn btn-secondary">
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
                                            Simpan Surat Jalan
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

@push('styles')
<style>
.table-responsive {
    overflow: visible !important;
}
.table-responsive table {
    overflow: visible !important;
}
.autocomplete-results {
    z-index: 9999 !important;
}
</style>
@endpush

@push('scripts')
<script>
let rowCount = 1;

// Function to update nomor surat based on jenis surat jalan
function updateNomorSurat() {
    const jenisSuratJalan = document.getElementById('jenis_surat_jalan').value;
    const year = new Date().getFullYear();
    
    const formats = {
        'Normal': `114.SJ/LOG.00.02/F02050000/${year}`,
        'Gangguan': `114.GGN/LOG.00.02/F02050000/${year}`,
        'Garansi': `114.GRN/LOG.00.02/F02050000/${year}`,
        'Peminjaman': `114.PMJ/LOG.00.02/F02050000/${year}`
    };
    
    const nomorSurat = formats[jenisSuratJalan] || formats['Normal'];
    document.getElementById('nomor_surat').value = nomorSurat;
}

// Event listener for jenis surat jalan change
document.addEventListener('DOMContentLoaded', function() {
    const jenisSuratJalanSelect = document.getElementById('jenis_surat_jalan');
    if (jenisSuratJalanSelect) {
        jenisSuratJalanSelect.addEventListener('change', updateNomorSurat);
        // Update on page load
        updateNomorSurat();
    }
});

// Function to add new row
function addRow() {
    const tbody = document.querySelector('#materialTable tbody');
    const newRow = document.createElement('tr');
    
    newRow.innerHTML = `
        <td>${rowCount + 1}</td>
        <td style="position: relative;">
            <input type="text" class="form-control form-control-sm material-autocomplete" 
                   name="materials[${rowCount}][material_search]" 
                   placeholder="Ketik untuk mencari material..." 
                   autocomplete="off" required>
            <input type="hidden" name="materials[${rowCount}][material_id]" class="material-id">
            <div class="autocomplete-results" style="display: none; position: absolute; z-index: 1000; background: white; border: 1px solid #ddd; max-height: 400px; overflow-y: auto; width: 100%;"></div>
        </td>
        <td>
            <input type="number" class="form-control form-control-sm" name="materials[${rowCount}][stock]" readonly disabled>
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
    
    // Add autocomplete functionality for new row
    initializeAutocomplete(newRow.querySelector('.material-autocomplete'));
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

// Initialize autocomplete functionality
function initializeAutocomplete(input) {
    let timeout;
    const resultsDiv = input.nextElementSibling.nextElementSibling; // autocomplete-results div
    
    input.addEventListener('input', function() {
        clearTimeout(timeout);
        const query = this.value;
        
        if (query.length < 3) {
            resultsDiv.style.display = 'none';
            return;
        }
        
        timeout = setTimeout(() => {
            fetch(`/material/autocomplete?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    resultsDiv.innerHTML = '';
                    
                    if (data.length > 0) {
                        data.forEach(material => {
                            const item = document.createElement('div');
                            item.className = 'autocomplete-item p-2 cursor-pointer hover:bg-gray-100';
                            item.style.cursor = 'pointer';
                            item.style.padding = '8px';
                            item.style.borderBottom = '1px solid #eee';
                            
                            const stockInfo = material.unrestricted_use_stock ? 
                                `<small class="text-info d-block">Stock: ${material.unrestricted_use_stock} ${material.base_unit_of_measure || ''}</small>` : 
                                '<small class="text-muted d-block">Stock: 0</small>';
                            
                            item.innerHTML = `
                                <div>[${material.material_code} - ${material.material_description}]</div>
                                ${stockInfo}
                            `;
                            
                            item.addEventListener('click', function() {
                                const row = input.closest('tr');
                                const hiddenInput = row.querySelector('.material-id');
                                const satuanInput = row.querySelector('input[name*="[satuan]"]');
                                const stockInput = row.querySelector('input[name*="[stock]"]');
                                
                                input.value = `[${material.material_code} - ${material.material_description}]`;
                                hiddenInput.value = material.id;
                                satuanInput.value = material.base_unit_of_measure || '';
                                stockInput.value = material.unrestricted_use_stock || 0;
                                resultsDiv.style.display = 'none';
                            });
                            
                            resultsDiv.appendChild(item);
                        });
                        resultsDiv.style.display = 'block';
                    } else {
                        resultsDiv.style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Error fetching materials:', error);
                    resultsDiv.style.display = 'none';
                });
        }, 300);
    });
    
    // Hide results when clicking outside
    document.addEventListener('click', function(e) {
        if (!input.contains(e.target) && !resultsDiv.contains(e.target)) {
            resultsDiv.style.display = 'none';
        }
    });
}

// Initialize autocomplete on page load
document.addEventListener('DOMContentLoaded', function() {
    const autocompleteInputs = document.querySelectorAll('.material-autocomplete');
    autocompleteInputs.forEach(input => {
        initializeAutocomplete(input);
    });
});

// Form validation
document.getElementById('suratJalanForm').addEventListener('submit', function(e) {
    const materialIds = document.querySelectorAll('.material-id');
    let hasValidMaterial = false;
    
    materialIds.forEach(input => {
        if (input.value) {
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