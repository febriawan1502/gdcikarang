<?php

namespace App\Imports;

use App\Models\Material;
use App\Models\MaterialStock;
use App\Models\MaterialHistory;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;

class MaterialImport implements ToCollection, WithHeadingRow
{
    use Importable;
    
    public $successCount = 0;
    public $errorCount = 0;
    public $errors = [];
    
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // +2 karena index dimulai dari 0 dan skip header
            
            try {
                // Skip empty rows
                if ($row->filter()->isEmpty()) {
                    continue;
                }
                
                // Map Excel columns to database fields (WithHeadingRow converts to lowercase with underscores)
                $materialData = [
                    'company_code' => $row['company_code'] ?? '',
                    'company_code_description' => $row['company_code_description'] ?? '',
                    'plant' => $row['plant'] ?? '',
                    'plant_description' => $row['plant_description'] ?? '',
                    'storage_location' => $row['storage_location'] ?? '',
                    'storage_location_description' => $row['storage_location_description'] ?? '',
                    'material_type' => $row['material_type'] ?? '',
                    'material_type_description' => $row['material_type_description'] ?? '',
                    'material_code' => $row['material'] ?? '',
                    'material_description' => $row['material_description'] ?? '',
                    'material_group' => $row['material_group'] ?? '',
                    'base_unit_of_measure' => $row['base_unit_of_measure'] ?? '',
                    'valuation_type' => $row['valuation_type'] ?? '',
                    'valuation_class' => $row['valuation_class'] ?? '',
                    'valuation_description' => $row['valuation_description'] ?? '',
                    'harga_satuan' => is_numeric($row['harga_satuan']) ? (float)$row['harga_satuan'] : 0,
                    'currency' => $row['currency'] ?? 'IDR',
                    'pabrikan' => $row['pabrikan'] ?? '',
                    'tanggal_terima' => !empty($row['tanggal_terima']) ? Carbon::parse($row['tanggal_terima']) : Carbon::now(),
                    'keterangan' => $row['keterangan'] ?? '',
                    'rak' => $row['rak'] ?? '',
                    'status' => !empty($row['status']) ? $row['status'] : Material::STATUS_BAIK,
                    'created_by' => auth()->id() ?? 1,
                    'created_at' => Carbon::now(),
                ];
                $stockData = [
                    'unrestricted_use_stock' => is_numeric($row['unrestricted_use_stock']) ? (float) $row['unrestricted_use_stock'] : 0,
                    'quality_inspection_stock' => is_numeric($row['quality_inspection_stock']) ? (float) $row['quality_inspection_stock'] : 0,
                    'blocked_stock' => is_numeric($row['blocked_stock']) ? (float) $row['blocked_stock'] : 0,
                    'in_transit_stock' => is_numeric($row['in_transit_stock'] ?? 0) ? (float) ($row['in_transit_stock'] ?? 0) : 0,
                    'project_stock' => is_numeric($row['project_stock'] ?? 0) ? (float) ($row['project_stock'] ?? 0) : 0,
                    'qty' => is_numeric($row['unrestricted_use_stock']) ? (int) $row['unrestricted_use_stock'] : 0,
                    'min_stock' => is_numeric($row['min_stock'] ?? null) ? (int) $row['min_stock'] : 0,
                ];
                
                // Set nomor manually to avoid transaction issues
                $materialData['nomor'] = time() + $rowNumber; // Simple unique number
                
                // Validate required fields
                if (empty($materialData['material_code']) || empty($materialData['material_description'])) {
                    $this->errors[] = [
                        'row' => $rowNumber,
                        'material_code' => $materialData['material_code'] ?? 'N/A',
                        'material_description' => $materialData['material_description'] ?? 'N/A',
                        'error' => 'Material Code dan Description wajib diisi'
                    ];
                    $this->errorCount++;
                    continue;
                }
                
                // Nomor sudah di-set manual di atas untuk menghindari masalah transaction
                
                // Use updateOrCreate to handle duplicates, prioritizing stock updates
                $material = Material::updateOrCreate(
                    ['material_code' => $materialData['material_code']],
                    array_merge($materialData, [
                        'updated_at' => Carbon::now()
                    ])
                );

                $unitId = auth()->user()->unit_id ?? null;
                if (!$unitId) {
                    $this->errors[] = [
                        'row' => $rowNumber,
                        'material_code' => $materialData['material_code'] ?? 'N/A',
                        'material_description' => $materialData['material_description'] ?? 'N/A',
                        'error' => 'Unit user belum diset. Import stok membutuhkan unit.',
                    ];
                    $this->errorCount++;
                    continue;
                }

                MaterialStock::withoutGlobalScopes()->updateOrCreate(
                    [
                        'material_id' => $material->id,
                        'unit_id' => $unitId,
                    ],
                    $stockData
                );

                if ($stockData['unrestricted_use_stock'] > 0) {
                    $hasHistory = MaterialHistory::where('material_id', $material->id)
                        ->where('unit_id', $unitId)
                        ->exists();

                    if (!$hasHistory) {
                        MaterialHistory::create([
                            'material_id' => $material->id,
                            'source_type' => 'material_import',
                            'source_id' => 0,
                            'unit_id' => $unitId,
                            'tanggal' => $materialData['tanggal_terima'] ?? Carbon::now(),
                            'tipe' => 'masuk',
                            'no_slip' => '-',
                            'masuk' => (int) $stockData['unrestricted_use_stock'],
                            'keluar' => 0,
                            'sisa_persediaan' => (int) $stockData['unrestricted_use_stock'],
                            'catatan' => 'Stok awal (import material)',
                        ]);
                    }
                }

                $this->successCount++;
                
            } catch (\Exception $e) {
                $materialCode = $row['material'] ?? 'N/A';
                $materialDescription = $row['material_description'] ?? 'N/A';
                
                $this->errors[] = [
                    'row' => $rowNumber,
                    'material_code' => $materialCode,
                    'material_description' => $materialDescription,
                    'error' => $e->getMessage()
                ];
                $this->errorCount++;
                Log::error("Material import error on row {$rowNumber} - Code: {$materialCode}, Description: {$materialDescription}, Error: " . $e->getMessage());
            }
        }
    }
    
    public function getResults()
    {
        return [
            'success_count' => $this->successCount,
            'error_count' => $this->errorCount,
            'errors' => $this->errors
        ];
    }
}
