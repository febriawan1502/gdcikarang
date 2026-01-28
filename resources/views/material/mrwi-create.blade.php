@extends('layouts.app')

@section('title', 'Input MRWI')

@section('content')
    @push('styles')
        <style>
            .form-input-sm {
                padding: 0.25rem 0.5rem;
                font-size: 0.875rem;
                border-radius: 0.375rem;
            }
        </style>
    @endpush

    <div class="space-y-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Input Material MRWI</h2>
                <p class="text-gray-500 text-sm mt-1">Masukkan data retur material bekas.</p>
            </div>
            <a href="{{ route('material-mrwi.index') }}"
                class="px-4 py-2 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 transition-all">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700">
                <ul class="list-disc pl-5 space-y-1 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{!! $error !!}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (isset($items))
            {{-- MODE REVIEW & EDIT --}}
            <form action="{{ route('material-mrwi.store') }}" method="POST" class="card space-y-6">
                @csrf

                {{-- Hidden / Readonly Header Data --}}
                <div class="p-6 border-b border-gray-100 bg-gray-50 rounded-t-xl">
                    <h3 class="text-lg font-bold text-gray-700 mb-4">Review Header Data</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Tanggal Masuk</label>
                            <input type="date" name="tanggal_masuk" value="{{ $requestData['tanggal_masuk'] ?? '' }}"
                                class="form-input w-full bg-gray-100" readonly>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Sumber</label>
                            <input type="text" name="sumber" value="{{ $requestData['sumber'] ?? '' }}"
                                class="form-input w-full bg-gray-100" readonly>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Berdasarkan</label>
                            <input type="text" name="berdasarkan" value="{{ $requestData['berdasarkan'] ?? '' }}"
                                class="form-input w-full bg-gray-100" readonly>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Lokasi</label>
                            <input type="text" name="lokasi" value="{{ $requestData['lokasi'] ?? '' }}"
                                class="form-input w-full bg-gray-100" readonly>
                        </div>
                        <div class="col-span-full">
                            <label class="text-xs font-bold text-gray-500 uppercase">Catatan</label>
                            <textarea name="catatan" class="form-input w-full bg-gray-100" rows="1" readonly>{{ $requestData['catatan'] ?? '' }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="p-6 overflow-x-auto">
                    <h3 class="text-lg font-bold text-gray-700 mb-4">Review & Edit Items</h3>
                    <table class="w-full text-sm border-collapse">
                        <thead>
                            <tr class="bg-gray-100 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                <th class="p-2 border">No</th>
                                <th class="p-2 border max-w-xs">No Material</th>
                                <th class="p-2 border max-w-xs">Nama Material</th>
                                <th class="p-2 border w-16">Qty</th>
                                <th class="p-2 border w-20">Satuan</th>
                                <th class="p-2 border">Serial (PLN)</th>
                                <th class="p-2 border">No Seri Material</th>
                                <th class="p-2 border">Merk</th>
                                <th class="p-2 border">Tahun</th>
                                <th class="p-2 border w-16">Klasifikasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($items as $idx => $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="p-2 border text-center font-mono">{{ $idx + 1 }}</td>

                                    {{-- Material ID (Hidden) --}}
                                    <input type="hidden" name="items[{{ $idx }}][material_id]"
                                        value="{{ $item['material_id'] }}">

                                    <td class="p-2 border">
                                        <input type="text" name="items[{{ $idx }}][no_material]"
                                            value="{{ $item['no_material'] }}"
                                            class="form-input-sm w-full border-gray-300 rounded text-right" readonly
                                            title="{{ $item['no_material'] }}">
                                    </td>
                                    <td class="p-2 border">
                                        <input type="text" name="items[{{ $idx }}][nama_material]"
                                            value="{{ $item['nama_material'] }}"
                                            class="form-input-sm w-full border-gray-300 rounded" readonly
                                            title="{{ $item['nama_material'] }}">
                                        @if (!$item['material_id'])
                                            <span class="text-xs text-red-500 block">Material Not Found</span>
                                        @endif
                                    </td>
                                    <td class="p-2 border">
                                        <input type="number" name="items[{{ $idx }}][qty]"
                                            value="{{ $item['qty'] }}"
                                            class="form-input-sm w-full border-gray-300 rounded text-right">
                                    </td>
                                    <td class="p-2 border">
                                        <input type="text" name="items[{{ $idx }}][satuan]"
                                            value="{{ $item['satuan'] }}"
                                            class="form-input-sm w-full border-gray-300 rounded">
                                    </td>
                                    <td class="p-2 border">
                                        <input type="text" name="items[{{ $idx }}][serial_number]"
                                            value="{{ $item['serial_number'] }}"
                                            class="form-input-sm w-full border-gray-300 rounded">
                                    </td>
                                    <td class="p-2 border">
                                        <input type="text" name="items[{{ $idx }}][id_pelanggan]"
                                            value="{{ $item['id_pelanggan'] }}"
                                            class="form-input-sm w-full border-gray-300 rounded">
                                    </td>
                                    <td class="p-2 border">
                                        @php
                                            $brands = match ($item['material_type'] ?? 'other') {
                                                'trafo' => $trafoBrands ?? [],
                                                'kubikel' => $kubikelBrands ?? [],
                                                default => [],
                                            };
                                        @endphp

                                        @if (count($brands) > 0)
                                            <select name="items[{{ $idx }}][nama_pabrikan]"
                                                class="form-input-sm w-full border-gray-300 rounded">
                                                <option value="" disabled
                                                    {{ empty($item['nama_pabrikan']) ? 'selected' : '' }}>Pilih Merk
                                                </option>
                                                @foreach ($brands as $brand)
                                                    <option value="{{ $brand }}"
                                                        {{ strtoupper($item['nama_pabrikan'] ?? '') == strtoupper($brand) ? 'selected' : '' }}>
                                                        {{ $brand }}</option>
                                                @endforeach
                                            </select>
                                        @else
                                            <input type="text" name="items[{{ $idx }}][nama_pabrikan]"
                                                value="{{ $item['nama_pabrikan'] }}"
                                                class="form-input-sm w-full border-gray-300 rounded">
                                        @endif
                                    </td>
                                    <td class="p-2 border">
                                        <input type="text" name="items[{{ $idx }}][tahun_buat]"
                                            value="{{ $item['tahun_buat'] }}"
                                            class="form-input-sm w-full border-gray-300 rounded">
                                    </td>
                                    <td class="p-2 border">
                                        <select name="items[{{ $idx }}][klasifikasi]"
                                            class="form-input-sm w-full border-gray-300 rounded text-center">
                                            <option value="" disabled
                                                {{ empty($item['klasifikasi']) ? 'selected' : '' }}>Pilih</option>
                                            <option value="1" {{ $item['klasifikasi'] == 1 ? 'selected' : '' }}>
                                                Standby</option>
                                            <option value="2" {{ $item['klasifikasi'] == 2 ? 'selected' : '' }}>
                                                Garansi</option>
                                            <option value="3" {{ $item['klasifikasi'] == 3 ? 'selected' : '' }}>
                                                Perbaikan</option>
                                            <option value="4"
                                                {{ in_array($item['klasifikasi'], [4, 5, 6]) ? 'selected' : '' }}>Rusak
                                            </option>
                                        </select>
                                    </td>

                                    {{-- Hidden Fields for others --}}
                                    <input type="hidden" name="items[{{ $idx }}][attb_limbah]"
                                        value="{{ $item['attb_limbah'] }}">
                                    <input type="hidden" name="items[{{ $idx }}][status_anggaran]"
                                        value="{{ $item['status_anggaran'] }}">
                                    <input type="hidden" name="items[{{ $idx }}][no_asset]"
                                        value="{{ $item['no_asset'] }}">
                                    <input type="hidden" name="items[{{ $idx }}][no_polis]"
                                        value="{{ $item['no_polis'] }}">
                                    {{-- Catatan (Keterangan) removed from view --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-6 bg-gray-50 border-t border-gray-100 flex justify-end gap-3 rounded-b-xl">
                    <a href="{{ route('material-mrwi.create') }}"
                        class="px-6 py-2 rounded-xl border border-gray-200 text-gray-600 hover:bg-white bg-white">
                        <i class="fas fa-redo mr-2"></i>Upload Ulang
                    </a>
                    <button type="submit"
                        class="px-6 py-2 rounded-xl bg-teal-500 text-white hover:bg-teal-600 shadow-lg shadow-teal-500/30">
                        <i class="fas fa-save mr-2"></i>Simpan Permanen
                    </button>
                </div>
            </form>
        @else
            {{-- MODE UPLOAD --}}
            <form action="{{ route('material-mrwi.preview') }}" method="POST" enctype="multipart/form-data"
                class="card p-6 border border-gray-100 shadow-xl shadow-gray-200/50">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block">Tanggal
                            Masuk</label>
                        <input type="date" name="tanggal_masuk" class="form-input w-full"
                            value="{{ old('tanggal_masuk', date('Y-m-d')) }}" required>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block">Sumber</label>
                        <input type="text" name="sumber" class="form-input w-full" value="{{ old('sumber') }}"
                            required>
                    </div>
                    <div>
                        <label
                            class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block">Berdasarkan</label>
                        <input type="text" name="berdasarkan" class="form-input w-full"
                            value="{{ old('berdasarkan') }}">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block">Lokasi</label>
                        <input type="text" name="lokasi" class="form-input w-full" value="{{ old('lokasi') }}">
                    </div>
                    <div class="md:col-span-2 lg:col-span-3">
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block">Catatan</label>
                        <textarea name="catatan" rows="2" class="form-input w-full">{{ old('catatan') }}</textarea>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Upload Excel MRWI</h3>
                    <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                        <label class="text-xs font-bold text-blue-500 uppercase tracking-wider mb-2 block">File
                            Excel</label>
                        <input type="file" name="mrwi_file" accept=".xlsx,.xls,.csv" class="form-input w-full"
                            required>
                        <p class="text-xs text-blue-500 mt-2">
                            <strong>Note:</strong> File akan diproses dan ditampilkan dalam tabel edit terlebih dahulu
                            sebelum disimpan.
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            Header wajib: NO, NO MATERIAL, NAMA MATERIAL, QTY, SATUAN, SERIAL NUMBER, ATTB / LIMBAH,
                            STATUS ANGGARAN, NO ASSET, NAMA PABRIKAN, TAHUN BUAT, ID PELANGGAN, KLASIFIKASI, NO POLIS.
                        </p>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('material-mrwi.index') }}"
                        class="px-6 py-2 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-2 rounded-xl bg-blue-500 text-white hover:bg-blue-600 shadow-lg shadow-blue-500/30">
                        <i class="fas fa-file-import mr-2"></i>Preview Data
                    </button>
                </div>
            </form>
        @endif
    </div>
@endsection
