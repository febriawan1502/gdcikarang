<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('material_masuk')
            ->whereIn(DB::raw('LOWER(status_sap)'), ['belum selesai sap'])
            ->update(['status_sap' => 'Belum SAP']);
    }

    public function down(): void
    {
        DB::table('material_masuk')
            ->whereIn(DB::raw('LOWER(status_sap)'), ['belum sap'])
            ->update(['status_sap' => 'Belum Selesai SAP']);
    }
};
