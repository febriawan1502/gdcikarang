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

        $rows = [
            [
                'code' => '2190224',
                'rak' => '1.A11-1.A44',
                'desc_contains' => '1P;230V;5-60A',
            ],
            [
                'code' => '2190224',
                'rak' => '1.B11-1.B44',
                'desc_contains' => 'E-PR',
            ],
            [
                'code' => '2200004',
                'rak' => '1.C11-1.C44',
                'desc_contains' => null,
            ],
            [
                'code' => '2190252',
                'rak' => '1.D11-1.D41',
                'desc_contains' => '3P;230/400V;5-80A',
            ],
            [
                'code' => '2190438',
                'rak' => '1.D12-1.D42',
                'desc_contains' => '5.7/100V-230/400',
            ],
        ];

        foreach ($rows as $row) {
            $code = $this->normalizeMaterialCode($row['code']);
            $query = Material::where('material_code', $code);
            if (!empty($row['desc_contains'])) {
                $query->where('material_description', 'like', '%' . $row['desc_contains'] . '%');
            }

            $materials = $query->get();
            if ($materials->isEmpty()) {
                $this->command?->warn("Material tidak ditemukan: {$code} ({$row['rak']})");
                continue;
            }

            if ($materials->count() > 1) {
                $this->command?->warn("Material ganda untuk kode {$code} ({$row['rak']}). Perjelas filter deskripsi.");
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

        // SAP material codes are often 8 chars with leading zeros.
        return str_pad($trimmed, 8, '0', STR_PAD_LEFT);
    }
}
