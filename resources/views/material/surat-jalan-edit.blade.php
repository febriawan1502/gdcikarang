@extends('layouts.app')

@section('title', 'Edit Surat Jalan')

@section('page-title', 'Edit Surat Jalan')

@section('content')
<!-- <pre>{{ json_encode($suratJalan->details, JSON_PRETTY_PRINT) }}</pre> -->

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header with Back Button -->
    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('surat-jalan.index') }}" class="p-2 bg-white rounded-xl shadow-sm border border-gray-200 text-gray-600 hover:text-teal-600 hover:border-teal-200 transition-all">
                <i class="fas fa-arrow-left text-lg"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Edit Surat Jalan</h1>
                <p class="text-gray-500 text-sm">Perbarui data dan detail pengiriman material.</p>
            </div>
        </div>
        <div class="hidden md:block">
            <span class="px-4 py-2 rounded-lg bg-teal-50 text-teal-700 text-sm font-semibold border border-teal-100">
                {{ $suratJalan->nomor_surat }}
            </span>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <!-- Form Content -->
        <div class="p-8 md:p-10">
            <form action="{{ route('surat-jalan.update', $suratJalan->id) }}" method="POST" id="suratJalanForm" class="space-y-12">
                @csrf
                @method('PUT')
                
                <!-- Section 1: Informasi Dasar -->
                <div>
                    <h3 class="flex items-center text-lg font-bold text-gray-800 mb-6 pb-2 border-b border-gray-100">
                        <i class="fas fa-file-alt text-teal-600 mr-3 text-xl"></i>
                        Informasi Surat Jalan
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Nomor Surat (Readonly) -->
                        <div>
                            <label for="nomor_surat" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nomor Surat Jalan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="nomor_surat" name="nomor_surat" value="{{ $suratJalan->nomor_surat }}" readonly
                                class="w-full rounded-lg bg-gray-50 border-gray-300 text-gray-500 px-4 py-2.5 shadow-sm focus:border-teal-500 focus:ring-teal-500 cursor-not-allowed">
                        </div>

                         <!-- Jenis Surat -->
                         <div>
                            <label for="jenis_surat_jalan" class="block text-sm font-semibold text-gray-700 mb-2">
                                Jenis Surat Jalan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" value="{{ $suratJalan->jenis_surat_jalan }}" readonly
                                class="w-full rounded-lg bg-gray-50 border-gray-300 text-gray-500 px-4 py-2.5 shadow-sm focus:border-teal-500 focus:ring-teal-500 cursor-not-allowed">
                            <input type="hidden" name="jenis_surat_jalan" value="{{ $suratJalan->jenis_surat_jalan }}">
                        </div>

                         <!-- Tanggal -->
                         <div>
                            <label for="tanggal" class="block text-sm font-semibold text-gray-700 mb-2">
                                Tanggal <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="tanggal" name="tanggal" 
                                value="{{ old('tanggal', \Carbon\Carbon::parse($suratJalan->tanggal)->format('Y-m-d')) }}"
                                class="w-full rounded-lg border-gray-300 px-4 py-2.5 shadow-sm focus:border-teal-500 focus:ring-teal-500">
                        </div>
                    </div>
                </div>

                <!-- Section 2: Informasi Penerima -->
                <div>
                    <h3 class="flex items-center text-lg font-bold text-gray-800 mb-6 pb-2 border-b border-gray-100">
                        <i class="fas fa-user-check text-blue-600 mr-3 text-xl"></i>
                        Informasi Penerima & Tujuan
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Kepada -->
                        <div>
                            <label for="kepada" class="block text-sm font-semibold text-gray-700 mb-2">
                                Diberikan Kepada <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="kepada" name="kepada" value="{{ $suratJalan->kepada }}" required placeholder="Vendor / Unit PLN"
                                class="w-full rounded-lg border-gray-300 px-4 py-2.5 shadow-sm focus:border-teal-500 focus:ring-teal-500 placeholder-gray-400">
                        </div>

                         <!-- Berdasarkan -->
                         <div class="md:row-span-2">
                            <label for="berdasarkan" class="block text-sm font-semibold text-gray-700 mb-2">
                                Berdasarkan <span class="text-red-500">*</span>
                            </label>
                            <textarea id="berdasarkan" name="berdasarkan" rows="5" required placeholder="Dasar permintaan / referensi..."
                                class="w-full rounded-lg border-gray-300 px-4 py-2.5 shadow-sm focus:border-teal-500 focus:ring-teal-500 placeholder-gray-400">{{ $suratJalan->berdasarkan }}</textarea>
                        </div>

                        <!-- Keterangan -->
                        <div>
                            <label for="keterangan" class="block text-sm font-semibold text-gray-700 mb-2">
                                Untuk Pekerjaan
                            </label>
                            <input type="text" id="keterangan" name="keterangan" value="{{ $suratJalan->keterangan }}" placeholder="Deskripsi pekerjaan..."
                                class="w-full rounded-lg border-gray-300 px-4 py-2.5 shadow-sm focus:border-teal-500 focus:ring-teal-500 placeholder-gray-400">
                        </div>

                        <!-- Nomor Slip -->
                        <div>
                            <label for="nomor_slip" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nomor Slip SAP
                            </label>
                            <input type="text" id="nomor_slip" name="nomor_slip" value="{{ $suratJalan->nomor_slip }}" placeholder="Contoh: TUG8 / TUG9"
                                class="w-full rounded-lg border-gray-300 px-4 py-2.5 shadow-sm focus:border-teal-500 focus:ring-teal-500 placeholder-gray-400">
                        </div>
                    </div>
                </div>
                        
                <!-- Section 3: Daftar Material -->
                <div>
                    <div class="flex items-center justify-between mb-6 pb-3 border-b border-gray-100">
                        <h3 class="flex items-center text-lg font-bold text-gray-800">
                            <i class="fas fa-boxes text-indigo-600 mr-3 text-xl"></i>
                            Daftar Material
                        </h3>
                        <button type="button" onclick="addRow()" 
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 shadow-lg shadow-teal-500/30 transition-all">
                            <i class="fas fa-plus mr-2"></i> Tambah Material
                        </button>
                    </div>
                    
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200" id="materialTable">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-12">No</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Material / Barang</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-24">Stock</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-24">Qty</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-24">Satuan</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Keterangan</th>
                                        <th scope="col" class="px-4 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider w-16">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($suratJalan->details as $index => $detail)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-500 text-center">{{ $index + 1 }}</td>

                                        {{-- Kolom Material --}}
                                        <td class="px-4 py-3">
                                            @if(in_array($suratJalan->jenis_surat_jalan, ['Manual', 'Peminjaman']))
                                                @if($suratJalan->status === 'APPROVED')
                                                    <input type="text" value="{{ $detail->nama_barang_manual }}" readonly
                                                        class="block w-full rounded-md bg-gray-50 border-gray-300 text-gray-500 shadow-sm sm:text-sm">
                                                    <input type="hidden" name="materials[{{ $index }}][nama_barang]" value="{{ $detail->nama_barang_manual }}">
                                                @else
                                                    <input type="text" name="materials[{{ $index }}][nama_barang]" value="{{ $detail->nama_barang_manual }}" placeholder="Nama barang manual..." required
                                                        class="block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                                                @endif
                                            @else
                                                {{-- yang normal tetap pakai master/autocomplete --}}
                                                @if($suratJalan->status === 'APPROVED')
                                                    <input type="text" value="{{ $detail->material->material_code ?? '' }} - {{ $detail->material->material_description ?? '' }}" readonly
                                                        class="block w-full rounded-md bg-gray-50 border-gray-300 text-gray-500 shadow-sm sm:text-sm">
                                                    <input type="hidden" name="materials[{{ $index }}][material_id]" value="{{ $detail->material_id }}">
                                                    <input type="hidden" name="materials[{{ $index }}][material_search]" value="{{ $detail->material->material_code ?? '' }} - {{ $detail->material->material_description ?? '' }}">
                                                @else
                                                    <div class="relative">
                                                        <input type="text" name="materials[{{ $index }}][material_search]" 
                                                               value="{{ $detail->material->material_code ?? '' }} - {{ $detail->material->material_description ?? '' }}"
                                                               autocomplete="off" required
                                                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm material-autocomplete placeholder-gray-400">
                                                        
                                                        <input type="hidden" name="materials[{{ $index }}][material_id]" value="{{ $detail->material_id }}" class="material-id-input">
                                                        <div class="autocomplete-results absolute z-50 w-full bg-white border border-gray-200 rounded-md shadow-lg mt-1 max-h-60 overflow-y-auto hidden"></div>
                                                    </div>
                                                @endif
                                            @endif
                                        </td>

                                        {{-- Kolom Stock --}}
                                        <td class="px-4 py-3">
                                            @if(in_array($suratJalan->jenis_surat_jalan, ['Manual', 'Peminjaman']))
                                                <input type="text" value="-" readonly class="block w-full rounded-md bg-gray-50 border-gray-300 text-center text-gray-500 shadow-sm sm:text-sm">
                                            @else
                                                <input type="number" name="materials[{{ $index }}][stock]" value="{{ $detail->material->unrestricted_use_stock ?? 0 }}" readonly
                                                    class="block w-full rounded-md bg-gray-50 border-gray-300 text-center text-gray-500 shadow-sm sm:text-sm font-mono">
                                            @endif
                                        </td>

                                        {{-- Kolom Qty --}}
                                        <td class="px-4 py-3">
                                            <input type="number" name="materials[{{ $index }}][quantity]" value="{{ $detail->quantity }}" min="1" required
                                                {{ $suratJalan->status === 'APPROVED' ? 'readonly' : '' }}
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm text-center font-bold {{ $suratJalan->status === 'APPROVED' ? 'bg-gray-50 text-gray-500' : 'text-gray-900' }}">
                                        </td>

                                        {{-- Kolom Satuan --}}
                                        <td class="px-4 py-3">
                                            <input type="text" name="materials[{{ $index }}][satuan]" value="{{ $detail->satuan }}" readonly
                                                class="block w-full rounded-md bg-gray-50 border-gray-300 text-gray-500 shadow-sm sm:text-sm text-center">
                                        </td>

                                        {{-- Kolom Keterangan --}}
                                        <td class="px-4 py-3">
                                            <input type="text" name="materials[{{ $index }}][keterangan]" value="{{ $detail->keterangan }}" placeholder="Ket..."
                                                {{ $suratJalan->status === 'APPROVED' ? 'readonly' : '' }}
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm {{ $suratJalan->status === 'APPROVED' ? 'bg-gray-50 text-gray-500' : '' }}">
                                        </td>

                                        {{-- Kolom Aksi --}}
                                        <td class="px-4 py-3 text-center">
                                            @if($suratJalan->status !== 'APPROVED')
                                                <button type="button" onclick="removeRow(this)" class="text-red-500 hover:text-red-700 hover:bg-red-50 p-2 rounded-full transition-colors">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                        
                <!-- Section 4: Keterangan Tambahan -->
                <div>
                    <h3 class="flex items-center text-lg font-bold text-gray-800 mb-6 pb-2 border-b border-gray-100">
                        <i class="fas fa-sticky-note text-yellow-600 mr-3 text-xl"></i>
                        Keterangan Tambahan
                    </h3>
                    
                    <div>
                        <textarea id="keterangan" name="keterangan" rows="3" placeholder="Catatan tambahan (opsional)..."
                            class="w-full rounded-lg border-gray-300 px-4 py-3 shadow-sm focus:border-teal-500 focus:ring-teal-500 placeholder-gray-400">{{ $suratJalan->keterangan }}</textarea>
                    </div>
                </div>

                <!-- Section 5: Informasi Kendaraan -->
                <div>
                    <h3 class="flex items-center text-lg font-bold text-gray-800 mb-6 pb-2 border-b border-gray-100">
                        <i class="fas fa-truck text-slate-600 mr-3 text-xl"></i>
                        Informasi Kendaraan & Pengangkut
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Kendaraan -->
                        <div>
                            <label for="kendaraan" class="block text-sm font-medium text-gray-700 mb-1">Kendaraan</label>
                            <input type="text" id="kendaraan" name="kendaraan" value="{{ $suratJalan->kendaraan }}" placeholder="Jenis/Merk"
                                class="w-full rounded-lg border-gray-300 px-4 py-2.5 shadow-sm focus:border-teal-500 focus:ring-teal-500 placeholder-gray-400">
                        </div>

                         <!-- No Polisi -->
                         <div>
                            <label for="no_polisi" class="block text-sm font-medium text-gray-700 mb-1">No. Polisi</label>
                            <input type="text" id="no_polisi" name="no_polisi" value="{{ $suratJalan->no_polisi }}" placeholder="Plat Nomor"
                                class="w-full rounded-lg border-gray-300 px-4 py-2.5 shadow-sm focus:border-teal-500 focus:ring-teal-500 placeholder-gray-400 uppercase">
                        </div>

                        <!-- Pengemudi -->
                        <div>
                            <label for="pengemudi" class="block text-sm font-medium text-gray-700 mb-1">Pengemudi</label>
                            <input type="text" id="pengemudi" name="pengemudi" value="{{ $suratJalan->pengemudi }}" placeholder="Nama Supir"
                                class="w-full rounded-lg border-gray-300 px-4 py-2.5 shadow-sm focus:border-teal-500 focus:ring-teal-500 placeholder-gray-400">
                        </div>

                        <!-- Security -->
                        <div>
                            <label for="security" class="block text-sm font-medium text-gray-700 mb-1">Security</label>
                            <input type="text" id="security" name="security" value="{{ $suratJalan->security }}" placeholder="Petugas Jaga"
                                class="w-full rounded-lg border-gray-300 px-4 py-2.5 shadow-sm focus:border-teal-500 focus:ring-teal-500 placeholder-gray-400">
                        </div>
                    </div>
                </div>

                <!-- Section 6: Foto Dokumentasi -->
                @if($suratJalan->foto_penerima)
                <div>
                    <h3 class="flex items-center text-lg font-bold text-gray-800 mb-6 pb-2 border-b border-gray-100">
                        <i class="fas fa-camera text-purple-600 mr-3 text-xl"></i>
                        Foto Dokumentasi
                    </h3>
                    
                    <div class="bg-gray-50 rounded-xl border border-dashed border-gray-300 p-6 flex flex-col items-center justify-center">
                         <a href="{{ asset('storage/' . $suratJalan->foto_penerima) }}" target="_blank" class="group relative block overflow-hidden rounded-lg shadow-md hover:shadow-xl transition-all duration-300">
                            <img src="{{ asset('storage/' . $suratJalan->foto_penerima) }}" alt="Foto Penerima" class="max-w-full h-auto max-h-80 object-cover transform group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300 flex items-center justify-center">
                                <i class="fas fa-search-plus text-white opacity-0 group-hover:opacity-100 text-3xl transform scale-0 group-hover:scale-100 transition-all duration-300"></i>
                            </div>
                        </a>
                        <p class="text-gray-500 text-sm mt-3">
                            <i class="fas fa-info-circle mr-1"></i> Klik gambar untuk memperbesar
                        </p>
                    </div>
                </div>
                @endif

                <!-- Action Buttons -->
                <div class="mt-10 pt-6 border-t border-gray-100 flex flex-col-reverse md:flex-row md:items-center justify-between gap-4">
                    <a href="{{ route('surat-jalan.index') }}" class="w-full md:w-auto px-6 py-3 bg-white border border-gray-300 rounded-xl text-gray-700 font-medium hover:bg-gray-50 hover:text-gray-900 shadow-sm transition-all text-center">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>

                    <div class="flex flex-col md:flex-row gap-3 w-full md:w-auto">
                        <button type="reset" class="w-full md:w-auto px-6 py-3 bg-amber-50 text-amber-700 border border-amber-200 rounded-xl font-medium hover:bg-amber-100 transition-all shadow-sm">
                            <i class="fas fa-undo mr-2"></i> Reset
                        </button>

                        @if($suratJalan->status === 'BUTUH_PERSETUJUAN')
                        <button type="button" onclick="approveSuratJalan({{ $suratJalan->id }})" class="w-full md:w-auto px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl font-bold shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 hover:from-blue-700 hover:to-blue-800 transition-all transform hover:-translate-y-0.5">
                            <i class="fas fa-check-circle mr-2"></i> Approve Surat Jalan
                        </button>
                        @endif

                        <button type="submit" name="action" value="update" class="w-full md:w-auto px-6 py-3 bg-gradient-to-r from-teal-600 to-teal-700 text-white rounded-xl font-bold shadow-lg shadow-teal-500/30 hover:shadow-teal-500/50 hover:from-teal-700 hover:to-teal-800 transition-all transform hover:-translate-y-0.5">
                            <i class="fas fa-save mr-2"></i> Simpan Perubahan
                        </button>

                        @if($suratJalan->status === 'APPROVED')
                        <button type="submit" name="action" value="selesai" class="w-full md:w-auto px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-xl font-bold shadow-lg shadow-green-500/30 hover:shadow-green-500/50 hover:from-green-700 hover:to-green-800 transition-all transform hover:-translate-y-0.5">
                            <i class="fas fa-check-double mr-2"></i> Tandai Selesai
                        </button>
                        @endif
                    </div>
                </div>

            </form>
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
const JENIS_SURAT = "{{ $suratJalan->jenis_surat_jalan }}";
const STATUS_SURAT = "{{ $suratJalan->status }}";
// let rowCount = {{ count($suratJalan->details) }};

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
    const index = tbody.children.length;

    const isManual = ['Manual', 'Peminjaman'].includes(JENIS_SURAT);
    const isNonStock = ['Garansi', 'Perbaikan', 'Manual', 'Peminjaman'].includes(JENIS_SURAT);

    const tr = document.createElement('tr');

    if (isManual) {
        tr.innerHTML = `
            <td class="px-4 py-3 text-sm text-gray-500 text-center">${index + 1}</td>
            <td class="px-4 py-3">
                <input type="text" name="materials[${index}][nama_barang]"
                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm" required placeholder="Nama barang manual...">
            </td>
            <td class="px-4 py-3" style="display:none;">
                <input type="text" value="-" readonly class="block w-full rounded-md bg-gray-50 border-gray-300 text-center text-gray-500 shadow-sm sm:text-sm">
            </td>
            <td class="px-4 py-3">
                <input type="number" name="materials[${index}][quantity]"
                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm text-center font-bold text-gray-900" required min="1">
            </td>
            <td class="px-4 py-3">
                <input type="text" name="materials[${index}][satuan]"
                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm text-center" required>
            </td>
            <td class="px-4 py-3">
                <input type="text" name="materials[${index}][keterangan]" placeholder="Ket..."
                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
            </td>
            <td class="px-4 py-3 text-center">
                <button type="button" class="text-red-500 hover:text-red-700 hover:bg-red-50 p-2 rounded-full transition-colors"
                        onclick="removeRow(this)"><i class="fas fa-trash"></i></button>
            </td>
        `;
    } else {
        tr.innerHTML = `
            <td class="px-4 py-3 text-sm text-gray-500 text-center">${index + 1}</td>
            <td class="px-4 py-3">
                <div class="relative">
                    <input type="text" name="materials[${index}][material_search]"
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm material-autocomplete placeholder-gray-400"
                           autocomplete="off" required placeholder="Cari material...">
                    <input type="hidden" name="materials[${index}][material_id]"
                           class="material-id">
                    <div class="autocomplete-results absolute z-50 w-full bg-white border border-gray-200 rounded-md shadow-lg mt-1 max-h-60 overflow-y-auto hidden"></div>
                </div>
            </td>
            <td class="px-4 py-3" style="${isNonStock ? 'display:none;' : ''}">
                <input type="number" name="materials[${index}][stock]"
                       class="block w-full rounded-md bg-gray-50 border-gray-300 text-center text-gray-500 shadow-sm sm:text-sm font-mono" readonly>
            </td>
            <td class="px-4 py-3">
                <input type="number" name="materials[${index}][quantity]"
                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm text-center font-bold text-gray-900" required min="1">
            </td>
            <td class="px-4 py-3">
                <input type="text" name="materials[${index}][satuan]"
                       class="block w-full rounded-md bg-gray-50 border-gray-300 text-gray-500 shadow-sm sm:text-sm text-center" readonly>
            </td>
            <td class="px-4 py-3">
                <input type="text" name="materials[${index}][keterangan]" placeholder="Ket..."
                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
            </td>
            <td class="px-4 py-3 text-center">
                <button type="button" class="text-red-500 hover:text-red-700 hover:bg-red-50 p-2 rounded-full transition-colors"
                        onclick="removeRow(this)"><i class="fas fa-trash"></i></button>
            </td>
        `;
    }

    tbody.appendChild(tr);

    if (!isManual) {
        initializeAutocomplete(tr.querySelector('.material-autocomplete'));
    }
    toggleManualModeEdit();
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
// =========================
// 🔧 FIXED initializeAutocomplete()
// =========================
function initializeAutocomplete(input) {
    let timeout;
    const resultsDiv = input.parentElement.querySelector('.autocomplete-results'); // div hasil autocomplete
    const hiddenInput = input.closest('tr').querySelector('.material-id-input, .material-id');
    const satuanInput = input.closest('tr').querySelector('input[name*="[satuan]"]');
    const stockInput = input.closest('tr').querySelector('input[name*="[stock]"]');

    // 🧠 Reset ID jika user mengetik manual
    input.addEventListener('input', function () {
        clearTimeout(timeout);

        // Kosongkan ID supaya backend tahu belum ada pilihan valid
        if (hiddenInput) hiddenInput.value = '';
        if (satuanInput) satuanInput.value = '';
        if (stockInput) stockInput.value = '';

        const query = this.value.trim();

        if (query.length < 2) {
            resultsDiv.style.display = 'none';
            return;
        }

        timeout = setTimeout(() => {
            fetch(`/material/autocomplete?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    resultsDiv.innerHTML = '';
                    resultsDiv.style.display = data.length ? 'block' : 'none';

                    data.forEach(material => {
                        const item = document.createElement('div');
                        item.className = 'autocomplete-item';
                        item.innerHTML = `
                            <strong>[${material.material_code}]</strong> ${material.material_description}<br>
                            <small class="text-muted">Stock: ${material.unrestricted_use_stock || 0} ${material.base_unit_of_measure || ''}</small>
                        `;

                        // 🖱️ Saat user klik hasil autocomplete
                        item.addEventListener('click', function () {
                            input.value = `[${material.material_code}] - ${material.material_description}`;
                            hiddenInput.value = material.id;
                            satuanInput.value = material.base_unit_of_measure || '';
                            stockInput.value = material.unrestricted_use_stock || 0;

                            resultsDiv.style.display = 'none';
                            input.style.borderColor = ''; // reset warna
                            input.style.backgroundColor = '';
                        });

                        resultsDiv.appendChild(item);
                    });
                })
                .catch(error => {
                    console.error('Error fetching materials:', error);
                    resultsDiv.style.display = 'none';
                });
        }, 300);
    });

    // 🔻 Tutup hasil saat klik di luar
    document.addEventListener('click', function (e) {
        if (!input.contains(e.target) && !resultsDiv.contains(e.target)) {
            resultsDiv.style.display = 'none';
        }
    });

    // 🧩 Validasi visual — kalau user tidak pilih dari daftar
    input.addEventListener('blur', function () {
        if (hiddenInput && hiddenInput.value === '') {
            input.style.borderColor = '#dc3545';
            input.style.backgroundColor = '#f8d7da';
        } else {
            input.style.borderColor = '';
            input.style.backgroundColor = '';
        }
    });
}

// Function to validate stock quantity
function validateStockQuantity(quantityInput, showAlert = true) {

    // 🔕 SKIP VALIDASI STOK
    if (['Garansi', 'Perbaikan', 'Manual', 'Peminjaman'].includes(JENIS_SURAT)) {
        return true;
    }

    const row = quantityInput.closest('tr');
    const stockInput = row.querySelector('input[name*="[stock]"]');
    const materialSearch = row.querySelector('.material-autocomplete');

    const quantity = parseInt(quantityInput.value) || 0;
    const stock = parseInt(stockInput.value) || 0;

    if (quantity > stock && materialSearch && materialSearch.value.trim() !== '') {
        if (showAlert) {
            alert(`Quantity (${quantity}) melebihi stock (${stock}).`);
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
        if (quantity > stock && materialSearch.value.trim() !== '') {
            this.style.borderColor = '#dc3545';
            this.style.backgroundColor = '#f8d7da';
        } else {
            this.style.borderColor = '';
            this.style.backgroundColor = '';
        }
    });
}
function toggleManualModeEdit() {
    const table = document.getElementById('materialTable');
    const isManual = ['Manual', 'Peminjaman'].includes(JENIS_SURAT);
    const isNonStock = ['Garansi', 'Perbaikan', 'Manual', 'Peminjaman'].includes(JENIS_SURAT);

    // Header material
    table.querySelector('th:nth-child(2)').textContent =
        isManual ? 'Nama Barang (Manual)' : 'Material';

    // Header stock
    table.querySelector('th:nth-child(3)').style.display = isNonStock ? 'none' : '';

    table.querySelectorAll('tbody tr').forEach(row => {
        row.querySelector('td:nth-child(3)').style.display = isNonStock ? 'none' : '';
    });
}

function approveSuratJalan(id) {

    fetch(`/surat-jalan/${id}/approve`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => {
        if (response.ok) {
            // LANGSUNG REDIRECT TANPA ALERT
            window.location.href = "/surat-jalan"; 
        } else {
            console.error("Approve gagal");
        }
    })
    .catch(error => {
        console.error("Error jaringan:", error);
    });
}

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

// Function to check for duplicate materials
function checkDuplicateMaterials() {
    const materialIds = document.querySelectorAll('input[name*="[material_id]"]');
    const materialSearchInputs = document.querySelectorAll('.material-autocomplete');
    const duplicates = [];
    const seenMaterials = new Map();
    
    materialIds.forEach((input, index) => {
        const materialId = input.value;
        const materialSearch = materialSearchInputs[index] ? materialSearchInputs[index].value.trim() : '';
        
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
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded');
    toggleManualModeEdit();

    const materialInputs = document.querySelectorAll('.material-autocomplete');
    materialInputs.forEach(input => {
        initializeAutocomplete(input);
    });

    const quantityInputs = document.querySelectorAll('input[name*="[quantity]"]');
    quantityInputs.forEach(input => {
        addQuantityValidation(input);
    });

    updateRowNumbers();
});

</script>
@endpush

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