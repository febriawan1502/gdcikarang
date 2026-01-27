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
        Schema::create('material_mrwi_serial_moves', function (Blueprint $table) {
            $table->id();
            $table->string('serial_number');
            $table->unsignedBigInteger('material_id');
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->enum('jenis', ['masuk', 'keluar', 'kembali']);
            $table->enum('status_bucket', ['standby', 'garansi', 'perbaikan', 'rusak']);
            $table->unsignedBigInteger('surat_jalan_id')->nullable();
            $table->unsignedBigInteger('surat_jalan_detail_id')->nullable();
            $table->string('reference_type')->nullable();
            $table->string('reference_number')->nullable();
            $table->date('tanggal')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index(['serial_number']);
            $table->index(['material_id', 'unit_id']);
            $table->index(['surat_jalan_id']);

            $table->foreign('material_id')->references('id')->on('materials');
            $table->foreign('surat_jalan_id')->references('id')->on('surat_jalan')->nullOnDelete();
            $table->foreign('surat_jalan_detail_id')->references('id')->on('surat_jalan_detail')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_mrwi_serial_moves');
    }
};
