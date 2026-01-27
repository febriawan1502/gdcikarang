<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Jalan - {{ $suratJalan->nomor_surat }}</title>

    <style>
        @page {
            margin: 20mm 15mm 20mm 15mm;
            size: A4;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            line-height: 1.2;
            margin: 0;
            padding: 0;
        }
        .stamp-checked {
    display: inline-block;

    color: #c00000;
    font-weight: 700;
    font-size: 16px;
    letter-spacing: 3px;
    text-transform: uppercase;

    transform: rotate(-12deg);
    opacity: 0.9;

    line-height: 1;
}



        /* Wrapper untuk 3 copy */
        .copy {
            page-break-after: always;
        }

        /* Copy terakhir jangan buat halaman kosong */
        .copy:last-child {
            page-break-after: auto !important;
        }

        .page-header {
            text-align: left;
            font-size: 10px;
            font-weight: bold;
            border-bottom: 1px solid #333;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }

        .page-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            font-weight: bold;
            border-top: 1px solid #333;
            padding-top: 5px;
            background: white;
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(45deg);
            font-size: 72px;
            font-weight: bold;
            color: rgba(255, 0, 0, 0.15);
            z-index: -1;
            pointer-events: none;
            white-space: nowrap;
        }

        .header {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 15px;
            text-transform: uppercase;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .info-table td {
            padding: 5px;
            border: 1px solid #333;
            font-size: 10px;
        }

        .info-label {
            font-weight: bold;
            background-color: #f0f0f0;
            width: 25%;
        }

        .material-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }

        .material-table th,
        .material-table td {
            border: 1px solid #333;
            padding: 5px;
            font-size: 9px;
        }

        .material-table th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }

        .material-table td:first-child {
            text-align: center;
            width: 40px;
        }

        .material-table td:nth-child(3),
        .material-table td:nth-child(4) {
            text-align: center;
            width: 80px;
        }

        .disclaimer {
            text-align: center;
            font-weight: bold;
            background-color: #f0f0f0;
            padding: 8px;
            margin: 15px 0;
            border: 1px solid #333;
            font-size: 10px;
        }

        .signature-section {
            margin-top: 30px;
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
        }

        .signature-table td {
            border: 1px solid #333;
            padding: 5px;
            text-align: center;
            font-size: 9px;
        }

        .signature-space td {
            height: 60px;
        }

        .page-break {
            page-break-after: always;
        }
        .copy-label {
            position: fixed;
            top: -10mm;          /* sejajar header */
            right: -10mm;        /* PAS di batas margin kanan */
            font-size: 11px;
            font-weight: bold;
            border: 1px solid #333;
            padding: 4px 10px;
            background: #fff;
            z-index: 999;
        }
    </style>
</head>

<body>

@for($copy = 1; $copy <= 3; $copy++)
<div class="copy">
    @if($copy == 1)
    <div class="copy-label">UNTUK PEMINTA</div>
@elseif($copy == 2)
    <div class="copy-label">ARSIP GUDANG</div>
@else
    <div class="copy-label">ARSIP SATPAM</div>
@endif

<div class="watermark">{{ strtoupper($suratJalan->status) }}</div>

@php
    $materialsPerPage = 18;
    $materialsPerAdditionalPage = 24;
    $totalMaterials = $suratJalan->details->count();
    $materialIndex = 0;
@endphp

