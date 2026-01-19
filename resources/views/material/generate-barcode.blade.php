<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Barcode - {{ $material->material_code }}</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        .material-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 30px;
        }

        .material-info p {
            margin: 10px 0;
            font-size: 14px;
        }

        .material-info strong {
            display: inline-block;
            width: 200px;
        }

        .qr-container {
            text-align: center;
            margin: 30px 0;
            padding: 30px;
            background: white;
            border: 2px dashed #ddd;
            border-radius: 10px;
        }

        #qrcode {
            display: inline-block;
            padding: 20px;
            background: white;
        }

        .url-info {
            margin-top: 20px;
            padding: 15px;
            background: #e9ecef;
            border-radius: 5px;
            word-break: break-all;
        }

        .url-info strong {
            display: block;
            margin-bottom: 5px;
        }

        .buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 30px;
        }

        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
        }

        .btn-primary {
            background: #007bff;
            color: white;
        }

        .btn-primary:hover {
            background: #0056b3;
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-success:hover {
            background: #218838;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        @media print {
            body {
                background: white;
            }
            .container {
                box-shadow: none;
                padding: 0;
            }
            .buttons {
                display: none;
            }
            .material-info {
                page-break-after: avoid;
            }
        }

        .print-label {
            page-break-inside: avoid;
            border: 2px solid #333;
            padding: 20px;
            margin: 20px auto;
            width: 300px;
            text-align: center;
        }

        .print-label h2 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .print-label .material-code {
            font-size: 24px;
            font-weight: bold;
            margin: 10px 0;
            font-family: monospace;
        }

        .print-label .material-name {
            font-size: 12px;
            margin: 10px 0;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🏷️ Generate Barcode Material</h1>

        <div class="material-info">
            <p><strong>Normalisasi Material:</strong> {{ $material->material_code }}</p>
            <p><strong>Nama Material:</strong> {{ $material->material_description }}</p>
            <p><strong>Gudang:</strong> {{ $material->storage_location ?? '-' }}</p>
            <p><strong>Rak:</strong> {{ $material->rak ?? '-' }}</p>
        </div>

        <div class="qr-container">
            <div id="qrcode"></div>
            
            <div class="url-info">
                <strong>URL Barcode:</strong>
                <code id="barcodeUrl">{{ $barcodeUrl }}</code>
            </div>
        </div>

        <div class="buttons">
            <button onclick="printBarcode()" class="btn btn-primary">
                🖨️ Print Barcode
            </button>
            <button onclick="downloadQR()" class="btn btn-success">
                💾 Download QR Code
            </button>
            <a href="{{ route('material.index') }}" class="btn btn-secondary">
                ← Kembali
            </a>
        </div>

        <!-- Template untuk print -->
        <div id="printTemplate" style="display: none;">
            <div class="print-label">
                <h2>{{ \App\Models\Setting::get('company_name', 'PT PLN (Persero)') }}</h2>
                <h2>{{ \App\Models\Setting::get('up3_name', 'UP3 Cimahi') }}</h2>
                <div class="material-code">{{ $material->material_code }}</div>
                <div class="material-name">{{ Str::limit($material->material_description, 50) }}</div>
                <div id="qrcodePrint"></div>
                <p style="font-size: 10px; margin-top: 10px;">Scan untuk lihat mutasi material</p>
            </div>
        </div>
    </div>

    <script>
        // Generate QR Code
        const barcodeUrl = "{{ $barcodeUrl }}";
        
        // PHP variables for JavaScript
        const companyName = "{{ \App\Models\Setting::get('company_name', 'PT PLN (Persero)') }}";
        const up3Name = "{{ \App\Models\Setting::get('up3_name', 'UP3 Cimahi') }}";
        const materialCode = "{{ $material->material_code }}";
        const materialName = "{{ Str::limit($material->material_description, 50) }}";
        
        new QRCode(document.getElementById("qrcode"), {
            text: barcodeUrl,
            width: 256,
            height: 256,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });

        // Function untuk print
        function printBarcode() {
            // Buat window baru untuk print
            const printWindow = window.open('', '_blank', 'width=400,height=600');
            
            // Ambil QR code yang sudah ada sebagai image
            const existingQR = document.querySelector('#qrcode canvas');
            let qrImageSrc = '';
            if (existingQR) {
                qrImageSrc = existingQR.toDataURL('image/png');
            }
            
            // HTML untuk print
            const printHTML = `
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Print Barcode</title>
                    <style>
                        * { margin: 0; padding: 0; box-sizing: border-box; }
                        body { 
                            font-family: Arial, sans-serif; 
                            display: flex; 
                            justify-content: center; 
                            align-items: center; 
                            min-height: 100vh;
                            padding: 20px;
                        }
                        .print-label {
                            border: 2px solid #333;
                            padding: 20px;
                            width: 300px;
                            text-align: center;
                        }
                        .print-label h2 {
                            font-size: 16px;
                            margin-bottom: 5px;
                        }
                        .material-code {
                            font-size: 22px;
                            font-weight: bold;
                            margin: 10px 0;
                            font-family: monospace;
                        }
                        .material-name {
                            font-size: 11px;
                            margin: 10px 0;
                            color: #666;
                        }
                        .qr-code {
                            margin: 15px auto;
                        }
                        .qr-code img {
                            width: 180px;
                            height: 180px;
                        }
                        .scan-text {
                            font-size: 10px;
                            margin-top: 10px;
                        }
                        @media print {
                            body { padding: 0; }
                        }
                    </style>
                </head>
                <body>
                    <div class="print-label">
                        <h2>${companyName}</h2>
                        <h2>${up3Name}</h2>
                        <div class="material-code">${materialCode}</div>
                        <div class="material-name">${materialName}</div>
                        <div class="qr-code">
                            <img src="${qrImageSrc}" alt="QR Code">
                        </div>
                        <p class="scan-text">Scan untuk lihat mutasi material</p>
                    </div>
                    <script>
                        window.onload = function() {
                            setTimeout(function() {
                                window.print();
                                window.close();
                            }, 300);
                        };
                    <\/script>
                </body>
                </html>
            `;
            
            printWindow.document.write(printHTML);
            printWindow.document.close();
        }

        // Function untuk download QR code
        function downloadQR() {
            const canvas = document.querySelector('#qrcode canvas');
            if (canvas) {
                const url = canvas.toDataURL('image/png');
                const link = document.createElement('a');
                link.download = 'QR-' + materialCode + '.png';
                link.href = url;
                link.click();
            }
        }

        // Copy URL
        document.getElementById('barcodeUrl').addEventListener('click', function() {
            navigator.clipboard.writeText(barcodeUrl);
            alert('URL berhasil dicopy!');
        });
    </script>
</body>
</html>
