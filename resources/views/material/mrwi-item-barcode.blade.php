<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barcode {{ $item->serial_number }}</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background: #f5f5f5;
        }

        .container {
            background: white;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            text-align: center;
            max-width: 400px;
            width: 90%;
        }

        .qr-wrapper {
            background: white;
            padding: 1rem;
            display: inline-block;
        }

        .title {
            font-size: 1.25rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
            color: #1f2937;
        }

        .subtitle {
            color: #4b5563;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
        }

        .info-grid {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 0.5rem 1rem;
            text-align: left;
            margin-top: 1.5rem;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
            background: #f9fafb;
            padding: 1rem;
            border-radius: 0.5rem;
        }

        .label {
            font-weight: 600;
            color: #6b7280;
        }

        .value {
            color: #1f2937;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-weight: 500;
            font-size: 0.875rem;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
            border: none;
            gap: 0.5rem;
        }

        .btn-blue {
            background-color: #3b82f6;
            color: white;
        }

        .btn-blue:hover {
            background-color: #2563eb;
        }

        .btn-print {
            margin-top: 1rem;
        }

        /* Print Specific Styles - 50x30mm Label */
        @media print {
            @page {
                size: 50mm 30mm;
                margin: 0;
            }

            body {
                background: white;
                display: block;
            }

            .container {
                box-shadow: none;
                padding: 0;
                margin: 0;
                width: 100%;
                max-width: none;
                display: none;
                /* Hide default view */
            }

            .print-label {
                display: flex !important;
                width: 50mm;
                height: 30mm;
                padding: 1.5mm;
                border: 0.2mm solid #000;
                /* Optional: border for visual guide, remove for production if needed */
                box-sizing: border-box;
                gap: 1.5mm;
                align-items: center;
                justify-content: space-between;
                page-break-after: always;
            }

            .label-text {
                flex: 1 1 auto;
                min-width: 0;
                text-align: left;
                line-height: 1.1;
                overflow: hidden;
            }

            .label-text .header {
                font-size: 6pt;
                font-weight: bold;
                white-space: nowrap;
            }

            .label-text .serial {
                font-size: 8pt;
                font-weight: bold;
                margin: 1mm 0;
                font-family: monospace;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .label-text .desc {
                font-size: 5pt;
                color: #000;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            .qr-code-print {
                flex: 0 0 auto;
                width: 18mm;
                height: 18mm;
            }

            .qr-code-print img {
                width: 100%;
                height: 100%;
            }
        }

        .print-label {
            display: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="title">QR Code Serial Number</div>
        <div class="subtitle">{{ $item->serial_number }}</div>

        <div class="qr-wrapper" id="qrcode"></div>

        <div class="info-grid">
            <div class="label">Material:</div>
            <div class="value">{{ $item->nama_material }}</div>
            <div class="label">Merk:</div>
            <div class="value">{{ $item->nama_pabrikan }}</div>
            <div class="label">Tahun:</div>
            <div class="value">{{ $item->tahun_buat }}</div>
            <div class="label">URL:</div>
            <div class="value" style="word-break: break-all; font-size: 0.75rem; color: #6b7280;">{{ $barcodeUrl }}
            </div>
        </div>

        <button onclick="window.print()" class="btn btn-blue btn-print">
            🖨️ Print Label (50x30mm)
        </button>
        <button onclick="window.close()" class="btn" style="background: #f3f4f6; color: #374151;">
            Tutup
        </button>
    </div>

    {{-- Print Template --}}
    <div class="print-label">
        <div class="label-text">
            <div class="header">PLN (Persero)</div>
            <div class="serial">{{ $item->serial_number }}</div>
            <div class="desc">{{ Str::limit($item->nama_material, 30) }}</div>
            <div class="desc">{{ $item->nama_pabrikan }} - {{ $item->tahun_buat }}</div>
        </div>
        <div class="qr-code-print" id="qrcodePrint"></div>
    </div>

    <script>
        const barcodeUrl = "{{ $barcodeUrl }}";

        // Render Main QR
        new QRCode(document.getElementById("qrcode"), {
            text: barcodeUrl,
            width: 128,
            height: 128,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.M
        });

        // Render Print QR
        new QRCode(document.getElementById("qrcodePrint"), {
            text: barcodeUrl,
            width: 64,
            height: 64,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.M
        });
    </script>
</body>

</html>
