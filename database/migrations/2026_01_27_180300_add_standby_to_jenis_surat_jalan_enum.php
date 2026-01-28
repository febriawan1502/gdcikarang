<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surat_jalan', function (Blueprint $table) {
            $table->text('jenis_temp')->nullable();
        });

        DB::statement('UPDATE surat_jalan SET jenis_temp = jenis_surat_jalan');
        Schema::table('surat_jalan', function (Blueprint $table) {
            $table->dropColumn('jenis_surat_jalan');
        });
        Schema::table('surat_jalan', function (Blueprint $table) {
            $table->enum('jenis_surat_jalan', ['Normal', 'Garansi', 'Peminjaman', 'Perbaikan', 'Manual', 'Rusak', 'Standby'])
                ->default('Normal')
                ->after('nomor_surat');
        });
        DB::statement('UPDATE surat_jalan SET jenis_surat_jalan = jenis_temp');
        Schema::table('surat_jalan', function (Blueprint $table) {
            $table->dropColumn('jenis_temp');
        });
    }

    public function down(): void
    {
        Schema::table('surat_jalan', function (Blueprint $table) {
            $table->text('jenis_temp')->nullable();
        });

        DB::statement('UPDATE surat_jalan SET jenis_temp = jenis_surat_jalan');
        Schema::table('surat_jalan', function (Blueprint $table) {
            $table->dropColumn('jenis_surat_jalan');
        });
        Schema::table('surat_jalan', function (Blueprint $table) {
            $table->enum('jenis_surat_jalan', ['Normal', 'Garansi', 'Peminjaman', 'Perbaikan', 'Manual', 'Rusak'])
                ->default('Normal')
                ->after('nomor_surat');
        });
        DB::statement('UPDATE surat_jalan SET jenis_surat_jalan = jenis_temp');
        Schema::table('surat_jalan', function (Blueprint $table) {
            $table->dropColumn('jenis_temp');
        });
    }
};
