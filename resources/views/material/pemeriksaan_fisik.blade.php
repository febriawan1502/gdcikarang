@extends('layouts.app')

@section('title', 'Pemeriksaan Fisik')

@section('content')

<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">📋 Pemeriksaan Fisik</h2>
            <p class="text-gray-500 text-sm mt-1">Bandingkan data SAP, stok fisik, dan SN MIMS.</p>
        </div>
    </div>

    <!-- Card Form Pilih Bulan -->
    <div class="card p-6 border border-gray-100 shadow-xl shadow-gray-200/50">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-teal-400 to-teal-500 flex items-center justify-center text-white">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-800">Pilih Bulan Pemeriksaan</h3>
        </div>

        <form action="{{ route('material.pemeriksaanFisik') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-end">
            <div class="flex-1 max-w-xs">
                <label for="bulan" class="form-label">Bulan</label>
                <input type="month" name="bulan" value="{{ $bulan ?? '' }}"
                       class="form-input" required>
            </div>
            <button class="btn-teal px-6 py-3 shadow-lg shadow-teal-500/20 hover:shadow-teal-500/40 transition-all duration-300 flex items-center gap-2">
                <i class="fas fa-search"></i>
                <span>Lanjut</span>
            </button>
        </form>
    </div>

@if($materials)
    <!-- Data Card -->
    <div class="card p-6 border border-gray-100 shadow-xl shadow-gray-200/50">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-400 to-purple-500 flex items-center justify-center text-white">
                    <i class="fas fa-boxes"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Pemeriksaan Fisik Material</h3>
                    <p class="text-gray-500 text-sm">Periode: {{ $bulan }}</p>
                </div>
            </div>

            <div class="flex flex-wrap gap-2">
                <button onclick="openModal('importSapModal')" class="px-3 py-2 rounded-lg bg-blue-500 text-white hover:bg-blue-600 transition-colors text-sm flex items-center gap-2">
                    <i class="fas fa-upload"></i>
                    <span>Import SAP</span>
                </button>

                <button onclick="openModal('importMimsModal')" class="px-3 py-2 rounded-lg bg-yellow-500 text-white hover:bg-yellow-600 transition-colors text-sm flex items-center gap-2">
                    <i class="fas fa-upload"></i>
                    <span>Import SN MIMS</span>
                </button>

                <a href="{{ route('material.pemeriksaanFisikPdf', ['bulan'=>$bulan]) }}"
                   class="px-3 py-2 rounded-lg bg-red-500 text-white hover:bg-red-600 transition-colors text-sm flex items-center gap-2"
                   target="_blank">
                    <i class="fas fa-file-pdf"></i>
                    <span>Print PDF</span>
                </a>
            </div>
        </div>

        <!-- Form Simpan -->
        <form action="{{ route('material.pemeriksaanFisik.store') }}" method="POST">
            @csrf
            <input type="hidden" name="bulan" value="{{ $bulan }}">  
            <div class="overflow-x-auto rounded-xl border border-gray-100">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50">
                            <th rowspan="2" class="px-3 py-2 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-r border-gray-200">NO</th>
                            <th rowspan="2" class="px-3 py-2 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-r border-gray-200">No Normalisasi</th>
                            <th rowspan="2" class="px-3 py-2 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-r border-gray-200">Nama Barang</th>
                            <th rowspan="2" class="px-3 py-2 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-r border-gray-200">Satuan</th>
                            <th rowspan="2" class="px-3 py-2 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-r border-gray-200">Valuation</th>
                            <th colspan="3" class="px-3 py-2 text-xs font-bold text-teal-600 uppercase tracking-wider border-b border-r border-gray-200 bg-teal-50">SALDO / JUMLAH</th>
                            <th colspan="3" class="px-3 py-2 text-xs font-bold text-orange-600 uppercase tracking-wider border-b border-r border-gray-200 bg-orange-50">PERBEDAAN / SELISIH</th>
                            <th colspan="3" class="px-3 py-2 text-xs font-bold text-purple-600 uppercase tracking-wider border-b border-gray-200 bg-purple-50">JUSTIFIKASI</th>
                        </tr>
                        <tr class="bg-gray-50">
                            <th class="px-3 py-2 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-r border-gray-200 bg-teal-50">SAP</th>
                            <th class="px-3 py-2 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-r border-gray-200 bg-teal-50">Stok Fisik</th>
                            <th class="px-3 py-2 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-r border-gray-200 bg-teal-50">SN MIMS</th>

                            <th class="px-3 py-2 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-r border-gray-200 bg-orange-50">SAP–Fisik</th>
                            <th class="px-3 py-2 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-r border-gray-200 bg-orange-50">SAP–SN</th>
                            <th class="px-3 py-2 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-r border-gray-200 bg-orange-50">Fisik–SN</th>

                            <th class="px-3 py-2 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-r border-gray-200 bg-purple-50">SAP–Fisik</th>
                            <th class="px-3 py-2 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-r border-gray-200 bg-purple-50">SAP–SN</th>
                            <th class="px-3 py-2 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200 bg-purple-50">Fisik–SN</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 bg-white">
                    @foreach($materials as $i => $m)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-3 py-2 text-center text-gray-600 border-r border-gray-100">{{ $i+1 }}</td>
                            <td class="px-3 py-2 text-gray-800 border-r border-gray-100">{{ $m->material_code }}</td>
                            <td class="px-3 py-2 text-gray-800 border-r border-gray-100">{{ $m->material_description }}</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-r border-gray-100">{{ $m->base_unit_of_measure }}</td>
                            <td class="px-3 py-2 text-center text-gray-600 border-r border-gray-100">{{ $m->valuation_type }}</td>

                            <input type="hidden" name="data[{{ $i }}][material_id]" value="{{ $m->id }}">

                            <!-- SAP -->
                            <td class="px-2 py-1 border-r border-gray-100 bg-teal-50/50">
                                <input type="number"
                                       name="data[{{ $i }}][sap]"
                                       id="sap_{{ $i }}"
                                       value="{{ $m->sap }}"
                                       class="form-input text-sm py-1 text-center"
                                       oninput="hitung({{ $i }})">
                            </td>

                            <!-- FISIK -->
                            <td class="px-3 py-2 text-center font-semibold text-teal-700 border-r border-gray-100 bg-teal-50/50">
                                {{ $m->fisik ?? $m->unrestricted_use_stock ?? 0 }}
                                <input type="hidden"
                                       name="data[{{ $i }}][fisik]"
                                       id="fisik_val_{{ $i }}"
                                       value="{{ $m->fisik ?? $m->unrestricted_use_stock ?? 0 }}">
                            </td>

                            <!-- SN -->
                            <td class="px-2 py-1 border-r border-gray-100 bg-teal-50/50">
                                <input type="number"
                                       name="data[{{ $i }}][sn]"
                                       id="sn_{{ $i }}"
                                       value="{{ $m->sn_mims }}"
                                       class="form-input text-sm py-1 text-center"
                                       oninput="hitung({{ $i }})">
                            </td>

                            <!-- SELISIH -->
                            <td class="px-2 py-1 border-r border-gray-100 bg-orange-50/50">
                                <input class="form-input text-sm py-1 text-center bg-gray-50" id="sf_{{ $i }}" name="data[{{ $i }}][selisih_sf]" value="{{ $m->selisih_sf }}" readonly>
                            </td>
                            <td class="px-2 py-1 border-r border-gray-100 bg-orange-50/50">
                                <input class="form-input text-sm py-1 text-center bg-gray-50" id="ss_{{ $i }}" name="data[{{ $i }}][selisih_ss]" value="{{ $m->selisih_ss }}" readonly>
                            </td>
                            <td class="px-2 py-1 border-r border-gray-100 bg-orange-50/50">
                                <input class="form-input text-sm py-1 text-center bg-gray-50" id="fs_{{ $i }}" name="data[{{ $i }}][selisih_fs]" value="{{ $m->selisih_fs }}" readonly>
                            </td>

                            <!-- JUSTIFIKASI -->
                            <td class="px-2 py-1 border-r border-gray-100 bg-purple-50/50">
                                <input class="form-input text-sm py-1" name="data[{{ $i }}][justifikasi_sf]" value="{{ $m->justifikasi_sf }}">
                            </td>
                            <td class="px-2 py-1 border-r border-gray-100 bg-purple-50/50">
                                <input class="form-input text-sm py-1" name="data[{{ $i }}][justifikasi_ss]" value="{{ $m->justifikasi_ss }}">
                            </td>
                            <td class="px-2 py-1 bg-purple-50/50">
                                <input class="form-input text-sm py-1" name="data[{{ $i }}][justifikasi_fs]" value="{{ $m->justifikasi_fs }}">
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="flex justify-end mt-6">
                <button class="btn-teal px-6 py-3 shadow-lg shadow-teal-500/20 hover:shadow-teal-500/40 transition-all duration-300 flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    <span>Simpan</span>
                </button>
            </div>
        </form>
    </div>
