<?php

use App\Models\SuratJalan;
use App\Models\MaterialMrwiStock;
use App\Models\MaterialMrwiSerialMove;

$nomorSurat = '008.PBK/LOG.00.02/F02050000/2026';
$sj = SuratJalan::where('nomor_surat', $nomorSurat)->with('details.material')->first();

if (!$sj) {
    echo "Surat Jalan not found.\n";
    exit;
}

echo "SJ ID: {$sj->id}, Status: {$sj->status}\n";

foreach ($sj->details as $d) {
    echo "------------------------------------------------\n";
    echo "Material: " . ($d->material->material_description ?? 'Unknown') . " (ID: {$d->material_id})\n";
    echo "Qty: {$d->quantity}\n";
    echo "Serials: " . json_encode($d->serial_numbers) . "\n";

    $stock = MaterialMrwiStock::where('material_id', $d->material_id)
        ->where('unit_id', $sj->unit_id)
        ->first();

    if ($stock) {
        echo "Stock Table -> Standby: {$stock->standby_stock}, Rusak: {$stock->rusak_stock}, Perbaikan: {$stock->perbaikan_stock}, Garansi: {$stock->garansi_stock}\n";
    } else {
        echo "Stock Table -> Not Found!\n";
    }

    if ($d->serial_numbers) {
        foreach ($d->serial_numbers as $s) {
            $move = MaterialMrwiSerialMove::where('serial_number', $s)
                ->orderByDesc('id')
                ->first();

            echo "Serial '$s' Latest Move:\n";
            if ($move) {
                echo "  - Status Bucket: {$move->status_bucket}\n";
                echo "  - Jenis: {$move->jenis}\n";
                echo "  - Unit ID: {$move->unit_id} (SJ Unit: {$sj->unit_id})\n";
            } else {
                echo "  - Move Not Found!\n";
            }
        }
    }
}
