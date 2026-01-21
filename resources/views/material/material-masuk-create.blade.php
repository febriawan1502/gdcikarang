@extends('layouts.app')

@section('title', 'Tambah Material Masuk')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Tambah Material Masuk</h2>
            <p class="text-gray-500 text-sm mt-1">Isi form di bawah untuk menambahkan material masuk baru.</p>
        </div>
        <a href="{{ route('material-masuk.index') }}" class="flex items-center gap-2 text-gray-600 hover:text-gray-800 transition-colors">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Form Container -->
    <div class="card border border-gray-100 shadow-xl shadow-gray-200/50">
        <!-- Error Messages -->
        @if ($errors->any())
            <div class="mx-6 mt-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700">
                <div class="flex items-center gap-2 font-semibold mb-2">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>Terdapat kesalahan:</span>
                </div>
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('material-masuk.store') }}" method="POST" id="materialMasukForm">
            @csrf

            <!-- Section: Identitas Utama -->
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-teal-400 to-teal-500 flex items-center justify-center text-white">
                        <i class="fas fa-file-invoice"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Identitas Dokumen</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="nomor_kr" class="form-label">Nomor KR</label>
                        <input type="text" class="form-input" id="nomor_kr" name="nomor_kr" placeholder="Masukkan Nomor KR" value="{{ old('nomor_kr') }}">
                    </div>
                    <div>
                        <label for="pabrikan" class="form-label">Pabrikan</label>
                        <input type="text" class="form-input" id="pabrikan" name="pabrikan" placeholder="Masukkan Pabrikan" value="{{ old('pabrikan') }}">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label for="tanggal_masuk" class="form-label">
                            Tanggal Masuk <span class="text-red-500">*</span>
                        </label>
                        <input type="date" class="form-input" id="tanggal_masuk" name="tanggal_masuk" value="{{ old('tanggal_masuk', date('Y-m-d')) }}" required>
                    </div>
                    <div>
                        <label for="jenis" class="form-label">Jenis</label>
                        <select name="jenis" id="jenis" class="form-input">
                            <option value="">-- Pilih Jenis --</option>
                            <option value="B1" {{ old('jenis')=='B1' ? 'selected' : '' }}>B1</option>
                            <option value="B2" {{ old('jenis')=='B2' ? 'selected' : '' }}>B2</option>
                            <option value="AO" {{ old('jenis')=='AO' ? 'selected' : '' }}>AO</option>
                        </select>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                    <div>
                        <label for="nomor_po" class="form-label">Nomor PO</label>
                        <input type="text" class="form-input" id="nomor_po" name="nomor_po" placeholder="Masukkan Nomor PO" value="{{ old('nomor_po') }}">
                    </div>
                    <div>
                        <label for="nomor_doc" class="form-label">Nomor DOC</label>
                        <input type="text" class="form-input" id="nomor_doc" name="nomor_doc" placeholder="Masukkan Nomor DOC" value="{{ old('nomor_doc') }}">
                    </div>
                    <div>
                        <label for="tugas_4" class="form-label">Tug 4</label>
                        <input type="text" class="form-input" id="tugas_4" name="tugas_4" placeholder="Masukkan Tug 4" value="{{ old('tugas_4') }}">
                    </div>
                </div>
                
                <div class="mt-6">
                    <label for="keterangan" class="form-label">Keterangan</label>
                    <textarea class="form-input" id="keterangan" name="keterangan" rows="3" placeholder="Masukkan keterangan (opsional)">{{ old('keterangan') }}</textarea>
                </div>
            </div>

            <!-- Section: Detail Material -->
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-400 to-purple-500 flex items-center justify-center text-white">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Detail Material</h3>
                </div>
                
                <div class="overflow-x-auto rounded-xl border border-gray-100">
                    <table class="table-purity w-full" id="materialTable">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider w-12">No</th>
                                <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Material</th>
                                <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Normalisasi</th>
                                <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider w-24">Qty</th>
                                <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider w-24">Satuan</th>
                                <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider w-16">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="materialTableBody" class="divide-y divide-gray-100 bg-white">
                            <tr>
                                <td class="px-4 py-3 text-center text-gray-600">1</td>
                                <td class="px-4 py-3">
                                    <div class="autocomplete-container">
                                        <input type="text" class="form-input text-sm material-search" 
                                            name="materials[0][material_description]" 
                                            placeholder="Ketik untuk mencari material..." autocomplete="off" required>
                                        <input type="hidden" name="materials[0][material_id]" class="material-id">
                                        <input type="hidden" name="materials[0][material_name]" class="material-name">
                                        <div class="autocomplete-results"></div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="autocomplete-container">
                                        <input type="text" class="form-input text-sm normalisasi-search" 
                                            name="materials[0][normalisasi]" placeholder="Normalisasi">
                                        <div class="autocomplete-results"></div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" class="form-input text-sm" name="materials[0][quantity]" placeholder="Qty" min="1" required>
                                </td>
                                <td class="px-4 py-3">
                                    <input type="text" class="form-input text-sm" name="materials[0][satuan]" placeholder="Satuan" required>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <button type="button" class="p-2 rounded-lg text-red-500 hover:bg-red-50 transition-colors remove-row" onclick="removeRow(this)" disabled>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4 flex justify-end">
                    <button type="button" class="px-4 py-2 rounded-lg bg-green-500 text-white hover:bg-green-600 transition-colors flex items-center gap-2" onclick="addRow()">
                        <i class="fas fa-plus"></i>
                        <span>Tambah Material</span>
                    </button>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="p-6 bg-gray-50 flex flex-col sm:flex-row justify-between items-center gap-4">
                <a href="{{ route('material-masuk.index') }}" class="w-full sm:w-auto px-6 py-3 rounded-xl border border-gray-300 text-gray-600 hover:bg-gray-100 transition-colors flex items-center justify-center gap-2">
                    <i class="fas fa-times"></i>
                    <span>Batal</span>
                </a>
                
                <button type="submit" class="w-full sm:w-auto btn-teal px-6 py-3 shadow-lg shadow-teal-500/20 hover:shadow-teal-500/40 transition-all duration-300 flex items-center justify-center gap-2">
                    <i class="fas fa-save"></i>
                    <span>Simpan Material Masuk</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
