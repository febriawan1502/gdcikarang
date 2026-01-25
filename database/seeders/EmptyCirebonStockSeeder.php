<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Material;
use App\Models\MaterialStock;
use App\Models\Unit;

class EmptyCirebonStockSeeder extends Seeder
{
    public function run(): void
    {
        $unit = Unit::where('code', 'UP3-CRB')->first();
        if (!$unit) {
            $this->command?->error('Unit UP3-CRB tidak ditemukan.');
            return;
        }

        $materials = Material::select('id')->get();

        foreach ($materials as $material) {
            MaterialStock::withoutGlobalScopes()->updateOrCreate(
                [
                    'material_id' => $material->id,
                    'unit_id' => $unit->id,
                ],
                [
                    'unrestricted_use_stock' => 0,
                    'quality_inspection_stock' => 0,
                    'blocked_stock' => 0,
                    'in_transit_stock' => 0,
                    'project_stock' => 0,
                    'qty' => 0,
                    'min_stock' => 0,
                ]
            );
        }
    }
}
