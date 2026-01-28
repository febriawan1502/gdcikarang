$logFile = 'mrwi_debug.log';
$nomorSurat = '008.PBK/LOG.00.02/F02050000/2026';
$sj = App\Models\SuratJalan::where('nomor_surat', $nomorSurat)->with('details.material')->first();

$output = "";

if (!$sj) {
$output .= "Surat Jalan not found.\n";
} else {
$output .= "SJ ID: {$sj->id}, Status: {$sj->status}\n";

foreach ($sj->details as $d) {
$output .= "------------------------------------------------\n";
$output .= "Material: " . ($d->material->material_description ?? 'Unknown') . " (ID: {$d->material_id})\n";
$output .= "Qty: {$d->quantity}\n";
$output .= "Serials: " . json_encode($d->serial_numbers) . "\n";

$stock = App\Models\MaterialMrwiStock::where('material_id', $d->material_id)
->where('unit_id', $sj->unit_id)
->first();

if ($stock) {
$output .= "Stock Table -> Standby: {$stock->standby_stock}, Rusak: {$stock->rusak_stock}, Perbaikan: {$stock->perbaikan_stock}, Garansi: {$stock->garansi_stock}\n";
} else {
$output .= "Stock Table -> Not Found!\n";
}

if ($d->serial_numbers) {
foreach ($d->serial_numbers as $s) {
$move = App\Models\MaterialMrwiSerialMove::where('serial_number', $s)
->orderByDesc('id')
->first();

$output .= "Serial '$s' Latest Move:\n";
if ($move) {
$output .= " - Status Bucket: {$move->status_bucket}\n";
$output .= " - Jenis: {$move->jenis}\n";
$output .= " - Unit ID: {$move->unit_id} (SJ Unit: {$sj->unit_id})\n";
} else {
$output .= " - Move Not Found!\n";
}
}
}
}
}

file_put_contents($logFile, $output);
exit