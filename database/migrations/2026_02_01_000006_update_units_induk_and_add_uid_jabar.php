<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('units')
            ->where('code', 'UP2D-BDG')
            ->update(['is_induk' => false]);

        DB::table('units')->updateOrInsert(
            ['code' => 'UID-JBR'],
            [
                'name' => 'UID Jabar',
                'plant' => '5300',
                'storage_location' => '53IP',
                'is_induk' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    public function down(): void
    {
        DB::table('units')
            ->where('code', 'UP2D-BDG')
            ->update(['is_induk' => true]);

        DB::table('units')
            ->where('code', 'UID-JBR')
            ->delete();
    }
};
