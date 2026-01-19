<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulk Print Barcode - Semua Material</title>
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

        .header-controls {
            max-width: 1200px;
            margin: 0 auto 30px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-controls h1 {
            color: #333;
            font-size: 24px;
        }

        .header-controls .info {
            color: #666;
            font-size: 14px;
        }

        .buttons {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }

        .btn-primary {
            background: #007bff;
            color: white;
        }

        .btn-primary:hover {
            background: #0056b3;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .barcode-grid {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
        }

        .barcode-item {
            background: white;
            border: 2px solid #333;
            padding: 15px;
            text-align: center;
            page-break-inside: avoid;
        }

        .barcode-item h3 {
            font-size: 12px;
            margin-bottom: 3px;
            color: #333;
        }

        .barcode-item .material-code {
            font-size: 14px;
            font-weight: bold;
            margin: 8px 0;
            font-family: monospace;
            word-break: break-all;
        }

        .barcode-item .material-name {
            font-size: 9px;
            margin: 5px 0;
            color: #666;
            height: 24px;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .barcode-item .qr-code {
            margin: 10px auto;
            display: flex;
            justify-content: center;
        }

        .barcode-item .qr-code canvas {
            width: 100px !important;
            height: 100px !important;
        }

        .barcode-item .scan-text {
            font-size: 8px;
            color: #888;
            margin-top: 5px;
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255,255,255,0.9);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .loading-overlay .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #007bff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        .loading-overlay p {
            margin-top: 15px;
            font-size: 16px;
            color: #333;
        }

        .loading-overlay .progress-text {
            margin-top: 10px;
            font-size: 14px;
            color: #666;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Print Styles */
        @media print {
            body {
                background: white;
                padding: 0;
                margin: 0;
            }

            .header-controls {
                display: none !important;
            }

            .loading-overlay {
                display: none !important;
            }

            .barcode-grid {
                max-width: 100%;
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 10px;
                padding: 10px;
            }

            .barcode-item {
                padding: 10px;
                border: 1.5px solid #333;
                page-break-inside: avoid;
            }

            .barcode-item h3 {
                font-size: 10px;
            }

            .barcode-item .material-code {
                font-size: 12px;
            }

            .barcode-item .material-name {
                font-size: 8px;
            }

            .barcode-item .qr-code canvas,
            .barcode-item .qr-code img {
                width: 80px !important;
                height: 80px !important;
            }

            .barcode-item .scan-text {
                font-size: 7px;
            }
        }

        @page {
            size: A4;
            margin: 10mm;
        }
    </style>
</head>
<body>
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner"></div>
        <p>Generating QR Codes...</p>
        <p class="progress-text" id="progressText">0 / {{ $materials->count() }}</p>
    </div>

    <!-- Header Controls -->
    <div class="header-controls">
        <div>
            <h1>🏷️ Bulk Print Barcode</h1>
            <p class="info">Total: {{ $materials->count() }} material</p>
        </div>
        <div class="buttons">
            <button onclick="printBarcodes()" class="btn btn-primary" id="printBtn" disabled>
                🖨️ Print Semua
            </button>
            <a href="{{ route('material.index') }}" class="btn btn-secondary">
                ← Kembali
            </a>
        </div>
    </div>

    <!-- Barcode Grid -->
    <div class="barcode-grid" id="barcodeGrid">
        @foreach($materials as $index => $material)
        <div class="barcode-item">
            <h3>{{ $companyName }}</h3>
            <h3>{{ $up3Name }}</h3>
            <div class="material-code">{{ $material->material_code }}</div>
            <div class="material-name">{{ Str::limit($material->material_description, 40) }}</div>
            <div class="qr-code" id="qr-{{ $index }}" data-url="{{ $material->barcodeUrl }}"></div>
            <p class="scan-text">Scan untuk lihat mutasi material</p>
        </div>
        @endforeach
    </div>

    <script>
        // Generate all QR codes
        document.addEventListener('DOMContentLoaded', function() {
            const qrContainers = document.querySelectorAll('.qr-code[data-url]');
            const totalCount = qrContainers.length;
            const progressText = document.getElementById('progressText');
            const loadingOverlay = document.getElementById('loadingOverlay');
            const printBtn = document.getElementById('printBtn');
            
            let generatedCount = 0;
            
            // Generate QR codes with a small delay to prevent browser freeze
            function generateQRCodes(startIndex) {
                const batchSize = 10; // Generate 10 at a time
                const endIndex = Math.min(startIndex + batchSize, totalCount);
                
                for (let i = startIndex; i < endIndex; i++) {
                    const container = qrContainers[i];
                    const url = container.getAttribute('data-url');
                    
                    try {
                        new QRCode(container, {
                            text: url,
                            width: 100,
                            height: 100,
                            colorDark: "#000000",
                            colorLight: "#ffffff",
                            correctLevel: QRCode.CorrectLevel.M
                        });
                    } catch (e) {
                        console.error('Error generating QR for index ' + i, e);
                    }
                    
                    generatedCount++;
                    progressText.textContent = generatedCount + ' / ' + totalCount;
                }
                
                if (endIndex < totalCount) {
                    // Continue with next batch
                    setTimeout(function() {
                        generateQRCodes(endIndex);
                    }, 50);
                } else {
                    // All done
                    setTimeout(function() {
                        loadingOverlay.style.display = 'none';
                        printBtn.disabled = false;
                    }, 500);
                }
            }
            
            // Start generating
            if (totalCount > 0) {
                generateQRCodes(0);
            } else {
                loadingOverlay.style.display = 'none';
            }
        });

        // Print function
        function printBarcodes() {
            window.print();
        }
    </script>
</body>
</html>
