<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\MaterialMrwiDetail;

$detail = MaterialMrwiDetail::latest()->first();

if ($detail) {
    echo "FOUND: " . $detail->serial_number . "\n";
    echo "Material ID: " . $detail->material_id . "\n";
} else {
    echo "NO_DATA\n";
}
