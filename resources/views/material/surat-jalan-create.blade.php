@extends('layouts.app')

@section('title', 'Buat Surat Jalan')

@section('page-title', 'Buat Surat Jalan')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Buat Surat Jalan</h2>
                <p class="text-gray-500 text-sm mt-1">Isi form di bawah untuk membuat surat jalan baru.</p>
            </div>
            <a href="{{ route('surat-jalan.index') }}"
                class="flex items-center gap-2 text-gray-600 hover:text-gray-800 transition-colors">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>

        <!-- Form Container -->
        <div class="card border border-gray-100 shadow-xl shadow-gray-200/50">
            <form action="{{ route('surat-jalan.store') }}" method="POST" id="suratJalanForm">
                @csrf

                <!-- Section: Informasi Surat Jalan -->
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-center gap-3 mb-6">
                        <div
                            class="w-10 h-10 rounded-xl bg-gradient-to-br from-teal-400 to-teal-500 flex items-center justify-center text-white">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Informasi Surat Jalan</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nomor_surat" class="form-label">
                                Nomor Surat Jalan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" class="form-input bg-gray-50" id="nomor_surat" name="nomor_surat"
                                value="{{ $nomorSurat }}" readonly>
                        </div>

                        <div>
                            <label for="jenis_surat_jalan" class="form-label">
                                Jenis Surat Jalan <span class="text-red-500">*</span>
                            </label>
                            <select class="form-input" id="jenis_surat_jalan" name="jenis_surat_jalan" required>
                                <option value="">Pilih Jenis Surat Jalan</option>
                                <option value="Normal" selected>Normal</option>
                                <option value="Garansi">Garansi</option>
                                <option value="Peminjaman">Peminjaman</option>
                                <option value="Perbaikan">Perbaikan</option>
                                <option value="Manual">Manual</option>
                            </select>
                        </div>

                        <div>
                            <label for="tanggal" class="form-label">
                                Tanggal Surat Jalan <span class="text-red-500">*</span>
                            </label>
                            <input type="date" class="form-input" id="tanggal" name="tanggal"
                                value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                </div>

                <!-- Section: Informasi Penerima -->
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-center gap-3 mb-6">
                        <div
                            class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-400 to-blue-500 flex items-center justify-center text-white">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Informasi Penerima</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="kepada" class="form-label">
                                Diberikan Kepada <span class="text-red-500">*</span>
                            </label>
                            <input type="text" class="form-input" id="kepada" name="kepada"
                                placeholder="Vendor / Unit PLN" required>
                        </div>

                        <div>
                            <label for="berdasarkan" class="form-label">
                                Berdasarkan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" class="form-input" id="berdasarkan" name="berdasarkan"
                                placeholder="Reservasi/Permintaan" required>
                        </div>

                        <div>
                            <label for="keterangan" class="form-label">
                                Untuk Pekerjaan
                            </label>
                            <input type="text" class="form-input" id="keterangan" name="keterangan"
                                placeholder="Pekerjaan / PB PD Rutin / STO">
                        </div>

                        <div>
                            <label for="nomor_slip" class="form-label">
                                Nomor Slip
                            </label>
                            <input type="text" class="form-input" id="nomor_slip" name="nomor_slip"
                                placeholder="No SAP : TUG8 / TUG9">
                        </div>

                        <!-- Foto Penerima -->
                        <div class="md:col-span-2">
                            <label class="form-label">
                                <i class="fas fa-camera mr-1"></i> Foto Penerima
                            </label>
                            <div class="flex flex-wrap items-start gap-4">
                                <div class="flex gap-2">
                                    <button type="button"
                                        class="px-4 py-2 rounded-lg border border-teal-500 text-teal-600 hover:bg-teal-50 transition-colors"
                                        onclick="openCameraModal()">
                                        <i class="fas fa-camera mr-1"></i> Ambil Gambar
                                    </button>
                                </div>
                                <div id="photoPreview" class="hidden">
                                    <div class="relative inline-block">
                                        <img id="thumbnailImg" src="" alt="Preview"
                                            class="max-w-[150px] max-h-[150px] rounded-lg border-2 border-gray-200">
                                        <button type="button"
                                            class="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-red-500 text-white text-xs flex items-center justify-center hover:bg-red-600"
                                            onclick="removePhoto()">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <input type="hidden" name="foto_penerima" id="fotoBase64">
                                </div>
                            </div>
                            <p class="text-gray-400 text-xs mt-2">Foto akan dikompresi ke resolusi 720p</p>
                        </div>
                    </div>
                </div>

                <!-- Section: Daftar Material -->
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-center gap-3 mb-6">
                        <div
                            class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-400 to-purple-500 flex items-center justify-center text-white">
                            <i class="fas fa-boxes"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Daftar Material</h3>
                    </div>

                    <div id="manualNotice"
                        class="hidden mb-4 p-4 bg-blue-50 border border-blue-200 rounded-xl text-blue-700 text-sm">
                        <i class="fas fa-info-circle mr-1"></i>
                        Mode <strong>Manual / Peminjaman</strong> aktif. Isi nama barang secara bebas tanpa memilih dari
                        daftar material.
                    </div>

                    <div class="rounded-xl border border-gray-100">
                        <table class="table-purity w-full" id="materialTable">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider w-12">No
                                    </th>
                                    <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Material
                                    </th>
                                    <th
                                        class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider w-[140px] col-stock">
                                        Stock</th>
                                    <th
                                        class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider w-[140px]">
                                        Qty</th>
                                    <th
                                        class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider w-[100px]">
                                        Satuan</th>
                                    <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Keterangan</th>
                                    <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider w-16">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white"></tbody>
                        </table>
                    </div>

                    <div class="mt-4 flex justify-end">
                        <button type="button"
                            class="px-4 py-2 rounded-lg bg-green-500 text-white hover:bg-green-600 transition-colors flex items-center gap-2"
                            onclick="addRow()">
                            <i class="fas fa-plus"></i>
                            <span>Tambah Material</span>
                        </button>
                    </div>
                </div>

                <!-- Section: Informasi Kendaraan -->
                <div class="p-6 border-b border-gray-100">
                    <div class="flex items-center gap-3 mb-6">
                        <div
                            class="w-10 h-10 rounded-xl bg-gradient-to-br from-orange-400 to-orange-500 flex items-center justify-center text-white">
                            <i class="fas fa-truck"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Informasi Kendaraan</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div>
                            <label for="kendaraan" class="form-label">Kendaraan</label>
                            <input type="text" class="form-input" id="kendaraan" name="kendaraan"
                                placeholder="Jenis/Merk kendaraan">
                        </div>

                        <div>
                            <label for="no_polisi" class="form-label">No. Polisi</label>
                            <input type="text" class="form-input" id="no_polisi" name="no_polisi"
                                placeholder="Nomor polisi kendaraan">
                        </div>

                        <div>
                            <label for="pengemudi" class="form-label">Pengemudi</label>
                            <input type="text" class="form-input" id="pengemudi" name="pengemudi"
                                placeholder="Nama pengemudi">
                        </div>

                        <div>
                            <label for="security" class="form-label">Security</label>
                            <input type="text" class="form-input" id="security" name="security"
                                placeholder="Nama security">
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="p-6 bg-gray-50 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <a href="{{ route('surat-jalan.index') }}"
                        class="w-full sm:w-auto px-6 py-3 rounded-xl border border-gray-300 text-gray-600 hover:bg-gray-100 transition-colors flex items-center justify-center gap-2">
                        <i class="fas fa-arrow-left"></i>
                        <span>Kembali</span>
                    </a>

                    <div class="flex gap-3 w-full sm:w-auto">
                        <button type="reset"
                            class="flex-1 sm:flex-none px-6 py-3 rounded-xl border border-yellow-400 text-yellow-600 hover:bg-yellow-50 transition-colors flex items-center justify-center gap-2">
                            <i class="fas fa-undo"></i>
                            <span>Reset</span>
                        </button>

                        <button type="submit"
                            class="flex-1 sm:flex-none btn-teal px-6 py-3 shadow-lg shadow-teal-500/20 hover:shadow-teal-500/40 transition-all duration-300 flex items-center justify-center gap-2">
                            <i class="fas fa-save"></i>
                            <span>Simpan Surat Jalan</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Camera Modal (Tailwind) -->
    <div id="cameraModal" class="fixed inset-0 z-[9999] hidden overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity"
                style="background-color: rgba(17, 24, 39, 0.6); backdrop-filter: blur(4px);" aria-hidden="true"
                onclick="closeCameraModal()"></div>

            <!-- Modal panel -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="relative z-10 inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-gray-100">
                <!-- Header -->
                <div class="bg-gradient-to-r from-teal-500 to-teal-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                            <i class="fas fa-camera"></i>
                            Ambil Foto Penerima
                        </h3>
                        <button type="button" class="text-white hover:text-gray-200 transition-colors"
                            onclick="closeCameraModal()">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Body -->
                <div class="p-6 text-center">
                    <div id="cameraContainer" class="mb-4">
                        <video id="cameraVideo" autoplay playsinline
                            class="w-full max-w-lg mx-auto rounded-xl border border-gray-200"></video>
                    </div>
                    <canvas id="cameraCanvas" style="display: none;"></canvas>
                    <div class="flex justify-center gap-3 mt-4">
                        <button type="button"
                            class="px-6 py-3 rounded-xl bg-green-500 text-white hover:bg-green-600 transition-colors flex items-center gap-2"
                            onclick="capturePhoto()">
                            <i class="fas fa-camera"></i>
                            <span>Capture</span>
                        </button>
                        <button type="button"
                            class="px-6 py-3 rounded-xl border border-gray-300 text-gray-600 hover:bg-gray-50 transition-colors flex items-center gap-2"
                            onclick="switchCamera()">
                            <i class="fas fa-sync-alt"></i>
                            <span>Ganti Kamera</span>
                        </button>
                    </div>
                </div>

                <!-- Footer -->
                <div class="bg-gray-50 px-6 py-4 flex justify-end">
                    <button type="button"
                        class="px-6 py-2 rounded-xl border border-gray-300 text-gray-600 hover:bg-gray-100 transition-colors"
                        onclick="closeCameraModal()">
                        Tutup
                    </button>
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

        .autocomplete-container {
            position: relative;
        }

        .autocomplete-results {
            position: absolute;
            width: max-content;
            min-width: 100%;
            top: 100%;
            left: 0;
            background: white;
            border: 1px solid #ddd;
            border-top: none;
            max-height: 400px;
            overflow-y: auto;
            z-index: 9999 !important;
            display: none;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border-radius: 0 0 0.5rem 0.5rem;
        }

        .autocomplete-item {
            padding: 10px;
            cursor: pointer;
            border-bottom: 1px solid #eee;
            font-size: 0.875rem;
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
                jenisSuratJalanSelect.addEventListener('change', function() {
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
        <td class="px-4 py-3 text-center text-gray-600">${index + 1}</td>

        <td class="px-4 py-3">
            <input type="text" class="form-input text-sm"
                   name="materials[${index}][nama_barang]" required placeholder="Nama Barang">
        </td>

        <td class="col-stock px-4 py-3" style="display:none;"></td>

        <td class="px-4 py-3">
            <input type="number" class="form-input text-sm"
                   name="materials[${index}][quantity]" min="1" required placeholder="Qty">
        </td>

        <td class="px-4 py-3">
            <input type="text" class="form-input text-sm"
                   name="materials[${index}][satuan]" required placeholder="Satuan">
        </td>

        <td class="px-4 py-3">
            <input type="text" class="form-input text-sm"
                   name="materials[${index}][keterangan]" placeholder="Keterangan">
        </td>

        <td class="px-4 py-3 text-center">
            <button type="button" class="p-2 rounded-lg text-red-500 hover:bg-red-50 transition-colors"
                    onclick="removeRow(this)">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
            } else {
                newRow.innerHTML = `
            <td class="px-4 py-3 text-center text-gray-600">${index + 1}</td>
            <td class="px-4 py-3" style="position:relative;">
                <div class="autocomplete-container">
                    <input type="text" class="form-input text-sm material-autocomplete"
                           name="materials[${index}][material_search]" autocomplete="off" required placeholder="Cari Material...">
                    <input type="hidden" name="materials[${index}][material_id]" class="material-id">
                    <div class="autocomplete-results"></div>
                </div>
            </td>
            <td class="col-stock px-4 py-3" style="${isNonStock ? 'display:none;' : ''}">
                <input type="number" class="form-input text-sm bg-gray-50"
                       name="materials[${index}][stock]" readonly disabled placeholder="Stock">
            </td>

            <td class="px-4 py-3">
                <input type="number" class="form-input text-sm"
                       name="materials[${index}][quantity]" min="1" required placeholder="Qty">
            </td>
            <td class="px-4 py-3">
                <input type="text" class="form-input text-sm ${jenis === 'Normal' ? 'bg-gray-50' : ''}"
                       name="materials[${index}][satuan]"
                       ${jenis === 'Normal' ? 'readonly' : ''} placeholder="Satuan">
            </td>

            <td class="px-4 py-3">
                <input type="text" class="form-input text-sm"
                       name="materials[${index}][keterangan]" placeholder="Keterangan">
            </td>
            <td class="px-4 py-3 text-center">
                <button type="button" class="p-2 rounded-lg text-red-500 hover:bg-red-50 transition-colors"
                        onclick="removeRow(this)">
                    <i class="fas fa-trash"></i>
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
            addRow(); // buat row baru sesuai jenis
            toggleManualMode(); // rapikan tampilan
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
                                    item.className =
                                        'autocomplete-item p-2 cursor-pointer hover:bg-gray-100';
                                    item.style.cursor = 'pointer';
                                    item.style.padding = '8px';
                                    item.style.borderBottom = '1px solid #eee';

                                    const jenis = document.getElementById('jenis_surat_jalan')
                                        .value;
                                    const showStockInfo = (jenis === 'Normal');

                                    const stockInfo = showStockInfo ?
                                        `<small class="text-info d-block">
                                    Stock: ${material.unrestricted_use_stock || 0} ${material.base_unit_of_measure || ''}
                                </small>` :
                                        '';


                                    item.innerHTML = `
                                <div>[${material.material_code} - ${material.material_description}]</div>
                                ${stockInfo}
                            `;

                                    item.addEventListener('click', function() {
                                        const row = input.closest('tr');
                                        const hiddenInput = row.querySelector(
                                            '.material-id');
                                        const satuanInput = row.querySelector(
                                            'input[name*="[satuan]"]');
                                        const stockInput = row.querySelector(
                                            'input[name*="[stock]"]');

                                        input.value =
                                            `[${material.material_code} - ${material.material_description}]`;
                                        hiddenInput.value = material.id;
                                        satuanInput.value = material
                                            .base_unit_of_measure || '';

                                        // ✅ HANYA NORMAL yang boleh isi stock
                                        const jenis = document.getElementById(
                                            'jenis_surat_jalan').value;
                                        if (jenis === 'Normal') {
                                            stockInput.value = material
                                                .unrestricted_use_stock || 0;
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
                    !['Garansi', 'Perbaikan', 'Manual', 'Peminjaman'].includes(jenis)
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
            notice.classList.toggle('hidden', !isManual);
        }


        // === Toggle mode Manual ===
        document.addEventListener('DOMContentLoaded', function() {
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
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent body scroll
            startCamera();
        }

        // Close camera modal
        function closeCameraModal() {
            stopCamera();
            const modal = document.getElementById('cameraModal');
            modal.classList.add('hidden');
            document.body.style.overflow = ''; // Restore body scroll
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
                    if (location.protocol !== 'https:' && location.hostname !== 'localhost' && location.hostname !==
                        '127.0.0.1') {
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
                        width: {
                            ideal: 1280
                        },
                        height: {
                            ideal: 720
                        }
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
            preview.classList.remove('hidden');
        }

        // Remove photo
        function removePhoto() {
            const preview = document.getElementById('photoPreview');
            const thumbnail = document.getElementById('thumbnailImg');
            const hiddenInput = document.getElementById('fotoBase64');

            thumbnail.src = '';
            hiddenInput.value = '';
            preview.classList.add('hidden');
        }
    </script>
@endpush
