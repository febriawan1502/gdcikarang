<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $unitId = DB::table('units')
            ->where('code', 'UID-JBR')
            ->value('id');

        if (!$unitId) {
            return;
        }

        DB::table('users')
            ->where('email', 'admin@asi.com')
            ->update(['unit_id' => $unitId]);
    }

    public function down(): void
    {
        DB::table('users')
            ->where('email', 'admin@asi.com')
            ->update(['unit_id' => null]);
    }
};
