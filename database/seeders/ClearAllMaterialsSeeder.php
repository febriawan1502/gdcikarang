<?php

namespace Database\Seeders;

use App\Models\Material;
use App\Models\MaterialHistory;
use App\Models\MaterialStock;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClearAllMaterialsSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            MaterialHistory::withoutGlobalScopes()->delete();
            MaterialStock::withoutGlobalScopes()->delete();
            Material::withoutGlobalScopes()->forceDelete();
        });
    }
}
