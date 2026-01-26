<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateUnitKodeSuratSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('units')
            ->where('code', 'UP3-CKR')
            ->update(['kode_surat' => 'F02180000']);
    }
}
