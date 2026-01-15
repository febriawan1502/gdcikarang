@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<style>
    a.disabled {
        pointer-events: none;
        opacity: 0.5;
    }
</style>
@endpush

@section('content')
@php
    $materialId = $material->id ?? null;
@endphp
<div class="container-fluid px-lg-5 px-3 py-4">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body py-4 px-4">

            {{-- 🔝 HEADER & EXPORT --}}
            <div class="d-flex gap-2 mt-3 mt-md-0">
                <a href="{{ $materialId 
                            ? route('material.history.export', ['id' => $materialId]) 
                            : route('material.history.export') }}"
                   class="btn btn-success shadow-sm d-flex align-items-center gap-2 px-3 py-2 rounded-3">
                    <i class="fa fa-file-excel"></i>
                    <span>Export Excel</span>
                </a>

                <a href="#"
                   id="exportPdfBtn"
                    target="_blank"
                   class="btn btn-primary d-flex align-items-center gap-2 px-3 py-2 rounded-3 {{ $materialId ? '' : 'disabled' }}"
                   {{ $materialId ? 'href=' . route('material.history.export-pdf', ['id' => $materialId]) : 'onclick=return false;' }}>
                    <i class="fa fa-file-pdf"></i>
                    <span>Export PDF</span>
                </a>
            </div>
            {{-- 🔍 AUTOCOMPLETE MATERIAL --}}
            <form id="filterForm" class="mb-4">
                <div class="row g-3 align-items-end justify-content-between">
                    <div class="col-lg-8 col-md-7">
                        <label for="searchMaterial" class="form-label text-muted small mb-1">Cari Material</label>
                        <input type="text" id="searchMaterial"
                               class="form-control shadow-sm rounded-3 form-control-sm"
                               placeholder="Ketik nama material...">
                    </div>

                    <input type="hidden" id="materialId">

                    <div class="col-lg-3 col-md-5 text-md-end">
                        <label for="tipe" class="form-label text-muted small mb-1 d-block">Jenis Transaksi</label>
                        <select name="tipe" id="tipe" class="form-select shadow-sm rounded-3 form-select-sm">
                            <option value="">Semua Tipe</option>
                            <option value="MASUK">MASUK</option>
                            <option value="KELUAR">KELUAR</option>
                        </select>
                    </div>
                </div>
            </form>
            
            {{-- 📋 TABLE --}}
            <div class="table-responsive">
                <table id="materialHistoryTable" class="table table-bordered table-striped datatable">


                    <thead class="table-light text-center align-middle">
                        <tr>
                            <th>Tanggal</th>
                            <th>Tipe</th>
                            <th>No Slip</th>
                            <th>Material</th>
                            <th>Masuk</th>
                            <th>Keluar</th>
                            <th>Sisa Persediaan</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>

                    <tbody id="dataTableBody">
                        @forelse ($histories as $h)
                            <tr class="text-center">
                                <td>{{ $h->tanggal ? \Carbon\Carbon::parse($h->tanggal)->format('Y-m-d') : '-' }}</td>

                                <td>
                                    <span class="badge 
                                        @if($h->tipe === 'MASUK') bg-success 
                                        @elseif($h->tipe === 'KELUAR') bg-danger 
                                        @else bg-secondary @endif px-3 py-2">
                                        {{ strtoupper($h->tipe) }}
                                    </span>
                                </td>

                                <td>{{ $h->no_slip ?? '-' }}</td>
                                <td class="text-start">{{ optional($h->material)->material_description ?? '-' }}</td>
                                <td class="text-success fw-semibold">{{ number_format($h->masuk ?? 0) }}</td>
                                <td class="text-danger fw-semibold">{{ number_format($h->keluar ?? 0) }}</td>
                                <td class="fw-semibold text-primary">{{ number_format($h->sisa_persediaan ?? 0) }}</td>
                                <td class="text-start text-muted">{{ $h->catatan ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    ❌ Tidak ada data histori
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
@endpush

@push('scripts')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

<script>
$(document).ready(function () {

    // INIT DATATABLE
    const table = $('#materialHistoryTable').DataTable({
        pageLength: 10,
        ordering: false,
        lengthChange: true,
        searching: true,
        info: true,
        paging: true
    });

    // AUTOCOMPLETE MATERIAL
    $("#searchMaterial").autocomplete({
        minLength: 1,
        source: function (request, response) {
            $.get("{{ route('material.autocomplete') }}", { q: request.term }, response);
        },
        select: function (event, ui) {

            // isi input
            $("#materialId").val(ui.item.id);
            $("#searchMaterial").val(ui.item.value);

            // filter tabel (kolom material index = 3)
            table.column(3).search(ui.item.value).draw();

            // AKTIFKAN EXPORT PDF
            const exportPdfBtn = document.getElementById('exportPdfBtn');
            exportPdfBtn.classList.remove('disabled');
            exportPdfBtn.setAttribute(
                'href',
                `/materials/${ui.item.id}/history/export-pdf`
            );
            exportPdfBtn.onclick = null;

            return false;
        }
    });

    // RESET SAAT INPUT DIKOSONGKAN
    $("#searchMaterial").on('keyup', function () {
        if (!this.value) {

            // reset tabel
            table.column(3).search('').draw();

            // MATIKAN EXPORT PDF
            const exportPdfBtn = document.getElementById('exportPdfBtn');
            exportPdfBtn.classList.add('disabled');
            exportPdfBtn.removeAttribute('href');
        }
    });

});
</script>

@endpush

@endsection
