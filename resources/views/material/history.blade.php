@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
        a.disabled {
            pointer-events: none;
            opacity: 0.5;
        }

        .ui-autocomplete {
            z-index: 9999 !important;
            max-height: 300px;
            overflow-y: auto;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .ui-menu-item {
            padding: 10px 12px;
            cursor: pointer;
        }

        .ui-menu-item:hover,
        .ui-state-active {
            background: #f0fdfa !important;
            color: #0d9488 !important;
            border: none !important;
        }
    </style>
@endpush

@section('content')
    @php
        $materialId = $material->id ?? null;
    @endphp
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">📊 Daftar Transaksi</h2>
                <p class="text-gray-500 text-sm mt-1">Riwayat transaksi material masuk dan keluar.</p>
            </div>

            <!-- Export Buttons -->
            <div class="flex gap-2">
                <a href="{{ $materialId ? route('material.history.export', ['id' => $materialId]) : route('material.history.export') }}"
                    class="px-4 py-2 rounded-xl bg-green-500 text-white hover:bg-green-600 transition-colors flex items-center gap-2">
                    <i class="fas fa-file-excel"></i>
                    <span>Export Excel</span>
                </a>

                <a href="#" id="exportPdfBtn" target="_blank"
                    class="px-4 py-2 rounded-xl bg-red-500 text-white hover:bg-red-600 transition-colors flex items-center gap-2 {{ $materialId ? '' : 'disabled opacity-50 cursor-not-allowed' }}"
                    {{ $materialId ? 'href=' . route('material.history.export-pdf', ['id' => $materialId]) : 'onclick=return false;' }}>
                    <i class="fas fa-file-pdf"></i>
                    <span>Export PDF</span>
                </a>
            </div>
        </div>

        <!-- Content Card -->
        <div class="card p-6 border border-gray-100 shadow-xl shadow-gray-200/50">
            <!-- Filter Section -->
            <form id="filterForm" class="mb-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div class="md:col-span-2">
                        <label for="searchMaterial" class="form-label">Cari Material</label>
                        <input type="text" id="searchMaterial" class="form-input" placeholder="Ketik nama material...">
                    </div>

                    <input type="hidden" id="materialId">

                    <div>
                        <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
                        <input type="date" id="tanggal_awal" class="form-input">
                    </div>

                    <div>
                        <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                        <input type="date" id="tanggal_akhir" class="form-input">
                    </div>

                    <div>
                        <label for="tipe" class="form-label">Jenis Transaksi</label>
                        <select name="tipe" id="tipe" class="form-input">
                            <option value="">Semua Tipe</option>
                            <option value="MASUK">MASUK</option>
                            <option value="KELUAR">KELUAR</option>
                        </select>
                    </div>
                </div>
            </form>

            <!-- Table -->
            <div class="overflow-x-auto rounded-xl border border-gray-100">
                <table id="materialHistoryTable" class="table-purity w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Tipe</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Nomor Material
                            </th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Material</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Masuk</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Keluar</th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Sisa Persediaan
                            </th>
                            <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Catatan</th>
                        </tr>
                    </thead>

                    <tbody id="dataTableBody" class="divide-y divide-gray-100 bg-white">
                        @foreach ($histories as $h)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 text-gray-600">
                                    {{ $h->tanggal ? \Carbon\Carbon::parse($h->tanggal)->format('Y-m-d') : '-' }}</td>

                                <td class="px-4 py-3">
                                    @php $tipe = strtoupper($h->tipe); @endphp
                                    @if ($tipe === 'MASUK' || $tipe === 'IN')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            MASUK
                                        </span>
                                    @elseif($tipe === 'KELUAR' || $tipe === 'OUT')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            KELUAR
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $tipe }}
                                        </span>
                                    @endif
                                </td>

                                <td class="px-4 py-3 text-gray-600">
                                    {{ isset($h->material->material_code) ? ltrim($h->material->material_code, '0') : '-' }}
                                </td>
                                <td class="px-4 py-3 text-gray-800">
                                    {{ optional($h->material)->material_description ?? '-' }}</td>
                                <td class="px-4 py-3 font-semibold text-green-600">{{ number_format($h->masuk ?? 0) }}</td>
                                <td class="px-4 py-3 font-semibold text-red-600">{{ number_format($h->keluar ?? 0) }}</td>
                                <td class="px-4 py-3 font-semibold text-blue-600">
                                    {{ number_format($h->sisa_persediaan ?? 0) }}</td>
                                <td class="px-4 py-3 text-gray-500">{{ $h->catatan ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($histories->isEmpty())
                <div class="px-4 py-8 text-center text-gray-400">
                    <i class="fas fa-inbox text-4xl mb-2"></i>
                    <p>Tidak ada data histori</p>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

        <script>
            $(document).ready(function() {

                // INIT DATATABLE
                const table = $('#materialHistoryTable').DataTable({
                    pageLength: 10,
                    ordering: false,
                    lengthChange: true,
                    searching: true,
                    info: true,
                    paging: true,
                    language: {
                        search: "🔍 Cari:",
                        lengthMenu: "Tampilkan _MENU_ data per halaman",
                        info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                        paginate: {
                            previous: "Sebelumnya",
                            next: "Berikutnya"
                        },
                        zeroRecords: "Tidak ada data ditemukan",
                        emptyTable: "Tidak ada data histori"
                    }
                });

                // AUTOCOMPLETE MATERIAL
                $("#searchMaterial").autocomplete({
                    minLength: 1,
                    source: function(request, response) {
                        $.get("{{ route('material.autocomplete') }}", {
                            q: request.term
                        }, function(data) {
                            response($.map(data, function(item) {
                                return {
                                    label: `${item.material_code} - ${item.material_description}`,
                                    value: `${item.material_code} - ${item.material_description}`,
                                    id: item.id,
                                    description: item.material_description
                                };
                            }));
                        });
                    },
                    select: function(event, ui) {

                        // isi input
                        $("#materialId").val(ui.item.id);
                        $("#searchMaterial").val(ui.item.value);

                        // filter tabel (kolom material index = 3)
                        table.column(3).search(ui.item.description).draw();

                        // AKTIFKAN EXPORT PDF
                        const exportPdfBtn = document.getElementById('exportPdfBtn');
                        exportPdfBtn.classList.remove('disabled', 'opacity-50', 'cursor-not-allowed');
                        exportPdfBtn.setAttribute(
                            'href',
                            `/materials/${ui.item.id}/history/export-pdf`
                        );
                        exportPdfBtn.onclick = null;

                        return false;
                    }
                });

                // RESET SAAT INPUT DIKOSONGKAN
                $("#searchMaterial").on('keyup', function() {
                    if (!this.value) {

                        // reset tabel
                        table.column(3).search('').draw();

                        // MATIKAN EXPORT PDF
                        const exportPdfBtn = document.getElementById('exportPdfBtn');
                        exportPdfBtn.classList.add('disabled', 'opacity-50', 'cursor-not-allowed');
                        exportPdfBtn.removeAttribute('href');
                    }
                });

                function parseDateInput(value) {
                    if (!value) {
                        return null;
                    }
                    return new Date(`${value}T00:00:00`);
                }

                $.fn.dataTable.ext.search.push(function(settings, data) {
                    if (settings.nTable.id !== 'materialHistoryTable') {
                        return true;
                    }

                    const startVal = $('#tanggal_awal').val();
                    const endVal = $('#tanggal_akhir').val();
                    const rowDateStr = data[0] || '';

                    if (!rowDateStr) {
                        return true;
                    }

                    const rowDate = new Date(`${rowDateStr}T00:00:00`);
                    const startDate = parseDateInput(startVal);
                    const endDate = parseDateInput(endVal);

                    if (startDate && rowDate < startDate) {
                        return false;
                    }
                    if (endDate && rowDate > endDate) {
                        return false;
                    }

                    return true;
                });

                $('#tanggal_awal, #tanggal_akhir').on('change', function() {
                    table.draw();
                });

                // FILTER TIPE
                $('#tipe').on('change', function() {
                    const value = $(this).val();
                    table.column(1).search(value).draw();
                });

            });
        </script>
    @endpush
@endsection
