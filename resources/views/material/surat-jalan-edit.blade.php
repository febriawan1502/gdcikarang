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
                    <form action="{{ route('surat-jalan.update', $suratJalan->id) }}" method="POST" id="suratJalanForm">
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
                                                    <input type="text" 
                                                           class="form-control form-control-sm material-autocomplete" 
                                                           name="materials[{{ $index }}][material_display]" 
                                                           value="{{ $detail->material->material_code ?? '' }} - {{ $detail->material->material_description ?? '' }}"
                                                           placeholder="Ketik kode atau nama material..."
                                                           autocomplete="off"
                                                           required>
                                                    <input type="hidden" 
                                                           name="materials[{{ $index }}][material_id]" 
                                                           value="{{ $detail->material_id }}"
                                                           class="material-id-input">
                                                    <div class="autocomplete-suggestions" style="display: none;"></div>
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

@push('styles')
<style>
.table-responsive {
    overflow-x: auto;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.form-control-sm {
    height: calc(1.5em + 0.5rem + 2px);
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.text-center {
    text-align: center;
}

.text-right {
    text-align: right;
}

#materialTable tbody tr:hover {
    background-color: #f8f9fa;
}

.invalid-feedback {
    display: block;
}

/* Autocomplete Styles */
.autocomplete-container {
    position: relative;
}

.autocomplete-suggestions {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #ddd;
    border-top: none;
    max-height: 200px;
    overflow-y: auto;
    z-index: 9999;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    display: none;
}

/* Ensure parent td has relative positioning */
td {
    position: relative;
}

.autocomplete-item {
    padding: 8px 12px;
    cursor: pointer;
    border-bottom: 1px solid #eee;
}

.autocomplete-item:hover {
    background-color: #f8f9fa;
}

.autocomplete-item:last-child {
    border-bottom: none;
}
</style>
@endpush

@push('scripts')
<script>
let rowCount = {{ count($suratJalan->details) }};

// Materials data for JavaScript
const materialsData = [
    @foreach($materials as $material)
    {
        id: {{ $material->id }},
        kode: '{{ addslashes($material->material_code ?? '') }}',
        nama: '{{ addslashes($material->material_description ?? '') }}',
        satuan: '{{ addslashes($material->base_unit_of_measure ?? '') }}',
        stock: {{ $material->unrestricted_use_stock ?? 0 }}
    },
    @endforeach
];

// Function to add new row
function addRow() {
    const tbody = document.querySelector('#materialTable tbody');
    const newRow = document.createElement('tr');
    
    newRow.innerHTML = `
        <td>${rowCount + 1}</td>
        <td style="position: relative;">
            <input type="text" 
                   class="form-control form-control-sm material-autocomplete" 
                   name="materials[${rowCount}][material_display]" 
                   placeholder="Ketik kode atau nama material..."
                   autocomplete="off"
                   required>
            <input type="hidden" 
                   name="materials[${rowCount}][material_id]" 
                   class="material-id-input">
            <div class="autocomplete-suggestions" style="display: none;"></div>
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
    
    // Initialize autocomplete for the new row
    const newInput = newRow.querySelector('.material-autocomplete');
    
    if (newInput) {
        initializeAutocomplete(newInput);
    }
    
    rowCount++;
    
    // Update all row numbers and input names
    updateRowNumbers();
}

// Function to remove row
function removeRow(button) {
    const row = button.closest('tr');
    const tbody = row.parentNode;
    
    if (tbody.children.length > 1) {
        row.remove();
        updateRowNumbers();
    } else {
        alert('Minimal harus ada satu material!');
    }
}

// Initialize autocomplete functionality
function initializeAutocomplete(input) {
    const row = input.closest('tr');
    const hiddenInput = row.querySelector('input[type="hidden"]');
    const suggestionsDiv = row.querySelector('.autocomplete-suggestions');
    const satuanInput = row.querySelector('input[name*="[satuan]"]');
    
    if (!hiddenInput || !suggestionsDiv || !satuanInput) {
        return;
    }
    
    input.addEventListener('input', function() {
        const query = this.value.toLowerCase();
        console.log('Input event triggered, query:', query);
        if (query.length < 2) {
            suggestionsDiv.style.display = 'none';
            return;
        }
        
        const filteredMaterials = materialsData.filter(material => {
            const kode = material.kode || '';
            const nama = material.nama || '';
            return kode.toLowerCase().includes(query) || nama.toLowerCase().includes(query);
        });
        
        console.log('Filtered materials:', filteredMaterials);
        
        if (filteredMaterials.length === 0) {
            console.log('No materials found for query:', query);
            suggestionsDiv.style.display = 'none';
            return;
        }
        
        if (filteredMaterials.length > 0) {
            suggestionsDiv.innerHTML = filteredMaterials.slice(0, 10).map(material => {
                const displayText = material.kode ? 
                    `<strong>${material.kode}</strong> - ${material.nama}` : 
                    `${material.nama}`;
                return `<div class="autocomplete-item" data-id="${material.id}" data-satuan="${material.satuan}">
                    ${displayText}
                </div>`;
            }).join('');
            suggestionsDiv.style.display = 'block';
            
            // Add click listeners to suggestions
            suggestionsDiv.querySelectorAll('.autocomplete-item').forEach(item => {
                item.addEventListener('click', function() {
                    const materialId = this.getAttribute('data-id');
                    const materialSatuan = this.getAttribute('data-satuan');
                    const materialText = this.textContent.trim();
                    
                    input.value = materialText;
                    hiddenInput.value = materialId;
                    satuanInput.value = materialSatuan;
                    suggestionsDiv.style.display = 'none';
                });
            });
        } else {
            suggestionsDiv.style.display = 'none';
        }
    });
    
    // Hide suggestions when clicking outside
    input.addEventListener('blur', function() {
        // Use setTimeout to allow click events on suggestions to fire first
        setTimeout(() => {
            suggestionsDiv.style.display = 'none';
        }, 200);
    });
}

// Initialize autocomplete for existing inputs
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded');
    const materialInputs = document.querySelectorAll('.material-autocomplete');
    console.log('Found material inputs:', materialInputs.length);
    console.log('Materials data:', materialsData);
    
    materialInputs.forEach((input, index) => {
        console.log(`Initializing autocomplete for input ${index}:`, input);
        initializeAutocomplete(input);
    });
    
    // Update row numbers for existing rows
    updateRowNumbers();
});

// Function to update row numbers
function updateRowNumbers() {
    const rows = document.querySelectorAll('#materialTable tbody tr');
    rows.forEach((row, index) => {
        row.querySelector('td:first-child').textContent = index + 1;
        
        // Update input names to maintain proper indexing
        const inputs = row.querySelectorAll('input');
        inputs.forEach(input => {
            const name = input.getAttribute('name');
            if (name && name.includes('materials[')) {
                const newName = name.replace(/materials\[\d+\]/, `materials[${index}]`);
                input.setAttribute('name', newName);
            }
        });
    });
}

// Form validation
document.getElementById('suratJalanForm').addEventListener('submit', function(e) {
    const materialInputs = document.querySelectorAll('input[name*="[material_id]"]');
    let hasValidMaterial = false;
    
    materialInputs.forEach(input => {
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