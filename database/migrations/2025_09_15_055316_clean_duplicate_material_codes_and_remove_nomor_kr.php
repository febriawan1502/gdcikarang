<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Clean duplicate material_codes by updating duplicates with unique codes
        $duplicates = DB::select("
            SELECT material_code, array_agg(id ORDER BY id) as ids
            FROM materials 
            GROUP BY material_code 
            HAVING COUNT(*) > 1
        ");
        
        foreach ($duplicates as $duplicate) {
            $ids = explode(',', trim($duplicate->ids, '{}'));
            // Skip first ID (keep original), update others
            for ($i = 1; $i < count($ids); $i++) {
                $newCode = $duplicate->material_code . '_DUP_' . $i;
                DB::table('materials')
                    ->where('id', $ids[$i])
                    ->update(['material_code' => $newCode]);
            }
        }
        
        Schema::table('materials', function (Blueprint $table) {
            $table->dropColumn('nomor_kr');
            $table->unique('material_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->dropUnique(['material_code']);
            $table->string('nomor_kr')->nullable();
        });
    }
};
