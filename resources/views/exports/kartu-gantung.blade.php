<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    /* Pengaturan Kertas A4 */
    @page { 
        size: A4; 
        margin: 0; 
    }

    html, body {
        margin: 0;
        padding: 0;
        width: 210mm;
        overflow: hidden;
        background: #ff75a0; /* Latar belakang putih agar bersih saat print */
    }

    /* CONTAINER UTAMA: Ukuran dikecilkan agar ada margin aman di kiri-kanan */
    .card {
        width: 190mm; /* Ruang aman agar tidak kepotong kanan */
        
        margin: 5mm auto; /* Memposisikan kartu di tengah kertas A4 */
        background: #ff75a0;
        padding: 10mm;
        box-sizing: border-box;
        /* border: 1px solid #000; */
        position: relative;
        font-family: Arial, Helvetica, sans-serif;
    }

    /* Header */
    .master-header {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .header-left { font-size: 11px; font-weight: bold; line-height: 1.3; }
    .header-right { text-align: right; font-size: 12px; font-weight: bold; }

    .box-lokasi {
        border: 1px solid #000;
        width: 30mm; height: 9mm;
        background: #ff75a0;
        display: inline-block;
        margin-top: 2px;
    }
    

    /* Area Normalisasi (Miring) */
    .norm-section {
        height: 22mm;
        margin-top: 5mm;
    }

    .norm-rotate {
        transform: rotate(-6deg);
        display: inline-block;
        transform-origin: left top;
    }

    .norm-table { border-collapse: collapse; }
    .norm-table td {
        border: 1.5px solid #000;
        width: 7.5mm; height: 8.5mm;
        text-align: center; font-weight: bold; font-size: 16px;
        background: rgba(255,255,255,0.3);
    }

    /* Judul */
    .title {
        text-align: center;
        font-size: 20px;
        font-weight: bold;
        margin: 5mm 0;
        text-transform: uppercase;
    }
    .lokasi-wrap {
    display: inline-block;
    text-align: center;
    margin-top: 4px;
    }

    .lokasi-title {
        font-size: 12px;
        font-weight: bold;
        text-align: right;
        margin-bottom: 2px;
    }

    .lokasi-table {
        border-collapse: collapse;
        margin: 0 auto;
    }

    .lokasi-table td {
        border: 1.5px solid #000;
        background: #ff75a0;
        text-align: center;
        font-weight: bold;
    }

    .lokasi-header td {
        height: 7mm;
        font-size: 11px;
    }

    .lokasi-boxes td {
        width: 7mm;
        height: 7mm;
    }
    /* Info Input Baris */
    .info-row {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 8px;
    }

    .info-label { font-size: 13px; font-weight: bold; white-space: nowrap; width: 1%; padding-right: 10px; }
    .info-dotted { border-bottom: 2px dotted #000; font-size: 13px; font-weight: bold; padding-bottom: 2px; }

    /* Tabel Data - KUNCI UTAMA AGAR TIDAK KEPOTONG */
    .data-table {
        width: 100%; /* Akan mengikuti lebar .card (190mm) */
        border-collapse: collapse;
        table-layout: fixed; /* Memaksa kolom tetap di dalam batas lebar */
    }

    .data-table th, .data-table td {
        border: 1px solid #000;
        padding: 6px 2px;
        font-size: 11px;
        text-align: center;
        overflow: hidden; /* Mencegah teks meluber keluar sel */
    }

    .data-table th { background: rgba(0,0,0,0.1); }

    .footer {
        position: absolute;
        bottom: 8mm; left: 10mm;
        font-size: 11px; font-weight: bold;
    }
</style>
</head>
<body>

<div class="card">
    <table class="master-header">
        <tr>
            <td class="header-left">
                PT PLN (PERSERO)<br>
                UID JAWA BARAT<br>
                UP3 CIMAHI
            </td>
            <td class="header-right">
                <div class="lokasi-wrap">
                    <div class="lokasi-title">TUG.2</div>

                    <table class="lokasi-table">
                        <tr class="lokasi-header">
                            <td colspan="4">Lokasi</td>
                        </tr>
                        <tr class="lokasi-boxes">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <div class="norm-section">
        <div class="norm-rotate">
            <table class="norm-table">
                <tr>
                    @php 
                        $materialCode = $material->material_code ?? '00299200';
                        $kode = str_split(str_pad($materialCode, 8, "0", STR_PAD_LEFT)); 
                    @endphp
                    @foreach($kode as $digit)
                        <td>{{ $digit }}</td>
                    @endforeach
                </tr>
            </table>
            <div style="font-size: 10px; font-weight: bold; margin-top: 3px;">Nomor Normalisasi</div>
        </div>
    </div>

    <div class="title">KARTU GANTUNG BARANG</div>

    <table class="info-row">
        <tr>
            <td class="info-label">Nama Barang:</td>
            <td class="info-dotted">{{ $material->material_description ?? '-' }}</td>
        </tr>
    </table>

    <table class="info-row">
        <tr>
            <td class="info-label">Satuan:</td>
            <td class="info-dotted" style="width: 35%;">{{ $material->base_unit_of_measure ?? '-' }}</td>
            <td class="info-label" style="padding-left: 30px;">Kartu No:</td>
            <td class="info-dotted">........</td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th rowspan="2" width="15%">Tgl.</th>
                <th rowspan="2" width="20%">No. Slip</th>
                <th rowspan="2" width="10%">Masuk</th>
                <th rowspan="2" width="10%">Keluar</th>
                <th colspan="3">Sisa Persediaan</th>
                <th rowspan="2">Catatan</th>
            </tr>
            <tr>
                <th width="8%">Rak</th>
                <th width="8%">Peti</th>
                <th width="11%">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @forelse($histories as $h)
            <tr>
                <td>{{ \Carbon\Carbon::parse($h->tanggal)->format('d/m/y') }}</td>
                <td>{{ $h->no_slip ?? '-' }}</td>
                <td>{{ $h->masuk }}</td>
                <td>{{ $h->keluar }}</td>
                <td>{{ $h->rak }}</td>
                <td>{{ $h->peti }}</td>
                <td>{{ $h->sisa_persediaan }}</td>
                <td style="text-align: left;">{{ $h->catatan }}</td>
            </tr>
            @empty
                @for ($i = 0; $i < 15; $i++)
                <tr style="height: 28px;">
                    <td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                </tr>
                @endfor
            @endforelse
        </tbody>
    </table>

    <div class="footer">A5 | TUG 2</div>
</div>

</body>
</html>