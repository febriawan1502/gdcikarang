<?php

    namespace App\Imports;

    use App\Models\Material;
    use App\Models\PemeriksaanFisik;
    use Maatwebsite\Excel\Concerns\ToCollection;
    use Maatwebsite\Excel\Concerns\WithHeadingRow;
    use Illuminate\Support\Collection;

    class PemeriksaanFisikImport implements ToCollection, WithHeadingRow
    {
        protected $bulan;

        public function __construct($bulan)
        {
            $this->bulan = $bulan;
        }

public function collection(Collection $rows)
{
    foreach ($rows as $row) {

        \Log::info('IMPORT ROW', $row->toArray());

        
        $rawCode = trim($row['no_part'] ?? '');
        if (!$rawCode) continue;

        
        $materialCode = ltrim($rawCode, '0');

        
        if ($materialCode === '') {
            $materialCode = '0';
        }

        
        $material = Material::where('material_code', $materialCode)->first();

        if (!$material) {
            \Log::warning('MATERIAL TIDAK DITEMUKAN', [
                'excel' => $rawCode,
                'normalized' => $materialCode
            ]);
            continue;
        }

        PemeriksaanFisik::updateOrCreate(
            [
                'material_id' => $material->id,
                'bulan'       => $this->bulan,
            ],
            [
                'sap'     => (int) ($row['sap'] ?? 0),
                'sn_mims' => is_numeric($row['sn_mims'] ?? null)
                                ? (int) $row['sn_mims']
                                : null,
            ]
        );
    }
}
    }
