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
                        <i class="fa fa-plus-circle me-2"></i>
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
                                    <i class="fa fa-file-alt me-1"></i>
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
                                    <option value="Garansi">Garansi</option>
                                    <option value="Peminjaman">Peminjaman</option>
                                    <option value="Perbaikan">Perbaikan</option>
                                    <option value="Manual">Manual</option>

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
                                    <i class="fa fa-user-check me-1"></i>
                                    Informasi Penerima
                                </h6>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="kepada" class="form-label fw-semibold">
                                    Diberikan Kepada <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="kepada" 
                                       name="kepada" 
                                       placeholder="Vendor / Unit PLN"
                                       required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="berdasarkan" class="form-label fw-semibold">
                                    Berdasarkan <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="berdasarkan" 
                                       name="berdasarkan" 
                                       placeholder="Reservasi/Permintaan" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="keterangan" class="form-label fw-semibold">
                                    Untuk Pekerjaan
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="keterangan" 
                                       name="keterangan" 
                                       placeholder="Pekerjaan / PB PD Rutin / STO">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="nomor_slip" class="form-label fw-semibold">
                                    Nomor Slip
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="nomor_slip" 
                                       name="nomor_slip" 
                                       placeholder="No SAP : TUG8 / TUG9">
                            </div>
                            
                            <!-- Foto Penerima -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fa fa-camera me-1"></i> Foto Penerima
                                </label>
                                <div class="d-flex align-items-start gap-3">
                                    <div>
                                        <button type="button" class="btn btn-outline-primary" onclick="openCameraModal()">
                                            <i class="fa fa-camera me-1"></i> Ambil Gambar
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary ms-2" onclick="uploadFromGallery()">
                                            <i class="fa fa-image me-1"></i> Pilih dari Galeri
                                        </button>
                                        <input type="file" id="galleryInput" accept="image/*" style="display: none;" onchange="handleGalleryUpload(event)">
                                    </div>
                                    <div id="photoPreview" class="d-none">
                                        <div class="position-relative" style="display: inline-block;">
                                            <img id="thumbnailImg" src="" alt="Preview" 
                                                 style="max-width: 150px; max-height: 150px; border-radius: 8px; border: 2px solid #ddd;">
                                            <button type="button" class="btn btn-danger btn-sm position-absolute" 
                                                    style="top: -8px; right: -8px; border-radius: 50%; width: 24px; height: 24px; padding: 0;"
                                                    onclick="removePhoto()">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                        <input type="hidden" name="foto_penerima" id="fotoBase64">
                                    </div>
                                </div>
                                <small class="text-muted">Foto akan dikompresi ke resolusi 720p</small>
                            </div>
                        </div>
                        
                        <!-- Daftar Material -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-info border-bottom pb-2">
                                    <i class="fa fa-boxes me-1"></i>
                                    Daftar Material
                                </h6>
                            </div>
                            
                            <div class="col-12 mb-3">
                                <div id="manualNotice" class="alert alert-info d-none">
                                    <i class="fa fa-info-circle me-1"></i>
                                    Mode <strong>Manual / Peminjaman</strong> aktif. Isi nama barang secara bebas tanpa memilih dari daftar material.
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="materialTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="30%">Material</th>
                                                <!-- <th width="10%">Stock</th> -->
                                                <th width="10%" class="col-stock">Stock</th>
                                                <th width="10%">Qty</th>
                                                <th width="10%">Satuan</th>
                                                <th width="25%">Keterangan</th>
                                                <th width="10%">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
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
                        
                        <!-- Informasi Kendaraan -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-info border-bottom pb-2">
                                    <i class="fa fa-truck me-1"></i>
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
                            
                            <div class="col-md-4 mb-3">
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
                        
                        <!-- Action Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('surat-jalan.index') }}" class="btn btn-secondary">
                                        <i class="fa fa-arrow-left me-1"></i>
                                        Kembali
                                    </a>
                                    
                                    <div>
                                        <button type="reset" class="btn btn-warning me-2">
                                            <i class="fa fa-undo me-1"></i>
                                            Reset Form
                                        </button>
                                        
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-save me-1"></i>
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

<!-- Camera Modal -->
<div class="modal fade" id="cameraModal" tabindex="-1" aria-labelledby="cameraModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cameraModalLabel">
                    <i class="fa fa-camera me-2"></i> Ambil Foto Penerima
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closeCameraModal()"></button>
            </div>
            <div class="modal-body text-center">
                <div id="cameraContainer">
                    <video id="cameraVideo" autoplay playsinline style="max-width: 100%; border-radius: 8px;"></video>
                </div>
                <canvas id="cameraCanvas" style="display: none;"></canvas>
                <div class="mt-3">
                    <button type="button" class="btn btn-success btn-lg" onclick="capturePhoto()">
                        <i class="fa fa-camera me-1"></i> Capture
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="switchCamera()">
                        <i class="fa fa-sync-alt me-1"></i> Ganti Kamera
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeCameraModal()">Tutup</button>
            </div>
        </div>
    </div>
