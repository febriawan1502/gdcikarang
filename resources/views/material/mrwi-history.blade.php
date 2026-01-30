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
        <form id="mrwiHistoryForm" method="GET" action="{{ route('material-mrwi.history') }}" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <label for="serial" class="form-label">Serial Number</label>
                <input type="text" name="serial" id="serial" class="form-input" value="{{ $serial }}" required
                       placeholder="Masukkan serial number">
            </div>
            <div class="flex items-end">
                <button id="mrwiSearchBtn" type="submit" class="btn-teal px-6 py-3">Cari</button>
            </div>
        </form>
    </div>

    <div id="mrwiNotFound" class="p-4 bg-red-50 border border-red-200 rounded-xl text-red-700" style="{{ $serial !== '' && $notFound ? '' : 'display:none;' }}">
        Serial number <strong>{{ $serial }}</strong> tidak ditemukan.
    </div>

    <div id="mrwiResult" class="space-y-6" style="{{ $serial !== '' && !$notFound ? '' : 'display:none;' }}">
            <div class="card p-6 border border-gray-100 shadow-xl shadow-gray-200/50">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Ringkasan</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div class="flex items-center justify-between rounded-lg bg-gray-50 border border-gray-100 px-4 py-3">
                        <span class="text-gray-500 font-semibold">Serial</span>
                        <span id="mrwiSummarySerial" class="text-gray-800 font-semibold">{{ $serial !== '' ? $serial : '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-lg bg-gray-50 border border-gray-100 px-4 py-3">
                        <span class="text-gray-500 font-semibold">Material</span>
                        <span id="mrwiSummaryMaterial" class="text-gray-800 font-semibold">
                            {{ $material->material_description ?? '-' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between rounded-lg bg-gray-50 border border-gray-100 px-4 py-3">
                        <span class="text-gray-500 font-semibold">Status Terakhir</span>
                        <span id="mrwiSummaryStatus" class="text-gray-800 font-semibold">
                            {{ ucfirst($latest->status_bucket ?? '-') }}
                        </span>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm mt-4">
                    <div class="flex items-center justify-between rounded-lg bg-gray-50 border border-gray-100 px-4 py-3">
                        <span class="text-gray-500 font-semibold">Merk</span>
                        <span id="mrwiDetailMerk" class="text-gray-800 font-semibold">{{ $mrwiDetail->nama_pabrikan ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-lg bg-gray-50 border border-gray-100 px-4 py-3">
                        <span class="text-gray-500 font-semibold">SN Pabrikan</span>
                        <span id="mrwiDetailSnPabrikan" class="text-gray-800 font-semibold">{{ $mrwiDetail->id_pelanggan ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-lg bg-gray-50 border border-gray-100 px-4 py-3">
                        <span class="text-gray-500 font-semibold">Tahun Produksi</span>
                        <span id="mrwiDetailTahun" class="text-gray-800 font-semibold">{{ $mrwiDetail->tahun_buat ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-lg bg-gray-50 border border-gray-100 px-4 py-3">
                        <span class="text-gray-500 font-semibold">Jenis Kerusakan</span>
                        <span id="mrwiDetailKerusakan" class="text-gray-800 font-semibold">{{ $mrwiDetail->mrwi->kategori_kerusakan ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-lg bg-gray-50 border border-gray-100 px-4 py-3">
                        <span class="text-gray-500 font-semibold">ULP Pengirim</span>
                        <span id="mrwiDetailUlp" class="text-gray-800 font-semibold">{{ $mrwiDetail->mrwi->ulp_pengirim ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-lg bg-gray-50 border border-gray-100 px-4 py-3">
                        <span class="text-gray-500 font-semibold">Vendor Pengirim</span>
                        <span id="mrwiDetailVendor" class="text-gray-800 font-semibold">{{ $mrwiDetail->mrwi->vendor_pengirim ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-lg bg-gray-50 border border-gray-100 px-4 py-3">
                        <span class="text-gray-500 font-semibold">Ex Gardu</span>
                        <span id="mrwiDetailExGardu" class="text-gray-800 font-semibold">{{ $mrwiDetail->mrwi->ex_gardu ?? '-' }}</span>
                    </div>
                </div>
                <div class="mt-4 text-sm text-gray-600">
                    Terakhir update:
                    <strong id="mrwiSummaryLastUpdate">
                        {{ $latest && $latest->tanggal ? \Carbon\Carbon::parse($latest->tanggal)->format('d/m/Y') : '-' }}
                    </strong>
                    (<span id="mrwiSummaryLastType">{{ $latest->jenis ?? '-' }}</span>)
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
                        <tbody id="mrwiTimelineBody" class="divide-y divide-gray-100 bg-white">
                            @foreach($timeline as $i => $row)
                                <tr>
                                    <td class="px-4 py-3 text-center text-gray-600">{{ $i + 1 }}</td>
                                    <td class="px-4 py-3 text-gray-700">
                                        {{ $row['tanggal'] ? \Carbon\Carbon::parse($row['tanggal'])->format('d/m/Y H:i') : '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-700">{{ $row['jenis'] }}</td>
                                    <td class="px-4 py-3 text-gray-700">{{ $row['status'] }}</td>
                                    <td class="px-4 py-3 text-gray-700">
                                        {{ $row['referensi'] }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-700">{{ $row['keterangan'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function() {
        const form = document.getElementById('mrwiHistoryForm');
        const input = document.getElementById('serial');
        const btn = document.getElementById('mrwiSearchBtn');

        if (!form || !input || !btn) return;

        const notFoundEl = document.getElementById('mrwiNotFound');
        const resultEl = document.getElementById('mrwiResult');

        const setText = (id, value) => {
            const el = document.getElementById(id);
            if (el) el.textContent = value ?? '-';
        };

        const setHtml = (id, value) => {
            const el = document.getElementById(id);
            if (el) el.innerHTML = value ?? '';
        };

        const renderTimeline = (rows) => {
            const body = document.getElementById('mrwiTimelineBody');
            if (!body) return;
            if (!rows || rows.length === 0) {
                body.innerHTML = '<tr><td colspan="6" class="px-4 py-3 text-center text-gray-500">Belum ada histori</td></tr>';
                return;
            }
            const html = rows.map((row, i) => {
                const tanggal = row.tanggal ? new Date(row.tanggal) : null;
                const tanggalText = tanggal
                    ? tanggal.toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' })
                    : '-';
                return `
                    <tr>
                        <td class="px-4 py-3 text-center text-gray-600">${i + 1}</td>
                        <td class="px-4 py-3 text-gray-700">${tanggalText}</td>
                        <td class="px-4 py-3 text-gray-700">${row.jenis ?? '-'}</td>
                        <td class="px-4 py-3 text-gray-700">${row.status ?? '-'}</td>
                        <td class="px-4 py-3 text-gray-700">${row.referensi ?? '-'}</td>
                        <td class="px-4 py-3 text-gray-700">${row.keterangan ?? '-'}</td>
                    </tr>
                `;
            }).join('');
            body.innerHTML = html;
        };

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const serial = input.value.trim();
            if (!serial) return;

            btn.disabled = true;
            const original = btn.textContent;
            btn.textContent = 'Mencari...';

            fetch(`/api/mrwi/history?serial=${encodeURIComponent(serial)}`)
                .then(async (res) => {
                    const data = await res.json();
                    if (!res.ok) {
                        throw data;
                    }
                    return data;
                })
                .then((res) => {
                    const data = res.data || {};
                    if (notFoundEl) notFoundEl.style.display = 'none';
                    if (resultEl) resultEl.style.display = '';

                    setText('mrwiSummarySerial', data.serial ?? serial);
                    setText('mrwiSummaryMaterial', data.summary?.material ?? '-');
                    setText('mrwiSummaryStatus', data.summary?.status ?? '-');
                    setText('mrwiSummaryLastUpdate', data.summary?.last_update ?? '-');
                    setText('mrwiSummaryLastType', data.summary?.last_update_type ?? '-');

                    setText('mrwiDetailMerk', data.details?.merk ?? '-');
                    setText('mrwiDetailSnPabrikan', data.details?.sn_pabrikan ?? '-');
                    setText('mrwiDetailTahun', data.details?.tahun ?? '-');
                    setText('mrwiDetailKerusakan', data.details?.jenis_kerusakan ?? '-');
                    setText('mrwiDetailUlp', data.details?.ulp_pengirim ?? '-');
                    setText('mrwiDetailVendor', data.details?.vendor_pengirim ?? '-');
                    setText('mrwiDetailExGardu', data.details?.ex_gardu ?? '-');

                    renderTimeline(data.timeline || []);
                })
                .catch((err) => {
                    if (resultEl) resultEl.style.display = 'none';
                    if (notFoundEl) {
                        setHtml('mrwiNotFound', `Serial number <strong>${serial}</strong> tidak ditemukan.`);
                        notFoundEl.style.display = '';
                    }
                })
                .finally(() => {
                    btn.disabled = false;
                    btn.textContent = original;
                });
        });
    })();
</script>
@endpush
