<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('surat_jalan_detail', function (Blueprint $table) {
            if (!Schema::hasColumn('surat_jalan_detail', 'serial_numbers')) {
                $table->json('serial_numbers')->nullable()->after('satuan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_jalan_detail', function (Blueprint $table) {
            if (Schema::hasColumn('surat_jalan_detail', 'serial_numbers')) {
                $table->dropColumn('serial_numbers');
            }
        });
    }
};
