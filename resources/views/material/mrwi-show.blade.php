@extends('layouts.app')

@section('title', 'Detail MRWI')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Detail Material MRWI</h2>
                <p class="text-gray-500 text-sm mt-1">Nomor: {{ $mrwi->nomor_mrwi }}</p>
            </div>
            <a href="{{ route('material-mrwi.index') }}"
                class="px-4 py-2 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 transition-all">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        <div class="card p-6 border border-gray-100 shadow-xl shadow-gray-200/50">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                    <div class="text-xs uppercase font-bold text-gray-500 mb-1">Tanggal Masuk</div>
                    <div class="text-gray-800 font-semibold">{{ $mrwi->tanggal_masuk?->format('d/m/Y') }}</div>
                </div>
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                    <div class="text-xs uppercase font-bold text-gray-500 mb-1">Sumber</div>
                    <div class="text-gray-800 font-semibold">{{ $mrwi->sumber ?? '-' }}</div>
                </div>
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                    <div class="text-xs uppercase font-bold text-gray-500 mb-1">Status</div>
                    <div class="text-gray-800 font-semibold">{{ $mrwi->status }}</div>
                </div>
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                    <div class="text-xs uppercase font-bold text-gray-500 mb-1">Berdasarkan</div>
                    <div class="text-gray-800 font-semibold">{{ $mrwi->berdasarkan ?? '-' }}</div>
                </div>
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                    <div class="text-xs uppercase font-bold text-gray-500 mb-1">Lokasi</div>
                    <div class="text-gray-800 font-semibold">{{ $mrwi->lokasi ?? '-' }}</div>
                </div>
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                    <div class="text-xs uppercase font-bold text-gray-500 mb-1">Dibuat Oleh</div>
                    <div class="text-gray-800 font-semibold">{{ $mrwi->creator->nama ?? '-' }}</div>
                </div>
            </div>

            @if ($mrwi->catatan)
                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-xl text-blue-700">
                    <i class="fas fa-info-circle mr-2"></i>{{ $mrwi->catatan }}
                </div>
            @endif

            <div class="overflow-hidden rounded-xl border border-gray-100">
                <table class="table-purity w-full">
                    <thead>
                        <tr class="bg-gray-50 text-left">
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">No Material</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Material</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">QTY</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Satuan</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Serial Number</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">ATTB / Limbah</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Status Anggaran</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">No Asset</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Pabrikan</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Tahun Buat</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">ID Pelanggan</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Klasifikasi</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">No Polis</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach ($mrwi->details as $index => $detail)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $index + 1 }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700 font-mono">{{ $detail->no_material ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $detail->nama_material }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700 font-semibold">{{ $detail->qty }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $detail->satuan }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $detail->serial_number ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $detail->attb_limbah ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $detail->status_anggaran ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $detail->no_asset ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $detail->nama_pabrikan ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $detail->tahun_buat ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $detail->id_pelanggan ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $detail->klasifikasi }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $detail->no_polis ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
