<?php

namespace Database\Seeders;

use App\Models\Material;
use App\Models\MaterialStock;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class Up3CikarangRakSeeder extends Seeder
{
    public function run(): void
    {
        $unit = Unit::where('code', 'UP3-CKR')->first();
        if (!$unit) {
            $this->command?->error('Unit UP3-CKR tidak ditemukan.');
            return;
        }

        $jsonPath = database_path('data/rak_cikarang.json');
        
        if (!file_exists($jsonPath)) {
            $this->command?->error("File data tidak ditemukan: {$jsonPath}");
            return;
        }

        $content = file_get_contents($jsonPath);
        $rows = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->command?->error("Error decoding JSON: " . json_last_error_msg());
            return;
        }

        // Pre-calculate code counts to handle duplicates
        $codeCounts = [];
        foreach ($rows as $row) {
            $c = $this->normalizeMaterialCode($row['code']);
            if (!isset($codeCounts[$c])) {
                $codeCounts[$c] = 0;
            }
            $codeCounts[$c]++;
        }

        foreach ($rows as $row) {
            $code = $this->normalizeMaterialCode($row['code']);
            $isDuplicate = ($codeCounts[$code] ?? 0) > 1;

            $query = Material::where('material_code', $code);
            
            // Only enforce description check if we have duplicates in the source file
            // OR if it's not a duplicate but we want to be safe (optional). 
            // Here we prioritize the Code if it's unique in the source.
            if ($isDuplicate && !empty($row['desc_contains'])) {
                $query->where('material_description', 'like', '%' . $row['desc_contains'] . '%');
            }

            $materials = $query->get();
            if ($materials->isEmpty()) {
                // If stricter check failed (or just not found), validation
                $this->command?->warn("Material tidak ditemukan: {$code} ({$row['rak']})" . ($isDuplicate ? " [Ambiguous]" : ""));
                continue;
            }

            if ($materials->count() > 1) {
                // This refers to DB duplicates, which we should avoid globally but handled here just in case
                $this->command?->warn("Material ganda di DB untuk kode {$code} ({$row['rak']}). Perjelas filter deskripsi.");
                continue;
            }

            $material = $materials->first();

            MaterialStock::withoutGlobalScopes()->updateOrCreate(
                [
                    'material_id' => $material->id,
                    'unit_id' => $unit->id,
                ],
                [
                    'rak' => $row['rak'],
                ]
            );
        }
    }

    private function normalizeMaterialCode(string $code): string
    {
        $trimmed = trim($code);
        if ($trimmed === '') {
            return $trimmed;
        }

        // DB stores material_code with 8 leading zeros before the normalisasi number.
        return '00000000' . $trimmed;
    }
}