@for($pageNum = 1; $pageNum <= $totalPages; $pageNum++)

    <!-- Page Header -->
    <div class="page-header">
        {{ strtoupper(\App\Models\Setting::get('company_name', 'PT PLN (PERSERO)')) }}<br>
        UID JAWA BARAT<br>
        {{ strtoupper(auth()->user()->unit->name ?? \App\Models\Setting::get('up3_name', 'UP3 Cimahi')) }}
    </div>

    @if($pageNum == 1)
        <div class="header">SURAT JALAN</div>

        <table class="info-table">
            <tr>
                <td class="info-label">Nomor Surat</td>
                <td colspan="3">{{ $suratJalan->nomor_surat }}</td>
            </tr>
            <tr>
                <td class="info-label">Tanggal</td>
                <td>{{ $suratJalan->tanggal->format('d/m/Y') }}</td>
                <td class="info-label">Jenis Surat Jalan</td>
                <td>{{ $suratJalan->jenis_surat_jalan ?? 'Normal' }}</td>
            </tr>
            <tr>
                <td class="info-label">Berdasarkan</td>
                <td>{{ $suratJalan->berdasarkan }}</td>
                <td class="info-label">Nomor Slip SAP</td>
                <td>{{ $suratJalan->nomor_slip ?? '-' }}</td>
            </tr>
            <tr>
                <td class="info-label">Diberikan Kepada</td>
                <td>{{ $suratJalan->kepada }}</td>
                <td class="info-label">Untuk Pekerjaan</td>
                <td>{{ $suratJalan->keterangan ?? '-' }}</td>
            </tr>
        </table>

        @php $materialsToShow = min($materialsPerPage, $totalMaterials); @endphp

    @else
        <div class="header">SURAT JALAN (Lanjutan)</div>

        @php
            $remaining = $totalMaterials - $materialIndex;
            $materialsToShow = min($materialsPerAdditionalPage, $remaining);
        @endphp
    @endif

    <!-- Material Table -->
    <table class="material-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Material</th>
                <th>Quantity</th>
                <th>Satuan</th>
                <th>No Rak</th>
                <th>Checklist</th>
            </tr>
        </thead>

        <tbody>
            @for($i = 0; $i < $materialsToShow; $i++)
                @php $detail = $suratJalan->details[$materialIndex]; @endphp
                <tr>
                    <td>{{ $materialIndex + 1 }}</td>

                    @if($detail->is_manual)
                        <td>{{ $detail->nama_barang_manual ?? '-' }}</td>
                    @else
                        <td>{{ $detail->material->material_code ?? '-' }} - {{ $detail->material->material_description ?? '-' }}</td>
                    @endif

                    <td>{{ $detail->quantity }}</td>
                    <td>{{ $detail->satuan_manual ?? $detail->satuan ?? '-' }}</td>
                    <td>{{ $detail->material->rak ?? '-' }}</td>
                    <td></td>
                </tr>
                @php $materialIndex++; @endphp
            @endfor
        </tbody>
    </table>

    @if($pageNum == $totalPages)
        <table class="info-table">
            <tr>
                <td class="info-label">Dibuat oleh</td>
                <td>{{ optional($suratJalan->creator)->name ?? '-' }}</td>
                <td class="info-label">Disetujui oleh</td>
                <td>{{ $suratJalan->approver->nama ?? '-' }}</td>
            </tr>
            <tr>
                <td class="info-label">Tanggal Dibuat</td>
                <td>{{ $suratJalan->created_at->format('d/m/Y H:i') }}</td>
                <td class="info-label">Tanggal Disetujui</td>
                <td>{{ $suratJalan->approved_at ? $suratJalan->approved_at->format('d/m/Y H:i') : '-' }}</td>
            </tr>
        </table>

        <table class="info-table">
            <tr>
                <td class="info-label">Kendaraan</td>
                <td class="info-label">No. Polisi</td>
                <td class="info-label">Pengemudi</td>
            </tr>
            <tr>
                <td>{{ $suratJalan->kendaraan ?? '' }}</td>
                <td>{{ $suratJalan->no_polisi ?? '' }}</td>
                <td>{{ $suratJalan->pengemudi ?? '' }}</td>
            </tr>
        </table>

        <div class="disclaimer">
            SEMUA RESIKO SETELAH BARANG KELUAR GUDANG<br>
            MENJADI TANGGUNG JAWAB PENGAMBIL BARANG
        </div>
    @endif

    <!-- Signature -->
<!-- Signature -->
<div class="signature-section">
    <table class="signature-table">
        <tr>
            <td><strong>Yang menerima</strong></td>
            <td><strong>Security</strong></td>
            <td><strong>Admin Gudang</strong></td>
        </tr>

        {{-- BARIS STEMPEL --}}
        <tr class="signature-space">
            <td></td>
            <td style="text-align:center; vertical-align:middle;">
                @if($suratJalan->security_checked_at)
                    <div class="stamp-checked">CHECKED</div>
                @endif
            </td>
            <td></td>
        </tr>

        {{-- BARIS NAMA --}}
        <tr>
            <td>{{ $suratJalan->nama_penerima ?? $suratJalan->kepada }}</td>
            <td>{{ $suratJalan->security ?? '-' }}</td>
            <td>{{ Auth::user()->nama }}<br>NIP. {{ Auth::user()->nip ?? '-' }}</td>
        </tr>
    </table>
</div>


    <div class="page-footer">
        Halaman {{ $pageNum }} dari {{ $totalPages }}
    </div>

    @if($pageNum < $totalPages)
        <div class="page-break"></div>
    @endif

@endfor <!-- page loop -->

</div>
@endfor <!-- copy loop -->

</body>
</html>
