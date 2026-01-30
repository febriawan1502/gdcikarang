<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\\Contracts\\Console\\Kernel')->bootstrap();
$serial = '53CRB25120803640003';
$latest = App\Models\MaterialMrwiSerialMove::where('serial_number', $serial)->orderByDesc('id')->first();
if ($latest) {
    echo json_encode([
        'jenis' => $latest->jenis,
        'bucket' => $latest->status_bucket,
        'tanggal' => $latest->tanggal,
        'unit_id' => $latest->unit_id,
    ], JSON_PRETTY_PRINT);
} else {
    echo 'not found';
}
