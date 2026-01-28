@extends('layouts.app')

@section('title', 'Edit Surat Jalan')

@section('page-title', 'Edit Surat Jalan')

@section('content')
    <!-- <pre>{{ json_encode($suratJalan->details, JSON_PRETTY_PRINT) }}</pre> -->

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Edit Surat Jalan</h2>
                <p class="text-gray-500 text-sm mt-1">Perbarui data dan detail pengiriman material.</p>
                <span
                    class="inline-flex mt-3 px-4 py-2 rounded-lg bg-teal-50 text-teal-700 text-sm font-semibold border border-teal-100">
                    {{ $suratJalan->nomor_surat }}
                </span>
            </div>
            <a href="{{ route('surat-jalan.index') }}"
                class="flex items-center gap-2 text-gray-600 hover:text-gray-800 transition-colors">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>

        <!-- Form Container -->
        <div class="card border border-gray-100 shadow-xl shadow-gray-200/50">
            <form action="{{ route('surat-jalan.update', $suratJalan->id) }}" method="POST" id="suratJalanForm">
                @csrf
                @method('PUT')

                    <!-- Section: Informasi Surat Jalan -->
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-center gap-3 mb-6">
                            <div
                                class="w-10 h-10 rounded-xl bg-linear-to-br from-teal-400 to-teal-500 flex items-center justify-center text-white">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Informasi Surat Jalan</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nomor Surat (Readonly) -->
                            <div>
                                <label for="nomor_surat" class="form-label">
                                    Nomor Surat Jalan <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="nomor_surat" name="nomor_surat"
                                    value="{{ $suratJalan->nomor_surat }}" readonly
                                    class="form-input bg-gray-50 text-gray-500 cursor-not-allowed">
                            </div>

                            <!-- Jenis Surat -->
                            <div>
                                <label for="jenis_surat_jalan" class="form-label">
                                    Jenis Surat Jalan <span class="text-red-500">*</span>
                                </label>
                                <input type="text" value="{{ $suratJalan->jenis_surat_jalan }}" readonly
                                    class="form-input bg-gray-50 text-gray-500 cursor-not-allowed">
                                <input type="hidden" name="jenis_surat_jalan" value="{{ $suratJalan->jenis_surat_jalan }}">
                            </div>

                            <!-- Tanggal -->
                            <div>
                                <label for="tanggal" class="form-label">
                                    Tanggal Surat Jalan <span class="text-red-500">*</span>
                                </label>
                                <input type="date" id="tanggal" name="tanggal"
                                    value="{{ old('tanggal', \Carbon\Carbon::parse($suratJalan->tanggal)->format('Y-m-d')) }}"
                                    class="form-input">
                            </div>
                        </div>
                    </div>

                    <!-- Section: Informasi Penerima -->
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-center gap-3 mb-6">
                            <div
                                class="w-10 h-10 rounded-xl bg-linear-to-br from-blue-400 to-blue-500 flex items-center justify-center text-white">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Informasi Penerima</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Kepada -->
                            <div>
                                <label for="kepada" class="form-label">
                                    Diberikan Kepada <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="kepada" name="kepada" value="{{ $suratJalan->kepada }}"
                                    required placeholder="Vendor / Unit PLN" class="form-input">
                            </div>

                            <!-- Berdasarkan -->
                            <div class="md:row-span-2">
                                <label for="berdasarkan" class="form-label">
                                    Berdasarkan <span class="text-red-500">*</span>
                                </label>
                                <textarea id="berdasarkan" name="berdasarkan" rows="5" required
                                    placeholder="Dasar permintaan / referensi..." class="form-input">{{ $suratJalan->berdasarkan }}</textarea>
                            </div>

                            <!-- Keterangan -->
                            <div>
                                <label for="keterangan" class="form-label">
                                    Untuk Pekerjaan
                                </label>
                                <input type="text" id="keterangan" name="keterangan"
                                    value="{{ $suratJalan->keterangan }}" placeholder="Deskripsi pekerjaan..."
                                    class="form-input">
                            </div>

                            <!-- Nama Penerima -->
                            <div>
                                <label for="nama_penerima" class="form-label">
                                    Nama Penerima
                                </label>
                                <input type="text" id="nama_penerima" name="nama_penerima"
                                    value="{{ $suratJalan->nama_penerima }}" placeholder="Nama Penerima"
                                    class="form-input">
                            </div>

                            <!-- Nomor Slip -->
                            <div>
                                <label for="nomor_slip" class="form-label">
                                    Nomor Slip
                                </label>
                                <input type="text" id="nomor_slip" name="nomor_slip"
                                    value="{{ $suratJalan->nomor_slip }}" placeholder="No SAP : TUG8 / TUG9"
                                    class="form-input">
                            </div>
                        </div>
                    </div>

                    <!-- Section: Daftar Material -->
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-center gap-3 mb-6">
                            <div
                                class="w-10 h-10 rounded-xl bg-linear-to-br from-purple-400 to-purple-500 flex items-center justify-center text-white">
                                <i class="fas fa-boxes"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Daftar Material</h3>
                        </div>

                        <div class="rounded-xl border border-gray-100">
                            <table class="table-purity w-full" id="materialTable">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider w-12">
                                            No
                                        </th>
                                        <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">
                                            Material / Barang
                                        </th>
                                        <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider col-serial">
                                            Serial Number
                                        </th>
                                        <th
                                            class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider w-[140px] col-stock">
                                            Stock
                                        </th>
                                        <th
                                            class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider w-[140px]">
                                            Qty
                                        </th>
                                        <th
                                            class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider w-[100px]">
                                            Satuan
                                        </th>
                                        <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">
                                            Keterangan
                                        </th>
                                        <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider w-16">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white">
                                        @foreach ($suratJalan->details as $index => $detail)
                                            <tr>
                                                <td class="px-4 py-3 text-sm text-gray-500 text-center">
                                                    {{ $index + 1 }}</td>

                                                {{-- Kolom Material --}}
                                                <td class="px-4 py-3">
                                                    @if (in_array($suratJalan->jenis_surat_jalan, ['Manual', 'Peminjaman']))
                                                        @if ($suratJalan->status === 'APPROVED')
                                                            <input type="text"
                                                                value="{{ $detail->nama_barang_manual }}" readonly
                                                                class="block w-full rounded-md bg-gray-50 border-gray-300 text-gray-500 shadow-sm sm:text-sm">
                                                            <input type="hidden"
                                                                name="materials[{{ $index }}][nama_barang]"
                                                                value="{{ $detail->nama_barang_manual }}">
                                                        @else
                                                            <input type="text"
                                                                name="materials[{{ $index }}][nama_barang]"
                                                                value="{{ $detail->nama_barang_manual }}"
                                                                placeholder="Nama barang manual..." required
                                                                class="block w-full rounded-md border-gray-300 px-3 py-2 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                                                        @endif
                                                    @else
                                                        {{-- yang normal tetap pakai master/autocomplete --}}
                                                        @if (in_array($suratJalan->jenis_surat_jalan, ['Garansi', 'Perbaikan', 'Rusak', 'Standby']))
                                                            <input type="text"
                                                                value="{{ $detail->material->material_code ?? '' }} - {{ $detail->material->material_description ?? '' }}"
                                                                readonly
                                                                class="block w-full rounded-md bg-gray-50 border-gray-300 text-gray-500 shadow-sm sm:text-sm mrwi-material-display">
                                                            <input type="hidden"
                                                                name="materials[{{ $index }}][material_id]"
                                                                value="{{ $detail->material_id }}">
                                                            <input type="hidden"
                                                                name="materials[{{ $index }}][material_search]"
                                                                value="{{ $detail->material->material_code ?? '' }} - {{ $detail->material->material_description ?? '' }}">
                                                        @elseif($suratJalan->status === 'APPROVED')
                                                            <input type="text"
                                                                value="{{ $detail->material->material_code ?? '' }} - {{ $detail->material->material_description ?? '' }}"
                                                                readonly
                                                                class="block w-full rounded-md bg-gray-50 border-gray-300 text-gray-500 shadow-sm sm:text-sm">
                                                            <input type="hidden"
                                                                name="materials[{{ $index }}][material_id]"
                                                                value="{{ $detail->material_id }}">
                                                            <input type="hidden"
                                                                name="materials[{{ $index }}][material_search]"
                                                                value="{{ $detail->material->material_code ?? '' }} - {{ $detail->material->material_description ?? '' }}">
                                                        @else
                                                            <div class="relative">
                                                                <input type="text"
                                                                    name="materials[{{ $index }}][material_search]"
                                                                    value="{{ $detail->material->material_code ?? '' }} - {{ $detail->material->material_description ?? '' }}"
                                                                    autocomplete="off" required
                                                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm material-autocomplete placeholder-gray-400">

                                                                <input type="hidden"
                                                                    name="materials[{{ $index }}][material_id]"
                                                                    value="{{ $detail->material_id }}"
                                                                    class="material-id-input">
                                                                <div
                                                                    class="autocomplete-results absolute z-50 w-full bg-white border border-gray-200 rounded-md shadow-lg mt-1 max-h-60 overflow-y-auto hidden">
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </td>

                                                {{-- Kolom Serial --}}
                                                <td class="px-4 py-3 col-serial">
                                                    @if (in_array($suratJalan->jenis_surat_jalan, ['Garansi', 'Perbaikan', 'Rusak', 'Standby']))
                                                        @php
                                                            $serial = $detail->serial_numbers[0] ?? '';
                                                        @endphp
                                                        @if ($suratJalan->status === 'APPROVED')
                                                            <input type="text" value="{{ $serial }}" readonly
                                                                class="block w-full rounded-md bg-gray-50 border-gray-300 text-gray-500 shadow-sm sm:text-sm">
                                                        @else
                                                            <input type="text"
                                                                name="materials[{{ $index }}][serial_number]"
                                                                value="{{ $serial }}"
                                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm mrwi-serial-input"
                                                                placeholder="Serial Number" required>
                                                            <p class="text-xs text-gray-400 mt-1 mrwi-serial-hint hidden">
                                                                Tekan Enter untuk ambil detail material.</p>
                                                        @endif
                                                    @else
                                                        <input type="text" value="-" readonly
                                                            class="block w-full rounded-md bg-gray-50 border-gray-300 text-center text-gray-500 shadow-sm sm:text-sm">
                                                    @endif
                                                </td>

                                                {{-- Kolom Stock --}}
                                                <td class="px-4 py-3">
                                                    @if (in_array($suratJalan->jenis_surat_jalan, ['Manual', 'Peminjaman']))
                                                        <input type="text" value="-" readonly
                                                            class="block w-full rounded-md bg-gray-50 border-gray-300 text-center text-gray-500 shadow-sm sm:text-sm">
                                                    @elseif(in_array($suratJalan->jenis_surat_jalan, ['Garansi', 'Perbaikan', 'Rusak', 'Standby']))
                                                        <input type="text" value="-" readonly
                                                            class="block w-full rounded-md bg-gray-50 border-gray-300 text-center text-gray-500 shadow-sm sm:text-sm">
                                                    @else
                                                        <input type="number"
                                                            name="materials[{{ $index }}][stock]"
                                                            value="{{ $detail->material->unrestricted_use_stock ?? 0 }}"
                                                            readonly
                                                            class="block w-full rounded-md bg-gray-50 border-gray-300 text-center text-gray-500 shadow-sm sm:text-sm font-mono">
                                                    @endif
                                                </td>

                                                {{-- Kolom Qty --}}
                                                <td class="px-4 py-3">
                                                    <input type="number" name="materials[{{ $index }}][quantity]"
                                                        value="{{ $detail->quantity }}" min="1" required
                                                        {{ $suratJalan->status === 'APPROVED' || in_array($suratJalan->jenis_surat_jalan, ['Garansi', 'Perbaikan', 'Rusak', 'Standby']) ? 'readonly' : '' }}
                                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm text-center font-bold {{ $suratJalan->status === 'APPROVED' ? 'bg-gray-50 text-gray-500' : 'text-gray-900' }}">
                                                </td>

                                                {{-- Kolom Satuan --}}
                                                <td class="px-4 py-3">
                                                    <input type="text" name="materials[{{ $index }}][satuan]"
                                                        value="{{ $detail->satuan }}" readonly
                                                        class="block w-full rounded-md bg-gray-50 border-gray-300 text-gray-500 shadow-sm sm:text-sm text-center">
                                                </td>

                                                {{-- Kolom Keterangan --}}
                                                <td class="px-4 py-3">
                                                    <input type="text"
                                                        name="materials[{{ $index }}][keterangan]"
                                                        value="{{ $detail->keterangan }}" placeholder="Ket..."
                                                        {{ $suratJalan->status === 'APPROVED' ? 'readonly' : '' }}
                                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm {{ $suratJalan->status === 'APPROVED' ? 'bg-gray-50 text-gray-500' : '' }}">
                                                </td>

                                                {{-- Kolom Aksi --}}
                                                <td class="px-4 py-3 text-center">
                                                    @if ($suratJalan->status !== 'APPROVED')
                                                        <button type="button" onclick="removeRow(this)"
                                                            class="text-red-500 hover:text-red-700 hover:bg-red-50 p-2 rounded-full transition-colors">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
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

                    <!-- Section: Keterangan Tambahan -->
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-center gap-3 mb-6">
                            <div
                                class="w-10 h-10 rounded-xl bg-linear-to-br from-yellow-400 to-yellow-500 flex items-center justify-center text-white">
                                <i class="fas fa-sticky-note"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Keterangan Tambahan</h3>
                        </div>

                        <div>
                            <textarea id="keterangan" name="keterangan" rows="3"
                                placeholder="Catatan tambahan (opsional)..." class="form-input">{{ $suratJalan->keterangan }}</textarea>
                        </div>
                    </div>

                    <!-- Section: Informasi Kendaraan -->
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-center gap-3 mb-6">
                            <div
                                class="w-10 h-10 rounded-xl bg-linear-to-br from-orange-400 to-orange-500 flex items-center justify-center text-white">
                                <i class="fas fa-truck"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800">Informasi Kendaraan</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <!-- Kendaraan -->
                            <div>
                                <label for="kendaraan" class="form-label">Kendaraan</label>
                                <input type="text" id="kendaraan" name="kendaraan"
                                    value="{{ $suratJalan->kendaraan }}" placeholder="Jenis/Merk kendaraan"
                                    class="form-input">
                            </div>

                            <!-- No Polisi -->
                            <div>
                                <label for="no_polisi" class="form-label">No. Polisi</label>
                                <input type="text" id="no_polisi" name="no_polisi"
                                    value="{{ $suratJalan->no_polisi }}" placeholder="Nomor polisi kendaraan"
                                    class="form-input uppercase">
                            </div>

                            <!-- Pengemudi -->
                            <div>
                                <label for="pengemudi" class="form-label">Pengemudi</label>
                                <input type="text" id="pengemudi" name="pengemudi"
                                    value="{{ $suratJalan->pengemudi }}" placeholder="Nama pengemudi"
                                    class="form-input">
                            </div>

                            <!-- Security -->
                            <div>
                                <label for="security" class="form-label">Security</label>
                                <input type="text" id="security" name="security"
                                    value="{{ $suratJalan->security }}" placeholder="Nama security"
                                    class="form-input">
                            </div>
                        </div>
                    </div>

                    <!-- Section: Foto Dokumentasi -->
                    @if ($suratJalan->foto_penerima)
                        @php
                            $fotoPath = $suratJalan->foto_penerima;
                            $normalizedPath = \Illuminate\Support\Str::startsWith($fotoPath, 'storage/')
                                ? substr($fotoPath, 8)
                                : ltrim($fotoPath, '/');
                            $fotoUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($normalizedPath);
                        @endphp
                        <div class="p-6 border-b border-gray-100">
                            <div class="flex items-center gap-3 mb-6">
                                <div
                                    class="w-10 h-10 rounded-xl bg-linear-to-br from-purple-400 to-purple-500 flex items-center justify-center text-white">
                                    <i class="fas fa-camera"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">Foto Dokumentasi</h3>
                            </div>

                            <div
                                class="bg-gray-50 rounded-xl border border-dashed border-gray-300 p-6 flex flex-col items-center justify-center">
                                <a href="{{ $fotoUrl }}" target="_blank"
                                    class="group relative block overflow-hidden rounded-lg shadow-md hover:shadow-xl transition-all duration-300">
                                    <img src="{{ $fotoUrl }}" alt="Foto Penerima"
                                        class="max-w-full h-auto max-h-80 object-cover transform group-hover:scale-105 transition-transform duration-500">
                                    <div
                                        class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300 flex items-center justify-center">
                                        <i
                                            class="fas fa-search-plus text-white opacity-0 group-hover:opacity-100 text-3xl transform scale-0 group-hover:scale-100 transition-all duration-300"></i>
                                    </div>
                                </a>
                                <p class="text-gray-500 text-sm mt-3">
                                    <i class="fas fa-info-circle mr-1"></i> Klik gambar untuk memperbesar
                                </p>
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="p-6 bg-gray-50 flex flex-col sm:flex-row justify-between items-center gap-4">
                        <a href="{{ route('surat-jalan.index') }}"
                            class="w-full sm:w-auto px-6 py-3 rounded-xl border border-gray-300 text-gray-600 hover:bg-gray-100 transition-colors flex items-center justify-center gap-2">
                            <i class="fas fa-arrow-left"></i>
                            <span>Kembali</span>
                        </a>

                        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                            @if ($suratJalan->status === 'BUTUH_PERSETUJUAN')
                                <button type="button" onclick="approveSuratJalan({{ $suratJalan->id }})"
                                    class="flex-1 sm:flex-none px-6 py-3 rounded-xl bg-blue-600 text-white hover:bg-blue-700 transition-colors flex items-center justify-center gap-2 shadow-lg shadow-blue-500/20 hover:shadow-blue-500/40">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Approve Surat Jalan</span>
                                </button>
                            @endif

                            <button type="submit" name="action" value="update"
                                class="flex-1 sm:flex-none btn-teal px-6 py-3 shadow-lg shadow-teal-500/20 hover:shadow-teal-500/40 transition-all duration-300 flex items-center justify-center gap-2">
                                <i class="fas fa-save"></i>
                                <span>Simpan Perubahan</span>
                            </button>

                            @if ($suratJalan->status === 'APPROVED')
                                <button type="submit" name="action" value="selesai"
                                    class="flex-1 sm:flex-none btn-green px-6 py-3 rounded-xl flex items-center justify-center gap-2">
                                    <i class="fas fa-check-double"></i>
                                    <span>Tandai Selesai</span>
                                </button>
                            @endif
                        </div>
                    </div>

            </form>
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
    </style>
