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
            font-size: 12px;
            line-height: 1.4;
            padding: 20px;
            background: #f5f5f5;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #333;
        }

        .header h1 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .info-section {
            margin-bottom: 20px;
            background: #f9f9f9;
            padding: 10px;
            border-radius: 4px;
        }

        .info-row {
            display: flex;
            margin-bottom: 5px;
        }

        .info-label {
            font-weight: bold;
            width: 140px;
            color: #555;
        }

        .info-value {
            flex: 1;
            font-weight: 500;
        }

        /* Desktop Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 11px;
        }

        table th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
            color: #333;
        }

        table tr:nth-child(even) {
            background-color: #fafafa;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .font-bold {
            font-weight: bold;
        }

        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ccc;
            text-align: center;
            font-size: 10px;
            color: #666;
        }

        /* Mobile Responsive Styles */
        @media screen and (max-width: 600px) {
            body {
                padding: 10px;
                background: #fff;
            }

            .container {
                box-shadow: none;
                padding: 0;
            }

            .info-label {
                width: 110px;
                /* Smaller label width on mobile */
            }

            table,
            thead,
            tbody,
            th,
            td,
            tr {
                display: block;
            }

            thead tr {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }

            tr {
                border: 1px solid #e5e7eb;
                border-radius: 8px;
                margin-bottom: 15px;
                padding: 10px;
                background: white;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            }

            td {
                border: none;
                border-bottom: 1px solid #f0f0f0;
                position: relative;
                padding-left: 45%;
                /* Space for label */
                padding-top: 8px;
                padding-bottom: 8px;
                text-align: right !important;
                /* Force right align for value */
                font-size: 12px;
            }

            td:last-child {
                border-bottom: none;
            }

            td:before {
                position: absolute;
                top: 8px;
                left: 0;
                width: 40%;
                padding-right: 10px;
                white-space: nowrap;
                text-align: left;
                font-weight: bold;
                color: #6b7280;
                content: attr(data-label);
            }

            /* Hide 'No' column on mobile if not needed, or keep it */
            td[data-label="No"] {
                display: none;
            }
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .container {
                box-shadow: none;
                padding: 0;
                max-width: 100%;
            }

            .header {
                border-bottom: 2px solid #000;
            }

            /* Force Table Layout for Print */
            table {
                display: table !important;
            }

            thead {
                display: table-header-group !important;
            }

            tbody {
                display: table-row-group !important;
            }

            tr {
                display: table-row !important;
                border: none !important;
                margin: 0 !important;
                box-shadow: none !important;
            }

            th,
            td {
                display: table-cell !important;
                border: 1px solid #000 !important;
                padding: 4px !important;
                text-align: left !important;
            }

            td:before {
                content: none !important;
            }

            td {
                padding-left: 4px !important;
                text-align: left !important;
            }

            /* Specialized Print Columns */
            td:nth-child(1) {
                width: 5%;
                text-align: center !important;
            }

            /* No */
            td:nth-child(2) {
                width: 12%;
                text-align: center !important;
            }

            /* Tanggal */
            td:nth-child(3),
            td:nth-child(4) {
                width: 10%;
                text-align: center !important;
            }

            /* In/Out */
            td:nth-child(5) {
                width: 10%;
                text-align: center !important;
            }

            /* Saldo */
            td:nth-child(8) {
                width: 15%;
                text-align: center !important;
            }

            /* Slip */
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
                <div class="info-label">Material Code</div>
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
                    <div class="info-value">: {{ $unit->nama_unit ?? ($unit->name ?? '-') }}</div>
                </div>
            @endif
            <div class="info-row">
                <div class="info-label">Saldo Akhir</div>
                <div class="info-value">: <strong>{{ number_format($saldoAkhir, 0, ',', '.') }}
                        {{ $material->base_unit_of_measure }}</strong></div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Keluar</th>
                    <th>Masuk</th>
                    <th>Saldo</th>
                    <th>Penerima/Pengirim</th>
                    <th>Pekerjaan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($histories as $index => $history)
                    <tr>
                        <td data-label="No" class="text-center">{{ $index + 1 }}</td>
                        <td data-label="Tanggal" class="text-center">{{ $history->tanggal->format('d/m/Y') }}</td>
                        <td data-label="Keluar" class="text-center">
                            {{ $history->keluar > 0 ? number_format($history->keluar, 0, ',', '.') : '-' }}</td>
                        <td data-label="Masuk" class="text-center">
                            {{ $history->masuk > 0 ? number_format($history->masuk, 0, ',', '.') : '-' }}</td>
                        <td data-label="Saldo Akhir" class="text-center font-bold">
                            {{ number_format($history->saldo_akhir_calculated, 0, ',', '.') }}</td>
                        <td data-label="Penerima/Pengirim">{{ $history->catatan ?? '-' }}</td>
                        <td data-label="Pekerjaan">{{ $history->pekerjaan }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="no-data text-center" style="padding: 20px; color: #777;">Belum ada
                            mutasi material</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="footer">
            Dicetak pada: {{ now()->format('d/m/Y H:i') }} WIB<br>
            {{ \App\Models\Setting::get('company_name', 'PT PLN (Persero)') }} UID Jawa Barat -
            {{ isset($unit) ? $unit->name : \App\Models\Setting::get('up3_name', 'UP3 Cimahi') }}
        </div>
    </div>

    <script>
        // Auto print jika diperlukan
        // window.onload = function() { window.print(); }
    </script>
</body>

</html>
