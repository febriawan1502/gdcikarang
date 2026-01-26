<?php

namespace Database\Seeders;

use App\Models\Material;
use App\Models\MaterialStock;
use App\Models\Unit;
use Illuminate\Database\Seeder;
use PhpOffice\PhpSpreadsheet\IOFactory;

class CikarangSaldoSeeder extends Seeder
{
    public function run(): void
    {
        $unit = Unit::where('code', 'UP3-CKR')->first();
        if (!$unit) {
            $this->command?->error('Unit UP3-CKR tidak ditemukan.');
            return;
        }

        $filePath = base_path('public/assets/lain-lain/CIKARANG SALDO 26 JAN 2026 SAP dan fisik.XLSX');
        if (!file_exists($filePath)) {
            $this->command?->error("File tidak ditemukan: {$filePath}");
            return;
        }

        $sheet = IOFactory::load($filePath)->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);
        if (count($rows) < 2) {
            $this->command?->warn('File kosong atau tidak ada data.');
            return;
        }

        $headerRow = array_shift($rows);
        $headerMap = $this->buildHeaderMap($headerRow);

        $nextNomor = (int) Material::max('nomor') + 1;

        foreach ($rows as $row) {
            $materialCodeRaw = $this->getValue($row, $headerMap, 'MATERIAL');
            $materialDescription = $this->getValue($row, $headerMap, 'MATERIAL DESCRIPTION');

            if ($materialCodeRaw === '' || $materialDescription === '') {
                continue;
            }

            $materialCode = $this->formatMaterialCode($materialCodeRaw);
            if ($materialCode === '') {
                continue;
            }

            $rak = $this->getValue($row, $headerMap, 'NO RAK');
            if ($rak === '#N/A' || $rak === '') {
                $rak = null;
            }

            $material = Material::where('material_code', $materialCode)->first();
            if (!$material) {
                $material = new Material();
                $material->nomor = $nextNomor++;
                $material->material_code = $materialCode;
                $material->created_by = 1;
            }

            $material->company_code = $this->getValue($row, $headerMap, 'COMPANY CODE');
            $material->company_code_description = $this->getValue($row, $headerMap, 'COMPANY CODE DESCRIPTION');
            $material->plant = $this->getValue($row, $headerMap, 'PLANT');
            $material->plant_description = $this->getValue($row, $headerMap, 'PLANT DESCRIPTION');
            $material->storage_location = $this->getValue($row, $headerMap, 'STORAGE LOCATION');
            $material->storage_location_description = $this->getValue($row, $headerMap, 'STORAGE LOCATION DESCRIPTION');
            $material->material_type = $this->getValue($row, $headerMap, 'MATERIAL TYPE');
            $material->material_description = $materialDescription;
            $material->base_unit_of_measure = $this->getValue($row, $headerMap, 'SATUAN');
            $material->material_group = $this->getValue($row, $headerMap, 'MATERIAL GROUP');
            $material->valuation_type = $this->getValue($row, $headerMap, 'VALUATION TYPE');
            $material->valuation_class = $this->getValue($row, $headerMap, 'VALUATION CLASS');
            $material->valuation_description = $this->getValue($row, $headerMap, 'VALUATION DESCRIPTION');
            $material->rak = $rak;
            $material->save();

            $saldoFisik = $this->toFloat($this->getValue($row, $headerMap, 'SALDO FISIK'));

            MaterialStock::withoutGlobalScopes()->updateOrCreate(
                [
                    'material_id' => $material->id,
                    'unit_id' => $unit->id,
                ],
                [
                    'unrestricted_use_stock' => $saldoFisik,
                    'quality_inspection_stock' => $this->toFloat($this->getValue($row, $headerMap, 'QUALITY INSPECTION STOCK')),
                    'blocked_stock' => $this->toFloat($this->getValue($row, $headerMap, 'BLOCKED STOCK')),
                    'in_transit_stock' => $this->toFloat($this->getValue($row, $headerMap, 'IN TRANSIT STOCK')),
                    'project_stock' => 0,
                    'qty' => (int) $saldoFisik,
                    'min_stock' => 0,
                ]
            );
        }
    }

    private function buildHeaderMap(array $headerRow): array
    {
        $map = [];
        foreach ($headerRow as $col => $name) {
            $key = strtoupper(trim((string) $name));
            if ($key !== '') {
                $map[$key] = $col;
            }
        }

        return $map;
    }

    private function getValue(array $row, array $headerMap, string $key): string
    {
        $col = $headerMap[strtoupper($key)] ?? null;
        if (!$col || !isset($row[$col])) {
            return '';
        }

        return trim((string) $row[$col]);
    }

    private function formatMaterialCode($value): string
    {
        if ($value === null || $value === '') {
            return '';
        }

        if (is_numeric($value)) {
            if ((float) $value == (int) $value) {
                return (string) (int) $value;
            }
            return rtrim(rtrim((string) $value, '0'), '.');
        }

        return trim((string) $value);
    }

    private function toFloat($value): float
    {
        if ($value === null || $value === '') {
            return 0.0;
        }

        if (is_numeric($value)) {
            return (float) $value;
        }

        $normalized = str_replace([',', ' '], '', (string) $value);
        return is_numeric($normalized) ? (float) $normalized : 0.0;
    }
}