</div>

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
let jenisSelect;
let table;
let notice;

// Function to update nomor surat based on jenis surat jalan
function updateNomorSurat() {
    const jenisSuratJalan = document.getElementById('jenis_surat_jalan').value;
    
    // Make AJAX call to get proper nomor surat with sequence
    fetch(`{{ route('surat-jalan.generate-nomor') }}?jenis=${jenisSuratJalan}`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.nomor_surat) {
            document.getElementById('nomor_surat').value = data.nomor_surat;
        }
    })
    .catch(error => {
        console.error('Error generating nomor surat:', error);
        // Fallback to default if error occurs
        document.getElementById('nomor_surat').value = '001.SJ/LOG.00.02/F02050000/I/2025';
    });
}

// Event listener for jenis surat jalan change
document.addEventListener('DOMContentLoaded', function() {
    const jenisSuratJalanSelect = document.getElementById('jenis_surat_jalan');
    if (jenisSuratJalanSelect) {
        jenisSuratJalanSelect.addEventListener('change', function () {
    updateNomorSurat();
    resetTable();
});
    }
});

// Function to add new row
function addRow() {
    const tbody = document.querySelector('#materialTable tbody');
    const index = tbody.querySelectorAll('tr').length;
    const jenis = jenisSelect.value;
    const isManual = ['Manual', 'Peminjaman'].includes(jenis);
    const isNonStock = ['Garansi', 'Perbaikan'].includes(jenis);


    const newRow = document.createElement('tr');

if (isManual) {
    newRow.innerHTML = `
        <td>${index + 1}</td>

        <td>
            <input type="text" class="form-control form-control-sm"
                   name="materials[${index}][nama_barang]" required>
        </td>

        <!-- ⛔ WAJIB ADA: TD STOCK (DISEMBUNYIKAN) -->
        <td class="col-stock" style="display:none;"></td>

        <td>
            <input type="number" class="form-control form-control-sm"
                   name="materials[${index}][quantity]" min="1" required>
        </td>

        <td>
            <input type="text" class="form-control form-control-sm"
                   name="materials[${index}][satuan]" required>
        </td>

        <td>
            <input type="text" class="form-control form-control-sm"
                   name="materials[${index}][keterangan]">
        </td>

        <td>
            <button type="button" class="btn btn-danger btn-sm"
                    onclick="removeRow(this)">
                <i class="fa fa-trash"></i>
            </button>
        </td>
    `;
    } else {
        newRow.innerHTML = `
            <td>${index + 1}</td>
            <td style="position:relative;">
                <input type="text" class="form-control form-control-sm material-autocomplete"
                       name="materials[${index}][material_search]" autocomplete="off" required>
                <input type="hidden" name="materials[${index}][material_id]" class="material-id">
                <div class="autocomplete-results"></div>
            </td>
            <td class="col-stock" style="${isNonStock ? 'display:none;' : ''}">
    <input type="number" class="form-control form-control-sm"
           name="materials[${index}][stock]" readonly disabled>
</td>

            <td>
                <input type="number" class="form-control form-control-sm"
                       name="materials[${index}][quantity]" min="1" required>
            </td>
            <td>
    <input type="text" class="form-control form-control-sm"
           name="materials[${index}][satuan]"
           ${jenis === 'Normal' ? 'readonly' : ''}>
</td>

            <td>
                <input type="text" class="form-control form-control-sm"
                       name="materials[${index}][keterangan]">
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm"
                        onclick="removeRow(this)">
                    <i class="fa fa-trash"></i>
                </button>
            </td>
        `;
    }

    tbody.appendChild(newRow);

    if (!isManual) {
        initializeAutocomplete(newRow.querySelector('.material-autocomplete'));
    }

    addQuantityValidation(newRow.querySelector('input[name*="[quantity]"]'));

}
function resetTable() {
    const tbody = table.querySelector('tbody');
    tbody.innerHTML = ''; // hapus semua row
    addRow();             // buat row baru sesuai jenis
    toggleManualMode();   // rapikan tampilan
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
            
                            const jenis = document.getElementById('jenis_surat_jalan').value;
                            const showStockInfo = (jenis === 'Normal');

                            const stockInfo = showStockInfo
                                ? `<small class="text-info d-block">
                                    Stock: ${material.unrestricted_use_stock || 0} ${material.base_unit_of_measure || ''}
                                </small>`
                                : '';

                            
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

    // ✅ HANYA NORMAL yang boleh isi stock
    const jenis = document.getElementById('jenis_surat_jalan').value;
    if (jenis === 'Normal') {
        stockInput.value = material.unrestricted_use_stock || 0;
        stockInput.disabled = false;
    } else {
        stockInput.value = '';
        stockInput.disabled = true;
    }

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

// Function to validate stock quantity
function validateStockQuantity(quantityInput, showAlert = true) {
    const jenis = document.getElementById('jenis_surat_jalan').value;

    // 🔕 SKIP VALIDASI STOK
    if (['Garansi', 'Perbaikan', 'Manual', 'Peminjaman'].includes(jenis)) {
        return true;
    }

    const row = quantityInput.closest('tr');
    const stockInput = row.querySelector('input[name*="[stock]"]');
    const materialSearch = row.querySelector('.material-autocomplete');
    
    const quantity = parseInt(quantityInput.value) || 0;
    const stock = parseInt(stockInput.value) || 0;
    
    if (quantity > stock && materialSearch.value.trim() !== '') {
        if (showAlert) {
            alert(`Quantity (${quantity}) melebihi stock yang tersedia (${stock}).`);
        }
        return false;
    }
    return true;
}

// Function to add quantity validation to input
function addQuantityValidation(quantityInput) {
    let lastValidatedValue = '';
    
    quantityInput.addEventListener('blur', function() {
        // Only show alert if value has changed since last validation
        if (this.value !== lastValidatedValue) {
            if (!validateStockQuantity(this, true)) {
                lastValidatedValue = this.value;
                this.focus();
                this.select();
            } else {
                lastValidatedValue = this.value;
            }
        }
    });
    
    quantityInput.addEventListener('input', function() {
        const row = this.closest('tr');
        const stockInput = row.querySelector('input[name*="[stock]"]');
        const materialSearch = row.querySelector('.material-autocomplete');
        
        const quantity = parseInt(this.value) || 0;
        const stock = parseInt(stockInput.value) || 0;
        
        // Real-time visual feedback
        const jenis = document.getElementById('jenis_surat_jalan').value;

if (
    quantity > stock &&
    materialSearch.value.trim() !== '' &&
    !['Garansi','Perbaikan','Manual','Peminjaman'].includes(jenis)
) {
    this.style.borderColor = '#dc3545';
    this.style.backgroundColor = '#f8d7da';
} else {
    this.style.borderColor = '';
    this.style.backgroundColor = '';
}

    });
}

// Function to check for duplicate materials
function checkDuplicateMaterials() {
    const materialIds = document.querySelectorAll('.material-id');
    const materialSearchInputs = document.querySelectorAll('.material-autocomplete');
    const duplicates = [];
    const seenMaterials = new Map();
    
    materialIds.forEach((input, index) => {
        const materialId = input.value;
        const materialSearch = materialSearchInputs[index].value.trim();
        
        if (materialId && materialSearch) {
            // Extract material code from the search input format: [CODE - DESCRIPTION]
            const codeMatch = materialSearch.match(/\[([^\]]+)\s*-/);
            const materialCode = codeMatch ? codeMatch[1].trim() : '';
            
            // Check for duplicate by material_id
            if (seenMaterials.has(materialId)) {
                const firstOccurrence = seenMaterials.get(materialId);
                duplicates.push({
                    materialId: materialId,
                    materialCode: materialCode,
                    materialName: materialSearch,
                    rows: [firstOccurrence.row, index + 1]
                });
            } else {
                seenMaterials.set(materialId, {
                    row: index + 1,
                    code: materialCode,
                    name: materialSearch
                });
            }
        }
    });
    
    return duplicates;
}

// Form validation
document.getElementById('suratJalanForm').addEventListener('submit', function(e) {
    const jenis = document.getElementById('jenis_surat_jalan').value;

    if (['Manual', 'Peminjaman'].includes(jenis)) {
        const names = document.querySelectorAll('input[name*="[nama_barang]"]');
        const hasValue = [...names].some(i => i.value.trim() !== '');

        if (!hasValue) {
            e.preventDefault();
            alert('Minimal harus ada satu nama barang');
        }
        return;
    }


    const materialIds = document.querySelectorAll('.material-id');
    let hasValidMaterial = false;
    let hasStockError = false;
    
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
    
    // Check for duplicate materials
    const duplicates = checkDuplicateMaterials();
    if (duplicates.length > 0) {
        e.preventDefault();
        let alertMessage = 'Terdapat material yang sama dalam list:\n\n';
        
        duplicates.forEach(duplicate => {
            alertMessage += `• Material: ${duplicate.materialName}\n`;
            alertMessage += `  Ditemukan di baris: ${duplicate.rows.join(', ')}\n\n`;
        });
        
        alertMessage += 'Silakan hapus atau ganti salah satu material yang sama.';
        alert(alertMessage);
        return false;
    }
    
    // Validate all quantity inputs before submission
    const quantityInputs = document.querySelectorAll('input[name*="[quantity]"]');
    quantityInputs.forEach(input => {
        if (!validateStockQuantity(input, false)) {
            hasStockError = true;
        }
    });
    
    if (hasStockError) {
        e.preventDefault();
        alert('Terdapat quantity yang melebihi stock. Silakan periksa kembali.');
        return false;
    }
});
function toggleManualMode() {
    const jenis = jenisSelect.value;

    const isManual = ['Manual', 'Peminjaman'].includes(jenis);
    const isNonStock = ['Garansi', 'Perbaikan', 'Manual', 'Peminjaman'].includes(jenis);

    // Header kolom nama
    table.querySelector('th:nth-child(2)').textContent =
        isManual ? 'Nama Barang (Manual)' : 'Material';

    // Stock column (hidden hanya kalau non-stock)
    table.querySelectorAll('.col-stock').forEach(th => {
        th.style.display = isNonStock ? 'none' : '';
    });

    // ⚠️ NOTICE MANUAL → HANYA MANUAL
    notice.classList.toggle('d-none', !isManual);
}


// === Toggle mode Manual ===
document.addEventListener('DOMContentLoaded', function () {
    jenisSelect = document.getElementById('jenis_surat_jalan');
    table = document.getElementById('materialTable');
    notice = document.getElementById('manualNotice');

    jenisSelect.addEventListener('change', resetTable);

    resetTable(); // ⬅️ PENTING: bikin row pertama
});

// ============================================
// CAMERA CAPTURE FUNCTIONS
// ============================================
let currentStream = null;
let useFrontCamera = true;

// Open camera modal
function openCameraModal() {
    const modal = document.getElementById('cameraModal');
    modal.style.display = 'block';
    modal.classList.add('show');
    document.body.classList.add('modal-open');
    
    // Try to use Bootstrap modal if available
    if (typeof $ !== 'undefined' && $.fn.modal) {
        $('#cameraModal').modal('show');
    }
    
    startCamera();
}

// Close camera modal
function closeCameraModal() {
    stopCamera();
    
    const modal = document.getElementById('cameraModal');
    modal.style.display = 'none';
    modal.classList.remove('show');
    document.body.classList.remove('modal-open');
    
    // Remove backdrop if exists
    const backdrop = document.querySelector('.modal-backdrop');
    if (backdrop) backdrop.remove();
    
    if (typeof $ !== 'undefined' && $.fn.modal) {
        $('#cameraModal').modal('hide');
    }
}

// Start camera
async function startCamera() {
    try {
        if (currentStream) {
            stopCamera();
        }
        
        // Check if mediaDevices is available
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            let errorMsg = 'Browser tidak mendukung akses kamera.';
            
            // Check if running on non-secure context
            if (location.protocol !== 'https:' && location.hostname !== 'localhost' && location.hostname !== '127.0.0.1') {
                errorMsg = 'Kamera membutuhkan HTTPS atau localhost.\n\n' +
                           'Solusi:\n' +
                           '1. Akses via http://localhost:8000 atau\n' +
                           '2. Akses via http://127.0.0.1:8000 atau\n' +
                           '3. Gunakan fitur "Pilih dari Galeri" untuk upload foto';
            }
            
            alert(errorMsg);
            closeCameraModal();
            return;
        }
        
        const constraints = {
            video: {
                facingMode: useFrontCamera ? 'user' : 'environment',
                width: { ideal: 1280 },
                height: { ideal: 720 }
            }
        };
        
        currentStream = await navigator.mediaDevices.getUserMedia(constraints);
        const video = document.getElementById('cameraVideo');
        video.srcObject = currentStream;
    } catch (err) {
        console.error('Error accessing camera:', err);
        
        let errorMsg = 'Tidak dapat mengakses kamera.';
        
        if (err.name === 'NotAllowedError' || err.name === 'PermissionDeniedError') {
            errorMsg = 'Izin kamera ditolak. Silakan izinkan akses kamera di browser.\n\n' +
                       'Klik ikon kunci/info di address bar untuk mengubah pengaturan.';
        } else if (err.name === 'NotFoundError' || err.name === 'DevicesNotFoundError') {
            errorMsg = 'Kamera tidak ditemukan. Pastikan perangkat memiliki kamera.';
        } else if (err.name === 'NotReadableError' || err.name === 'TrackStartError') {
            errorMsg = 'Kamera sedang digunakan aplikasi lain.';
        } else if (err.name === 'OverconstrainedError') {
            errorMsg = 'Kamera tidak mendukung resolusi yang diminta.';
        } else if (err.name === 'TypeError') {
            errorMsg = 'Kamera membutuhkan HTTPS atau localhost.\n\n' +
                       'Gunakan http://localhost:8000 atau "Pilih dari Galeri"';
        }
        
        alert(errorMsg);
        closeCameraModal();
    }
}