.autocomplete-container {
    position: relative;
}

.autocomplete-results {
    position: absolute;
    width: max-content;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #ddd;
    border-top: none;
    max-height: 400px;
    overflow-y: auto;
    z-index: 9999 !important;
    display: none;
}

.autocomplete-item {
    padding: 10px;
    cursor: pointer;
    border-bottom: 1px solid #eee;
}

.autocomplete-item:hover {
    background-color: #f8f9fa;
}

.autocomplete-item:last-child {
    border-bottom: none;
}

.table-responsive {
    overflow: visible !important;
}

.table-responsive table {
    overflow: visible !important;
}
</style>
@endpush

@push('scripts')
<script>
let rowIndex = 1;

function addRow() {
    const tbody = document.getElementById('materialTableBody');
    const newRow = document.createElement('tr');
    
    newRow.innerHTML = `
        <td class="px-4 py-3 text-center text-gray-600">${rowIndex + 1}</td>
        <td class="px-4 py-3">
            <div class="autocomplete-container">
                <input type="text" class="form-input text-sm material-search" 
                       name="materials[${rowIndex}][material_description]" 
                       placeholder="Ketik untuk mencari material..." 
                       autocomplete="off" required>
                <input type="hidden" name="materials[${rowIndex}][material_id]" class="material-id">
                <input type="hidden" name="materials[${rowIndex}][material_name]" class="material-name">
                <div class="autocomplete-results"></div>
            </div>
        </td>
        <td class="px-4 py-3">
            <div class="autocomplete-container">
                <input type="text" class="form-input text-sm normalisasi-search" 
                       name="materials[${rowIndex}][normalisasi]" placeholder="Normalisasi">
                <div class="autocomplete-results"></div>
            </div>
        </td>
        <td class="px-4 py-3">
            <input type="number" class="form-input text-sm" 
                   name="materials[${rowIndex}][quantity]" placeholder="Qty" min="1" required>
        </td>
        <td class="px-4 py-3">
            <input type="text" class="form-input text-sm" 
                   name="materials[${rowIndex}][satuan]" placeholder="Satuan" required>
        </td>
        <td class="px-4 py-3 text-center">
            <button type="button" class="p-2 rounded-lg text-red-500 hover:bg-red-50 transition-colors remove-row" 
                    onclick="removeRow(this)">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    
    tbody.appendChild(newRow);
    rowIndex++;
    
    // Update row numbers and enable/disable remove buttons
    updateRowNumbers();
    initializeAutocomplete(newRow.querySelector('.material-search'));
    initializeNormalisasiAutocomplete(newRow.querySelector('.normalisasi-search'));
}

function removeRow(button) {
    const row = button.closest('tr');
    row.remove();
    updateRowNumbers();
}

function updateRowNumbers() {
    const rows = document.querySelectorAll('#materialTableBody tr');
    rows.forEach((row, index) => {
        row.querySelector('td:first-child').textContent = index + 1;
        
        // Update name attributes
        const inputs = row.querySelectorAll('input');
        inputs.forEach(input => {
            if (input.name) {
                input.name = input.name.replace(/\[\d+\]/, `[${index}]`);
            }
        });
        
        // Enable/disable remove button
        const removeBtn = row.querySelector('.remove-row');
        removeBtn.disabled = rows.length === 1;
    });
}

function initializeAutocomplete(input) {
    let timeout;
    
    input.addEventListener('input', function() {
        clearTimeout(timeout);
        const query = this.value;
        const resultsDiv = this.parentElement.querySelector('.autocomplete-results');
        const hiddenInput = this.parentElement.querySelector('.material-id');
        
        if (query.length < 2) {
            resultsDiv.style.display = 'none';
            hiddenInput.value = '';
            return;
        }
        
        timeout = setTimeout(() => {
            fetch(`{{ route('material-masuk.autocomplete.material') }}?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    resultsDiv.innerHTML = '';
                    
                    if (data.length > 0) {
                        data.forEach(material => {
                            const item = document.createElement('div');
                            item.className = 'autocomplete-item';
                            item.innerHTML = `
                                <strong>${material.text}</strong><br>
                                <small class="text-muted">
                                    Normalisasi: ${material.normalisasi || 'N/A'} | 
                                    Satuan: ${material.satuan || ''}
                                </small>
                            `;
                            
                            item.addEventListener('click', () => {
                                input.value = material.text;
                                hiddenInput.value = material.id;
                                const nameInput = input.closest('tr').querySelector('.material-name');
if (nameInput) nameInput.value = material.text;

                                
                                // Auto-fill normalisasi
                                const normalisasiInput = input.closest('tr').querySelector('input[name*="[normalisasi]"]');
                                if (normalisasiInput && material.normalisasi) {
                                    normalisasiInput.value = material.normalisasi;
                                }
                                
                                // Auto-fill satuan if available
                                const satuanInput = input.closest('tr').querySelector('input[name*="[satuan]"]');
                                if (satuanInput && material.satuan) {
                                    satuanInput.value = material.satuan;
                                }
                                
                                resultsDiv.style.display = 'none';
                            });
                            
                            resultsDiv.appendChild(item);
                        });
                        resultsDiv.style.display = 'block';
                    } else {
                        resultsDiv.innerHTML = '<div class="autocomplete-item">Tidak ada material ditemukan</div>';
                        resultsDiv.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    resultsDiv.style.display = 'none';
                });
        }, 300);
    });
    
    // Hide results when clicking outside
    document.addEventListener('click', function(e) {
        if (!input.parentElement.contains(e.target)) {
            input.parentElement.querySelector('.autocomplete-results').style.display = 'none';
        }
    });
}

