<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Jalan - {{ $suratJalan->nomor_surat }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 20mm;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        
        .header {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
            text-transform: uppercase;
        }
        
        .info-section {
            margin-bottom: 15px;
        }
        
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        .info-table td {
            padding: 5px;
            border: 1px solid #333;
        }
        
        .info-label {
            font-weight: bold;
            background-color: #f0f0f0;
            width: 25%;
        }
        
        .material-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .material-table th,
        .material-table td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
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
            padding: 10px;
            margin: 20px 0;
            border: 1px solid #333;
        }
        
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 40px;
        }
        
        .signature-table td {
            border: 1px solid #333;
            padding: 10px;
            text-align: center;
            width: 33.33%;
            vertical-align: top;
        }
        
        .signature-space {
            height: 100px !important;
            min-height: 100px !important;
        }
        
        .signature-space td {
            height: 100px !important;
            min-height: 100px !important;
            vertical-align: top !important;
        }
        
        .signature-title {
            font-weight: bold;
            margin-bottom: 60px;
        }
        
        .signature-name {
            border-top: 1px solid #333;
            padding-top: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        SURAT JALAN
    </div>
    
    <table class="info-table">
        <tr>
            <td class="info-label">Nomor Surat</td>
            <td>{{ $suratJalan->nomor_surat }}</td>
            <td class="info-label">Tanggal</td>
            <td>{{ $suratJalan->tanggal->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td class="info-label">Jenis Surat Jalan</td>
            <td>{{ $suratJalan->jenis_surat_jalan ?? 'Normal' }}</td>
            <td class="info-label">Kepada</td>
            <td>{{ $suratJalan->kepada }}</td>
        </tr>
        <tr>
            <td class="info-label">Status</td>
            <td colspan="3">{{ $suratJalan->status }}</td>
        </tr>
        <tr>
            <td class="info-label">Berdasarkan</td>
            <td colspan="3">{{ $suratJalan->berdasarkan }}</td>
        </tr>
        <tr>
            <td class="info-label">Security</td>
            <td colspan="3">{{ $suratJalan->security ?? '-' }}</td>
        </tr>
    </table>
    
    <table class="material-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Material</th>
                <th>Quantity</th>
                <th>Satuan</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($suratJalan->details as $index => $detail)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $detail->material->material_description }}</td>
                <td>{{ $detail->quantity }}</td>
                <td>{{ $detail->satuan }}</td>
                <td>{{ $detail->keterangan ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <table class="info-table">
        <tr>
            <td class="info-label">Keterangan</td>
            <td colspan="3">{{ $suratJalan->keterangan ?? '-' }}</td>
        </tr>
    </table>
    
    <table class="info-table">
        <tr>
            <td class="info-label">Dibuat oleh</td>
            <td>{{ $suratJalan->creator->nama }}</td>
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
            <td>{{ $suratJalan->kendaraan ?? '' }}</td>
        </tr>
        <tr>
            <td class="info-label">No. Polisi</td>
            <td>{{ $suratJalan->no_polisi ?? '' }}</td>
        </tr>
        <tr>
            <td class="info-label">Pengemudi</td>
            <td>{{ $suratJalan->pengemudi ?? '' }}</td>
        </tr>
    </table>
    
    <div class="disclaimer">
        SEMUA RESIKO SETELAH BARANG KELUAR GUDANG<br>
        MENJADI TANGGUNG JAWAB PENGAMBIL BARANG
    </div>
    
    <table class="signature-table">
        <tr>
            <td><strong>Yang menerima</strong></td>
            <td><strong>Security</strong></td>
            <td><strong>Admin Gudang</strong></td>
        </tr>
        <tr class="signature-space">
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>( {{ $suratJalan->kepada ?? '' }} )</td>
            <td>( {{ $suratJalan->security ?? '-' }} )</td>
            <td>NINDYA APRIETA S.<br>NIP. 8609353Z</td>
        </tr>
    </table>
</body>
</html>