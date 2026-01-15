<!DOCTYPE html>
<html>
<head>
    <title>Print Material Masuk</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        table, th, td { border: 1px solid #000; }
        th, td { padding: 6px; }
        th { background: #eee; }
        h3 { text-align: center; margin-bottom: 15px; }
    </style>
</head>
<body onload="window.print()">

<h3>DAFTAR MATERIAL MASUK</h3>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal Masuk</th>
            <th>Nomor KR</th>
            <th>Pabrikan</th>
            <th>Material</th>
            <th>Total Qty</th>
            <th>Dibuat Oleh</th>
            <th>Status SAP</th>
        </tr>
    </thead>
    <tbody>
        @foreach($materials as $i => $item)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ \Carbon\Carbon::parse($item->tanggal_masuk)->format('d-m-Y') }}</td>
            <td>{{ $item->nomor_kr }}</td>
            <td>{{ $item->pabrikan }}</td>
            <td>
                @foreach($item->details as $d)
                    {{ $d->material->material_description ?? '-' }}
                    ({{ $d->quantity }} {{ $d->satuan }})<br>
                @endforeach
            </td>
            <td>{{ $item->details->sum('quantity') }}</td>
            <td>{{ $item->creator->nama ?? '-' }}</td>
            <td>{{ $item->status_sap }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