function initializeNormalisasiAutocomplete(input) {
    let timeout;
    
    input.addEventListener('input', function() {
        clearTimeout(timeout);
        const query = this.value;
        const resultsDiv = this.parentElement.querySelector('.autocomplete-results');
        
        if (query.length < 2) {
            resultsDiv.style.display = 'none';
            return;
        }
        
        timeout = setTimeout(() => {
            fetch(`{{ route('material-masuk.autocomplete.normalisasi') }}?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    resultsDiv.innerHTML = '';
                    
                    if (data.length > 0) {
                        data.forEach(material => {
                            const item = document.createElement('div');
                            item.className = 'autocomplete-item';
                            item.innerHTML = `
                                <strong>${material.text}</strong><br>
                                <small class="text-muted">
                                    Material: ${material.material_description || 'N/A'} | 
                                    Satuan: ${material.satuan || ''}
                                </small>
                            `;
                            
                            item.addEventListener('click', () => {
                                input.value = material.text;
                                
                                // Auto-fill material description
                                const materialInput = input.closest('tr').querySelector('input[name*="[material_description]"]');
                                const hiddenInput = input.closest('tr').querySelector('.material-id');
                                if (materialInput && material.material_description) {
                                    materialInput.value = material.material_description;
                                    hiddenInput.value = material.id;
                                }
                                
                                // Auto-fill satuan if available
                                const satuanInput = input.closest('tr').querySelector('input[name*="[satuan]"]');
                                if (satuanInput && material.satuan) {
                                    satuanInput.value = material.satuan;
                                }
                                
                                resultsDiv.style.display = 'none';
                            });
                            
                            resultsDiv.appendChild(item);
                        });
                        resultsDiv.style.display = 'block';
                    } else {
                        resultsDiv.innerHTML = '<div class="autocomplete-item">Tidak ada normalisasi ditemukan</div>';
                        resultsDiv.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    resultsDiv.style.display = 'none';
                });
        }, 300);
    });
    
    // Hide results when clicking outside
    document.addEventListener('click', function(e) {
        if (!input.parentElement.contains(e.target)) {
            input.parentElement.querySelector('.autocomplete-results').style.display = 'none';
        }
    });
}

// Initialize autocomplete for existing inputs
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.material-search').forEach(input => {
        initializeAutocomplete(input);
    });
    
    document.querySelectorAll('.normalisasi-search').forEach(input => {
        initializeNormalisasiAutocomplete(input);
    });
    
    updateRowNumbers();
});

// Form validation
document.getElementById('materialMasukForm').addEventListener('submit', function(e) {
    const materialInputs = document.querySelectorAll('.material-id');
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