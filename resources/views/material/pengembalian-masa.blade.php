@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Pengembalian Barang</h2>
            <p class="text-gray-500 text-sm mt-1">Lengkapi data pengembalian untuk surat jalan ini.</p>
        </div>
        <a href="{{ url()->previous() }}" class="flex items-center gap-2 text-gray-600 hover:text-gray-800 transition-colors">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali</span>
        </a>
    </div>

    <div class="card p-6 border border-gray-100 shadow-xl shadow-gray-200/50">
        <div class="rounded-xl border border-gray-100 bg-white p-5 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div class="flex items-center justify-between rounded-lg bg-gray-50 border border-gray-100 px-4 py-3">
                    <span class="text-gray-500 font-semibold">Nomor Surat Jalan</span>
                    <span class="text-gray-800 font-semibold">{{ $surat->nomor_surat }}</span>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-gray-50 border border-gray-100 px-4 py-3">
                    <span class="text-gray-500 font-semibold">Jenis Surat Jalan</span>
                    <span class="text-gray-800 font-semibold">{{ ucfirst($surat->jenis_surat_jalan) }}</span>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-gray-50 border border-gray-100 px-4 py-3">
                    <span class="text-gray-500 font-semibold">Tanggal Keluar</span>
                    <span class="text-gray-800 font-semibold">{{ $surat->tanggal->format('d/m/Y') }}</span>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-gray-50 border border-gray-100 px-4 py-3">
                    <span class="text-gray-500 font-semibold">Diberikan Kepada</span>
                    <span class="text-gray-800 font-semibold">{{ $surat->kepada }}</span>
                </div>
            </div>
        </div>

        @if(auth()->user()->role !== 'guest')
        <form action="{{ route('surat-jalan.kembalikan', ['suratId' => $surat->id, 'detailId' => $detail->id]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="form-label">Nomor Surat Jalan Masuk</label>
                    <input type="text" name="nomor_surat_masuk" class="form-input"
                           placeholder="Contoh: SJ-MSK/001/2025" required>
                    <p class="text-xs text-gray-500 mt-2">Masukkan nomor dokumen surat jalan pengembalian barang.</p>
                </div>
                <div>
                    <label class="form-label">Tanggal Masuk</label>
                    <input type="date" name="tanggal_masuk" class="form-input"
                           value="{{ now()->toDateString() }}" required>
                </div>
            </div>

            <h3 class="text-lg font-semibold text-gray-800 mb-4">Detail Material</h3>
            <div class="overflow-x-auto rounded-xl border border-gray-100 mb-6">
                <table class="table-purity w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider w-12">No</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Material</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider w-24">Keluar</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider w-24">Masuk</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider w-24">Sisa</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider w-24">Satuan</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach ($surat->details as $i => $item)
                            @php
                                $keluar = $item->quantity;
                                $masuk = $item->jumlah_kembali ?? 0;
                                $sisa = $keluar - $masuk;
                            @endphp
                            <tr>
                                <td class="px-4 py-3 text-center text-gray-600">{{ $i + 1 }}</td>
                                <td class="px-4 py-3">
                                    <input type="text" class="form-input text-sm bg-gray-50" 
                                           value="{{ $item->material->material_description ?? $item->nama_barang_manual }}" readonly>
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" class="form-input text-sm text-center font-semibold bg-gray-50 text-blue-600"
                                           value="{{ $keluar }}" readonly>
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" class="form-input text-sm text-center font-semibold bg-gray-50 text-green-600"
                                           value="{{ $masuk }}" readonly>
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" class="form-input text-sm text-center font-semibold bg-gray-50 text-red-600"
                                           value="{{ $sisa }}" readonly>
                                </td>
                                <td class="px-4 py-3">
                                    <input type="text" class="form-input text-sm text-center bg-gray-50"
                                           value="{{ $item->satuan ?? 'Unit' }}" readonly>
                                </td>
                                <td class="px-4 py-3">
                                    <input type="text" name="keterangan" class="form-input text-sm" placeholder="Keterangan">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($detail->histories && $detail->histories->count() > 0)
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Riwayat Pengembalian Sebelumnya</h3>
                    <div class="overflow-x-auto rounded-xl border border-gray-100">
                        <table class="table-purity w-full text-sm">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider w-12">No</th>
                                    <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Nomor Surat Jalan Masuk</th>
                                    <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider w-40">Tanggal Masuk</th>
                                    <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider w-40">Jumlah Kembali</th>
                                    <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @foreach($detail->histories as $i => $h)
                                    <tr>
                                        <td class="px-4 py-3 text-center text-gray-600">{{ $i + 1 }}</td>
                                        <td class="px-4 py-3 text-gray-700">{{ $h->nomor_surat_masuk }}</td>
                                        <td class="px-4 py-3 text-gray-700">{{ \Carbon\Carbon::parse($h->tanggal_masuk)->format('d/m/Y') }}</td>
                                        <td class="px-4 py-3 text-center font-semibold text-green-600">{{ $h->jumlah_kembali }}</td>
                                        <td class="px-4 py-3 text-gray-700">{{ $h->keterangan ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            @if(in_array($surat->jenis_surat_jalan, ['Garansi', 'Perbaikan', 'Rusak']) && !$detail->is_manual)
                <div class="mb-6">
                    <label class="form-label">Pilih Serial Number yang Dikembalikan</label>
                    <select name="serials[]" class="form-input" multiple size="6" required>
                        @forelse($availableSerials ?? [] as $sn)
                            <option value="{{ $sn }}">{{ $sn }}</option>
                        @empty
                            <option value="">Serial tidak tersedia</option>
                        @endforelse
                    </select>
                    <p class="text-xs text-gray-500 mt-2">Pilih serial satu per satu. Jumlah kembali akan mengikuti jumlah serial terpilih.</p>
                </div>
            @else
                <div class="mb-6">
                    <label class="form-label">Jumlah yang Dikembalikan Sekarang</label>
                    <input type="number" name="jumlah_kembali" class="form-input"
                           min="1" max="{{ $detail->quantity - ($detail->jumlah_kembali ?? 0) }}"
                           value="{{ $detail->quantity - ($detail->jumlah_kembali ?? 0) }}" required>
                </div>
            @endif

            <div class="flex justify-end gap-3">
                <a href="{{ url()->previous() }}"
                    class="px-6 py-2 rounded-xl border border-gray-300 text-gray-600 hover:bg-gray-100 transition-colors">
                    Batal
                </a>
                <button type="submit" class="btn-teal px-6 py-2 shadow-lg shadow-teal-500/20 hover:shadow-teal-500/40 transition-all duration-300">
                    Simpan
                </button>
            </div>
        </form>
        @endif

        @if ($detail->pengembalianHistories->count() > 0)
        <div class="mt-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Riwayat Pengembalian Barang</h3>
            <div class="overflow-x-auto rounded-xl border border-gray-100">
                <table class="table-purity w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider w-12">No</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Nomor Surat Masuk</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider w-40">Tanggal Masuk</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider w-40">Jumlah Kembali</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach ($detail->pengembalianHistories as $i => $history)
                        <tr>
                            <td class="px-4 py-3 text-center text-gray-600">{{ $i + 1 }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $history->nomor_surat_masuk }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ \Carbon\Carbon::parse($history->tanggal_masuk)->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-center font-semibold text-green-600">{{ $history->jumlah_kembali }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $history->keterangan ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    .table-purity input[readonly] {
        background-color: #f8fafc;
    }
</style>
@endpush
