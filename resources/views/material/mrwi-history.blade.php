@extends('layouts.app')

@section('title', 'Histori Material MRWI')

@section('page-title', 'Histori Material MRWI')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Histori Material MRWI</h2>
            <p class="text-gray-500 text-sm mt-1">Cari histori material berdasarkan serial number.</p>
        </div>
    </div>

    <div class="card p-6 border border-gray-100 shadow-xl shadow-gray-200/50">
        <form method="GET" action="{{ route('material-mrwi.history') }}" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <label for="serial" class="form-label">Serial Number</label>
                <input type="text" name="serial" id="serial" class="form-input" value="{{ $serial }}" required
                       placeholder="Masukkan serial number">
            </div>
            <div class="flex items-end">
                <button type="submit" class="btn-teal px-6 py-3">Cari</button>
            </div>
        </form>
    </div>

    @if($serial !== '')
        @if($notFound)
            <div class="p-4 bg-red-50 border border-red-200 rounded-xl text-red-700">
                Serial number <strong>{{ $serial }}</strong> tidak ditemukan.
            </div>
        @else
            <div class="card p-6 border border-gray-100 shadow-xl shadow-gray-200/50">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Ringkasan</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div class="flex items-center justify-between rounded-lg bg-gray-50 border border-gray-100 px-4 py-3">
                        <span class="text-gray-500 font-semibold">Serial</span>
                        <span class="text-gray-800 font-semibold">{{ $serial }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-lg bg-gray-50 border border-gray-100 px-4 py-3">
                        <span class="text-gray-500 font-semibold">Material</span>
                        <span class="text-gray-800 font-semibold">
                            {{ $material->material_description ?? '-' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between rounded-lg bg-gray-50 border border-gray-100 px-4 py-3">
                        <span class="text-gray-500 font-semibold">Status Terakhir</span>
                        <span class="text-gray-800 font-semibold">
                            {{ ucfirst($latest->status_bucket ?? '-') }}
                        </span>
                    </div>
                </div>
                <div class="mt-4 text-sm text-gray-600">
                    Terakhir update:
                    <strong>
                        {{ $latest && $latest->tanggal ? \Carbon\Carbon::parse($latest->tanggal)->format('d/m/Y') : '-' }}
                    </strong>
                    ({{ $latest->jenis ?? '-' }})
                </div>
            </div>

            <div class="card p-6 border border-gray-100 shadow-xl shadow-gray-200/50">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Timeline</h3>
                <div class="overflow-x-auto rounded-xl border border-gray-100">
                    <table class="table-purity w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider w-12">No</th>
                                <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider w-40">Tanggal</th>
                                <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider w-32">Jenis</th>
                                <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider w-32">Status</th>
                                <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Referensi</th>
                                <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach($history as $i => $move)
                                <tr>
                                    <td class="px-4 py-3 text-center text-gray-600">{{ $i + 1 }}</td>
                                    <td class="px-4 py-3 text-gray-700">
                                        {{ $move->tanggal ? \Carbon\Carbon::parse($move->tanggal)->format('d/m/Y') : '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-700">{{ ucfirst($move->jenis) }}</td>
                                    <td class="px-4 py-3 text-gray-700">{{ ucfirst($move->status_bucket) }}</td>
                                    <td class="px-4 py-3 text-gray-700">
                                        @if($move->reference_number)
                                            {{ $move->reference_number }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-gray-700">{{ $move->keterangan ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    @endif
</div>
@endsection
