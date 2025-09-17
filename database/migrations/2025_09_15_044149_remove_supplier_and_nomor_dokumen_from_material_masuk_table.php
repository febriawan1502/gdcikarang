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
        Schema::table('material_masuk', function (Blueprint $table) {
            $table->dropIndex(['supplier']); // Drop index first
            $table->dropColumn(['supplier', 'nomor_dokumen']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('material_masuk', function (Blueprint $table) {
            $table->string('supplier')->nullable();
            $table->string('nomor_dokumen')->nullable();
            $table->index('supplier');
        });
    }
};