@endif
</div>

<!-- Modal Import SAP -->
<div id="importSapModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeModal('importSapModal')"></div>
        
        <div class="inline-block bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full relative">
            <form action="{{ route('material.importSap') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="bulan" value="{{ $bulan ?? '' }}">

                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                            <i class="fas fa-upload"></i>
                            Import SAP
                        </h3>
                        <button type="button" class="text-white hover:text-gray-200 transition-colors" onclick="closeModal('importSapModal')">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>

                <div class="p-6">
                    <label class="form-label">File Excel</label>
                    <input type="file" name="file" class="form-input" required>
                    <p class="text-sm text-gray-500 mt-2">Format: no_part | sap</p>
                </div>

                <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                    <button type="button" class="px-4 py-2 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-100 transition-colors" onclick="closeModal('importSapModal')">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-blue-500 text-white hover:bg-blue-600 transition-colors">
                        Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Import MIMS -->
<div id="importMimsModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeModal('importMimsModal')"></div>
        
        <div class="inline-block bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full relative">
            <form action="{{ route('material.importMims') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="bulan" value="{{ $bulan ?? '' }}">

                <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                            <i class="fas fa-upload"></i>
                            Import SN MIMS
                        </h3>
                        <button type="button" class="text-white hover:text-gray-200 transition-colors" onclick="closeModal('importMimsModal')">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>

                <div class="p-6">
                    <label class="form-label">File Excel</label>
                    <input type="file" name="file" class="form-input" required>
                    <p class="text-sm text-gray-500 mt-2">Format: no_part | sn_mims</p>
                </div>

                <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                    <button type="button" class="px-4 py-2 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-100 transition-colors" onclick="closeModal('importMimsModal')">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-yellow-500 text-white hover:bg-yellow-600 transition-colors">
                        Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Modal functions
function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
    document.body.style.overflow = '';
}

// Calculation function
function hitung(i) {
    const sapVal   = document.getElementById(`sap_${i}`).value;
    const fisikVal = document.getElementById(`fisik_val_${i}`).value;
    const snVal    = document.getElementById(`sn_${i}`).value;

    const sap   = sapVal !== '' ? parseFloat(sapVal) : null;
    const fisik = fisikVal !== '' ? parseFloat(fisikVal) : null;
    const sn    = snVal !== '' ? parseFloat(snVal) : null;

    document.getElementById(`sf_${i}`).value =
        sap !== null && fisik !== null ? sap - fisik : '';

    document.getElementById(`ss_${i}`).value =
        sap !== null && sn !== null ? sap - sn : '';

    document.getElementById(`fs_${i}`).value =
        fisik !== null && sn !== null ? fisik - sn : '';
}

document.addEventListener('DOMContentLoaded', function () {
    @if($materials)
        const totalRows = {{ $materials->count() }};
        for (let i = 0; i < totalRows; i++) {
            hitung(i);
        }
    @endif
});
</script>
@endpush