// Stop camera
function stopCamera() {
    if (currentStream) {
        currentStream.getTracks().forEach(track => track.stop());
        currentStream = null;
    }
}

// Switch between front and back camera
function switchCamera() {
    useFrontCamera = !useFrontCamera;
    startCamera();
}

// Capture photo from camera
function capturePhoto() {
    const video = document.getElementById('cameraVideo');
    const canvas = document.getElementById('cameraCanvas');
    
    // Get video dimensions
    const videoWidth = video.videoWidth;
    const videoHeight = video.videoHeight;
    
    // Calculate 720p dimensions maintaining aspect ratio
    const maxHeight = 720;
    const maxWidth = 1280;
    
    let targetWidth, targetHeight;
    
    if (videoWidth / videoHeight > maxWidth / maxHeight) {
        targetWidth = maxWidth;
        targetHeight = Math.round(maxWidth * videoHeight / videoWidth);
    } else {
        targetHeight = maxHeight;
        targetWidth = Math.round(maxHeight * videoWidth / videoHeight);
    }
    
    canvas.width = targetWidth;
    canvas.height = targetHeight;
    
    const ctx = canvas.getContext('2d');
    ctx.drawImage(video, 0, 0, targetWidth, targetHeight);
    
    // Compress to JPEG with quality 0.7
    const compressedBase64 = canvas.toDataURL('image/jpeg', 0.7);
    
    // Set thumbnail and hidden input
    setPhotoPreview(compressedBase64);
    
    // Close modal
    closeCameraModal();
}

