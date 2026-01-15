<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pemeriksaan_fisik', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('material_id');
            $table->string('bulan', 7); // format: YYYY-MM

            $table->integer('sap')->nullable();
            $table->integer('fisik')->nullable();
            $table->integer('sn_mims')->nullable();

            $table->integer('selisih_sf')->nullable();
            $table->integer('selisih_ss')->nullable();
            $table->integer('selisih_fs')->nullable();

            $table->string('justifikasi_sf')->nullable();
            $table->string('justifikasi_ss')->nullable();
            $table->string('justifikasi_fs')->nullable();

            $table->timestamps();

            $table->foreign('material_id')
                  ->references('id')
                  ->on('materials')
                  ->onDelete('cascade');

            $table->unique(['material_id', 'bulan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemeriksaan_fisik');
    }
};
