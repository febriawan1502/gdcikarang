<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Mutasi Material - {{ $material->material_code }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            line-height: 1.2;
            padding: 10mm;
            background: #f5f5f5;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #333;
        }

        .header h1 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .info-section {
            margin-bottom: 15px;
        }

        .info-row {
            display: flex;
            margin-bottom: 5px;
        }

        .info-label {
            font-weight: bold;
            width: 150px;
        }

        .info-value {
            flex: 1;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table th,
        table td {
            border: 1px solid #333;
            padding: 6px;
            text-align: left;
            font-size: 9px;
        }

        table th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }

        table td:nth-child(1) {
            text-align: center;
            width: 30px;
        }

        table td:nth-child(2) {
            text-align: center;
            width: 80px;
        }

        table td:nth-child(3),
        table td:nth-child(4) {
            text-align: center;
            width: 60px;
        }

        table td:nth-child(5) {
            text-align: center;
            width: 70px;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            color: #999;
        }

        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ccc;
            text-align: center;
            font-size: 8px;
            color: #666;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }
            .container {
                box-shadow: none;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>DAFTAR MUTASI MATERIAL</h1>
        </div>

        <div class="info-section">
            <div class="info-row">
                <div class="info-label">Normalisasi Material</div>
                <div class="info-value">: {{ $material->material_code }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Nama Material</div>
                <div class="info-value">: {{ $material->material_description }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Gudang</div>
                <div class="info-value">: {{ $material->storage_location ?? '-' }}</div>
            </div>
            @if ($unit)
                <div class="info-row">
                    <div class="info-label">Unit</div>
                    <div class="info-value">: {{ $unit->nama_unit ?? $unit->name ?? '-' }}</div>
                </div>
            @endif
            <div class="info-row">
                <div class="info-label">Saldo Akhir</div>
                <div class="info-value">: <strong>{{ number_format($saldoAkhir, 0, ',', '.') }} {{ $material->base_unit_of_measure }}</strong></div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Keluar</th>
                    <th>Masuk</th>
                    <th>Saldo Akhir</th>
                    <th>Penerima/Pengirim</th>
                    <th>Pekerjaan</th>
                    <th>No Slip SAP</th>
                </tr>
            </thead>
            <tbody>
                @forelse($histories as $index => $history)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $history->tanggal->format('d/m/Y') }}</td>
                    <td>{{ $history->keluar > 0 ? number_format($history->keluar, 0, ',', '.') : '' }}</td>
                    <td>{{ $history->masuk > 0 ? number_format($history->masuk, 0, ',', '.') : '' }}</td>
                    <td>{{ number_format($history->saldo_akhir_calculated, 0, ',', '.') }}</td>
                    <td>{{ $history->catatan ?? '-' }}</td>
                    <td>{{ $history->pekerjaan }}</td>
                    <td>{{ $history->no_slip ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="no-data">Belum ada mutasi material</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="footer">
            Dicetak pada: {{ now()->format('d/m/Y H:i') }} WIB<br>
            {{ \App\Models\Setting::get('company_name', 'PT PLN (Persero)') }} UID Jawa Barat - {{ \App\Models\Setting::get('up3_name', 'UP3 Cimahi') }}
        </div>
    </div>

    <script>
        // Auto print jika diperlukan
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