@endpush

@push('scripts')
    <script>
        const JENIS_SURAT = "{{ $suratJalan->jenis_surat_jalan }}";
        const STATUS_SURAT = "{{ $suratJalan->status }}";
        // let rowCount = {{ count($suratJalan->details) }};

        // Materials data for JavaScript
        const materialsData = [
            @foreach ($materials as $material)
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
            const isNonStock = ['Manual', 'Peminjaman'].includes(JENIS_SURAT);
            const isMrwi = ['Garansi', 'Perbaikan', 'Rusak', 'Standby'].includes(JENIS_SURAT);

            const tr = document.createElement('tr');

            if (isManual) {
                tr.innerHTML = `
            <td class="px-4 py-3 text-sm text-gray-500 text-center">${index + 1}</td>
            <td class="px-4 py-3">
                <input type="text" name="materials[${index}][nama_barang]"
                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm" required placeholder="Nama barang manual...">
            </td>
            <td class="px-4 py-3 col-serial" style="display:none;">
                <input type="text" value="-" readonly class="block w-full rounded-md bg-gray-50 border-gray-300 text-center text-gray-500 shadow-sm sm:text-sm">
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
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm ${isMrwi ? 'mrwi-material-display bg-gray-50' : 'material-autocomplete'} placeholder-gray-400"
                           autocomplete="off" required placeholder="${isMrwi ? 'Otomatis dari serial' : 'Cari material...'}" ${isMrwi ? 'readonly' : ''}>
                    <input type="hidden" name="materials[${index}][material_id]"
                           class="material-id">
                    <div class="autocomplete-results absolute z-50 w-full bg-white border border-gray-200 rounded-md shadow-lg mt-1 max-h-60 overflow-y-auto hidden"></div>
                </div>
            </td>
            <td class="px-4 py-3 col-serial" style="${isMrwi ? '' : 'display:none;'}">
                <input type="text" name="materials[${index}][serial_number]" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm mrwi-serial-input"
                    placeholder="Serial Number" ${isMrwi ? 'required' : ''}>
                <p class="text-xs text-gray-400 mt-1 mrwi-serial-hint hidden">Tekan Enter untuk ambil detail material.</p>
            </td>
            <td class="px-4 py-3" style="${isNonStock ? 'display:none;' : ''}">
                <input type="number" name="materials[${index}][stock]"
                       class="block w-full rounded-md bg-gray-50 border-gray-300 text-center text-gray-500 shadow-sm sm:text-sm font-mono" readonly>
            </td>
            <td class="px-4 py-3">
                <input type="number" name="materials[${index}][quantity]"
                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm text-center font-bold text-gray-900" required min="1" ${isMrwi ? 'readonly' : ''}>
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

            if (!isManual && !isMrwi) {
                initializeAutocomplete(tr.querySelector('.material-autocomplete'));
            }
            if (isMrwi) {
                const serialInput = tr.querySelector('.mrwi-serial-input');
                if (serialInput) {
                    attachSerialHandler(serialInput);
                }
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
            input.addEventListener('input', function() {
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
                    fetch(
                            `/material/autocomplete?query=${encodeURIComponent(query)}&jenis=${encodeURIComponent(JENIS_SURAT)}`
                            )
                        .then(response => response.json())
                        .then(data => {
                            resultsDiv.innerHTML = '';
                            resultsDiv.style.display = data.length ? 'block' : 'none';

                            data.forEach(material => {
                                const item = document.createElement('div');
                                item.className = 'autocomplete-item';
                                item.innerHTML = `
                            <strong>[${material.material_code}]</strong> ${material.material_description}<br>
                            <small class="text-muted">Stock: ${(material.available_stock ?? material.unrestricted_use_stock ?? 0)} ${material.base_unit_of_measure || ''}</small>
                        `;

                                // 🖱️ Saat user klik hasil autocomplete
                                item.addEventListener('click', function() {
                                    input.value =
                                        `[${material.material_code}] - ${material.material_description}`;
                                    hiddenInput.value = material.id;
                                    satuanInput.value = material.base_unit_of_measure ||
                                        '';
                                    if (!['Manual', 'Peminjaman'].includes(
                                            JENIS_SURAT)) {
                                        stockInput.value = (material.available_stock ??
                                            material.unrestricted_use_stock ?? 0);
                                    } else {
                                        stockInput.value = '';
                                    }

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
            document.addEventListener('click', function(e) {
                if (!input.contains(e.target) && !resultsDiv.contains(e.target)) {
                    resultsDiv.style.display = 'none';
                }
            });

            // 🧩 Validasi visual — kalau user tidak pilih dari daftar
            input.addEventListener('blur', function() {
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
            if (['Manual', 'Peminjaman'].includes(JENIS_SURAT) || isMrwiJenis(JENIS_SURAT)) {
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
            const isNonStock = ['Manual', 'Peminjaman'].includes(JENIS_SURAT);
            const isMrwi = isMrwiJenis(JENIS_SURAT);

            // Header material
            table.querySelector('th:nth-child(2)').textContent =
                isManual ? 'Nama Barang (Manual)' : 'Material';

            // Header serial
            table.querySelector('th:nth-child(3)').style.display = isMrwi ? '' : 'none';

            // Header stock
            table.querySelector('th:nth-child(4)').style.display = isNonStock ? 'none' : '';

            table.querySelectorAll('tbody tr').forEach(row => {
                row.querySelector('td:nth-child(3)').style.display = isMrwi ? '' : 'none';
                row.querySelector('td:nth-child(4)').style.display = isNonStock ? 'none' : '';
            });
        }

        function isMrwiJenis(jenis) {
            return ['Garansi', 'Perbaikan', 'Rusak', 'Standby'].includes(jenis);
        }

        function attachSerialHandler(input) {
            input.addEventListener('keydown', function(e) {
                if (e.key !== 'Enter') {
                    return;
                }
                e.preventDefault();
                handleSerialLookup(input);
            });
        }

        function handleSerialLookup(input) {
            const row = input.closest('tr');
            const serial = (input.value || '').trim();
            if (!serial) {
                return;
            }

            if (isDuplicateSerial(serial, input)) {
                alert('Serial number sudah ada di list.');
                clearMrwiRow(row);
                input.value = '';
                input.focus();
                return;
            }

            const hint = row.querySelector('.mrwi-serial-hint');
            if (hint) {
                hint.classList.add('hidden');
            }

            fetch(
                    `{{ route('material-mrwi.serial-lookup') }}?serial=${encodeURIComponent(serial)}&jenis=${encodeURIComponent(JENIS_SURAT)}`
                    )
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        alert(data.message || 'Serial number tidak valid.');
                        clearMrwiRow(row);
                        input.value = '';
                        return;
                    }

                    const hiddenInput = row.querySelector('.material-id-input, .material-id');
                    const materialInput = row.querySelector('.mrwi-material-display');
                    const materialSearch = row.querySelector('input[name*="[material_search]"]');
                    const satuanInput = row.querySelector('input[name*="[satuan]"]');
                    const qtyInput = row.querySelector('input[name*="[quantity]"]');
                    const stockInput = row.querySelector('input[name*="[stock]"]');

                    hiddenInput.value = data.material.id;
                    if (materialInput) {
                        materialInput.value = `[${data.material.code}] - ${data.material.description}`;
                    }
                    if (materialSearch) {
                        materialSearch.value = `[${data.material.code}] - ${data.material.description}`;
                    }
                    if (satuanInput) {
                        satuanInput.value = data.material.satuan || '';
                    }
                    if (qtyInput) {
                        qtyInput.value = 1;
                    }
                    if (stockInput) {
                        stockInput.value = 1;
                    }
                })
                .catch(() => {
                    alert('Gagal ambil data serial number.');
                    clearMrwiRow(row);
                });
        }

        function clearMrwiRow(row) {
            const hiddenInput = row.querySelector('.material-id-input, .material-id');
            const materialInput = row.querySelector('.mrwi-material-display');
            const materialSearch = row.querySelector('input[name*="[material_search]"]');
            const satuanInput = row.querySelector('input[name*="[satuan]"]');
            const qtyInput = row.querySelector('input[name*="[quantity]"]');
            const stockInput = row.querySelector('input[name*="[stock]"]');

            if (hiddenInput) hiddenInput.value = '';
            if (materialInput) materialInput.value = '';
            if (materialSearch) materialSearch.value = '';
            if (satuanInput) satuanInput.value = '';
            if (qtyInput) qtyInput.value = '';
            if (stockInput) stockInput.value = '';
        }

        function isDuplicateSerial(serial, currentInput) {
            const inputs = document.querySelectorAll('.mrwi-serial-input');
            for (const input of inputs) {
                if (input === currentInput) {
                    continue;
                }
                if ((input.value || '').trim().toLowerCase() === serial.toLowerCase()) {
                    return true;
                }
            }
            return false;
        }

        function approveSuratJalan(id) {
            Swal.fire({
                title: 'Konfirmasi',
                text: "Apakah Anda yakin ingin menyetujui Surat Jalan ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Approve!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading state
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Mohon tunggu sebentar.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    fetch(`/surat-jalan/${id}/approve`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json', // Ensure we expect JSON
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(async response => {
                            const data = await response.json().catch(() =>
                                ({})); // Try to parse JSON, default to empty

                            if (response.ok) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: 'Surat Jalan berhasil disetujui.',
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.href = "/surat-jalan";
                                });
                            } else {
                                // Handle server errors (e.g., 400, 422, 500)
                                throw new Error(data.message || 'Gagal melakukan approval.');
                            }
                        })
                        .catch(error => {
                            console.error("Error approval:", error);
                            Swal.fire({
                                title: 'Gagal!',
                                text: error.message || 'Terjadi kesalahan sistem.',
                                icon: 'error'
                            });
                        });
                }
            });
        }

        // Function to update row numbers
        function updateRowNumbers() {
            const rows = document.querySelectorAll('#materialTable tbody tr');
            rows.forEach((row, index) => {
                row.querySelector('td:first-child').textContent = index + 1;

                // Update input names to maintain proper indexing
                const fields = row.querySelectorAll('input, select');
                fields.forEach(field => {
                    const name = field.getAttribute('name');
                    if (name && name.includes('materials[')) {
                        const newName = name.replace(/materials\[\d+\]/, `materials[${index}]`);
                        field.setAttribute('name', newName);
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

            if (!isMrwiJenis(JENIS_SURAT)) {
                const materialInputs = document.querySelectorAll('.material-autocomplete');
                materialInputs.forEach(input => {
                    initializeAutocomplete(input);
                });
            }

            const quantityInputs = document.querySelectorAll('input[name*="[quantity]"]');
            quantityInputs.forEach(input => {
                addQuantityValidation(input);
            });

            updateRowNumbers();

            if (isMrwiJenis(JENIS_SURAT) && STATUS_SURAT !== 'APPROVED') {
                const serialInputs = document.querySelectorAll('.mrwi-serial-input');
                serialInputs.forEach(input => {
                    attachSerialHandler(input);
                });
            }

            const form = document.getElementById('suratJalanForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    if (!isMrwiJenis(JENIS_SURAT) || STATUS_SURAT === 'APPROVED') {
                        return;
                    }

                    let hasSerialError = false;
                    const seenSerials = new Set();
                    const rows = document.querySelectorAll('#materialTable tbody tr');
                    rows.forEach(row => {
                        const serialInput = row.querySelector('.mrwi-serial-input');
                        const qtyInput = row.querySelector('input[name*="[quantity]"]');
                        if (!serialInput || !qtyInput) {
                            return;
                        }
                        const serial = (serialInput.value || '').trim();
                        if (!serial) {
                            hasSerialError = true;
                            return;
                        }
                        const key = serial.toLowerCase();
                        if (seenSerials.has(key)) {
                            hasSerialError = true;
                            return;
                        }
                        seenSerials.add(key);
                        qtyInput.value = 1;
                    });

                    if (hasSerialError) {
                        e.preventDefault();
                        alert('Semua material MRWI wajib memilih serial number.');
                    }
                });
            }
        });
    </script>
@endpush

