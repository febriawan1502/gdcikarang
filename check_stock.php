<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SuratJalan;
use App\Models\MaterialMrwiStock;
use App\Models\MaterialMrwiSerialMove;

$sjId = 30;
$sj = SuratJalan::with('details')->find($sjId);

if (!$sj) {
    echo "SJ #$sjId NOT FOUND\n";
    exit;
}

echo "SJ #$sjId Unit: {$sj->unit_id}\n";
$detail = $sj->details->first();
if (!$detail) {
    echo "No details found\n";
    exit;
}

echo "Material ID: {$detail->material_id}, Required Qty: {$detail->quantity}\n";

$stock = MaterialMrwiStock::where('material_id', $detail->material_id)
    ->where('unit_id', $sj->unit_id)
    ->first();

if ($stock) {
    echo "STOCK: Standby={$stock->standby_stock}, Rusak={$stock->rusak_stock}, Perbaikan={$stock->perbaikan_stock}, Garansi={$stock->garansi_stock}\n";
} else {
    echo "STOCK ROW NOT FOUND\n";
}

$serials = $detail->serial_numbers ?? [];
echo "Requested Serials: " . json_encode($serials) . "\n";

foreach ($serials as $serial) {
    $latest = MaterialMrwiSerialMove::where('serial_number', $serial)
        ->where('material_id', $detail->material_id)
        ->where('unit_id', $sj->unit_id)
        ->orderByDesc('id')
        ->first();

    if ($latest) {
        echo "Serial $serial -> Latest Status Bucket: {$latest->status_bucket}\n";
    } else {
        echo "Serial $serial -> NOT FOUND IN MOVES\n";
    }
}