// Handle gallery upload
function uploadFromGallery() {
    document.getElementById('galleryInput').click();
}

function handleGalleryUpload(event) {
    const file = event.target.files[0];
    if (!file) return;
    
    const reader = new FileReader();
    reader.onload = function(e) {
        compressImage(e.target.result, function(compressedBase64) {
            setPhotoPreview(compressedBase64);
        });
    };
    reader.readAsDataURL(file);
}

// Compress image to 720p
function compressImage(base64, callback) {
    const img = new Image();
    img.onload = function() {
        const canvas = document.createElement('canvas');
        
        const maxHeight = 720;
        const maxWidth = 1280;
        
        let targetWidth, targetHeight;
        
        if (img.width / img.height > maxWidth / maxHeight) {
            targetWidth = Math.min(img.width, maxWidth);
            targetHeight = Math.round(targetWidth * img.height / img.width);
        } else {
            targetHeight = Math.min(img.height, maxHeight);
            targetWidth = Math.round(targetHeight * img.width / img.height);
        }
        
        canvas.width = targetWidth;
        canvas.height = targetHeight;
        
        const ctx = canvas.getContext('2d');
        ctx.drawImage(img, 0, 0, targetWidth, targetHeight);
        
        // Compress to JPEG with quality 0.7
        const compressedBase64 = canvas.toDataURL('image/jpeg', 0.7);
        callback(compressedBase64);
    };
    img.src = base64;
}

// Set photo preview
function setPhotoPreview(base64) {
    const preview = document.getElementById('photoPreview');
    const thumbnail = document.getElementById('thumbnailImg');
    const hiddenInput = document.getElementById('fotoBase64');
    
    thumbnail.src = base64;
    hiddenInput.value = base64;
    preview.classList.remove('d-none');
}

// Remove photo
function removePhoto() {
    const preview = document.getElementById('photoPreview');
    const thumbnail = document.getElementById('thumbnailImg');
    const hiddenInput = document.getElementById('fotoBase64');
    
    thumbnail.src = '';
    hiddenInput.value = '';
    preview.classList.add('d-none');
    
    // Reset gallery input
    document.getElementById('galleryInput').value = '';
}

</script>
@endpush