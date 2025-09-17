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
        Schema::table('surat_jalan', function (Blueprint $table) {
            $table->string('kendaraan')->nullable()->after('keterangan');
            $table->string('no_polisi')->nullable()->after('kendaraan');
            $table->string('pengemudi')->nullable()->after('no_polisi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_jalan', function (Blueprint $table) {
            $table->dropColumn(['kendaraan', 'no_polisi', 'pengemudi']);
        });
    }
};
