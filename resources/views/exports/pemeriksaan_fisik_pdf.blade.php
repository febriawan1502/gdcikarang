<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pemeriksaan Fisik</title>
    <style>
        @page { margin: 20px 25px; }
        body { font-family: Arial, sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; }
        .no-border td { border: none; }
        .center { text-align: center; } .right { text-align: right; } .left { text-align: left; }
        th, td { border: 1px solid #000; padding: 4px; text-align: center; vertical-align: middle; }
        th { font-weight: bold; }
        .ttd td { border: none; padding-top: 30px; text-align: center; }
        hr { border: 1px solid #000; margin: 8px 0 12px; }
    </style>
</head>
<body>

{{-- HEADER --}}
<table class="no-border" style="width:100%;">
    <tr>
        <td style="width:25%;" class="left">
            <strong>PT PLN (PERSERO) UID JAWA BARAT</strong>
        </td>
        <td style="width:50%; text-align:center;">
            <div style="font-weight:bold; font-size:12px;">DAFTAR PEMERIKSAAN BARANG-BARANG / SPAREPART</div>
            <div style="margin-top:4px;">Pada tanggal: <strong>{{ $tanggalCetak }}</strong></div>
            <div style="margin-top:2px;">Gudang: <strong>{{ $gudang }}</strong></div>
        </td>
        <td style="width:25%;" class="right">
            <strong>PLN UID JABAR</strong><br>
            <strong>UP3 / ULP CIMAHI</strong><br>
            <!-- <strong>CIMAHI</strong> -->
        </td>
    </tr>
</table>
<br><br>

{{-- TABEL DATA --}}
<table>
    <thead>
        <tr>
            <th rowspan="2" style="width:5%;">No.</th>
            <th rowspan="2" style="width:5%;">No. Normalisasi</th>
            <th rowspan="2" style="width:20%;">NAMA BARANG / SPARE PART</th>
            <th rowspan="2" style="width:5%;">Satuan</th>
            <th rowspan="2" style="width:8%;">Valuation Type</th>
            <th colspan="3" style="width:18%;">SALDO / JUMLAH</th>
            <th colspan="3" style="width:27%;">PERBEDAAN / SELISIH</th>
            <th colspan="3" style="width:27%;">JUSTIFIKASI SELISIH</th>
        </tr>
        <tr>
            <th style="width:6%;">SAP</th>
            <th style="width:6%;">FISIK</th>
            <th style="width:6%;">SN MIMS</th>

            <th style="width:9%;">SAP-FISIK</th>
            <th style="width:9%;">SAP-SN MIMS</th>
            <th style="width:9%;">FISIK-SN MIMS</th>

            <th style="width:9%;">SAP-FISIK</th>
            <th style="width:9%;">SAP-SN MIMS</th>
            <th style="width:9%;">FISIK-SN MIMS</th>
        </tr>
    </thead>
    <tbody>
        @foreach($materials as $i => $m)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $m->material_code }}</td>
            <td class="left">{{ $m->material_description }}</td>
            <td>{{ $m->base_unit_of_measure }}</td>
            <td>{{ $m->valuation_type }}</td>

            <td>{{ $m->sap ?? '' }}</td>
            <td>{{ $m->fisik ?? $m->unrestricted_use_stock ?? 0 }}</td>
            <td>{{ $m->sn_mims ?? '' }}</td>

            <td>{{ $m->selisih_sf ?? '' }}</td>
            <td>{{ $m->selisih_ss ?? '' }}</td>
            <td>{{ $m->selisih_fs ?? '' }}</td>

            <td>{{ $m->justifikasi_sf ?? '' }}</td>
            <td>{{ $m->justifikasi_ss ?? '' }}</td>
            <td>{{ $m->justifikasi_fs ?? '' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
</body>
</html>
