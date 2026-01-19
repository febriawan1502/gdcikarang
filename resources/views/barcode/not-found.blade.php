<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Material Tidak Ditemukan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 500px;
        }

        .icon {
            font-size: 64px;
            color: #dc3545;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 24px;
            color: #333;
            margin-bottom: 10px;
        }

        p {
            font-size: 16px;
            color: #666;
            margin-bottom: 20px;
        }

        .material-code {
            background: #f8f9fa;
            padding: 10px 20px;
            border-radius: 5px;
            font-family: monospace;
            font-size: 18px;
            color: #495057;
            margin: 20px 0;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            transition: background 0.3s;
        }

        .btn:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">⚠️</div>
        <h1>Material Tidak Ditemukan</h1>
        <p>Material dengan normalisasi berikut tidak ditemukan dalam database:</p>
        <div class="material-code">{{ $materialCode }}</div>
        <p>Silakan periksa kembali kode material atau hubungi admin gudang.</p>
        <a href="{{ route('login') }}" class="btn">Kembali ke Dashboard</a>
    </div>
</body>
</html>
