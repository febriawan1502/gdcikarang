@extends('layouts.app')

@section('title', 'Input MRWI')

@section('content')
    @push('styles')
        <style>
            .action-btn {
                width: 32px;
                height: 32px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border-radius: 8px;
                border: none;
                cursor: pointer;
                transition: all 0.2s;
            }

            .action-btn.delete {
                background: #FED7D7;
                color: #742A2A;
            }

            .autocomplete-results {
                max-height: 220px;
                overflow-y: auto;
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

        <form action="{{ route('material-mrwi.store') }}" method="POST" enctype="multipart/form-data"
            class="card p-6 border border-gray-100 shadow-xl shadow-gray-200/50">
            @csrf
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700">
                    <ul class="list-disc pl-5 space-y-1 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block">Tanggal Masuk</label>
                    <input type="date" name="tanggal_masuk" class="form-input w-full" value="{{ old('tanggal_masuk', date('Y-m-d')) }}" required>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block">Sumber</label>
                    <input type="text" name="sumber" class="form-input w-full" value="{{ old('sumber') }}" required>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block">Berdasarkan</label>
                    <input type="text" name="berdasarkan" class="form-input w-full" value="{{ old('berdasarkan') }}">
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
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block">File Excel</label>
                    <input type="file" name="mrwi_file" accept=".xlsx,.xls,.csv" class="form-input w-full" required>
                    <p class="text-xs text-gray-500 mt-2">
                        Header wajib: NO, NO MATERIAL, NAMA MATERIAL, QTY, SATUAN, SERIAL NUMBER, ATTB / LIMBAH,
                        STATUS ANGGARAN, NO ASSET, NAMA PABRIKAN, TAHUN BUAT, ID PELANGGAN, KLASIFIKASI, NO POLIS.
                    </p>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('material-mrwi.index') }}" class="px-6 py-2 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 rounded-xl bg-teal-500 text-white hover:bg-teal-600">
                    Simpan MRWI
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
@endpush
