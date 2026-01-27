<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('material_mrwi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->string('nomor_mrwi')->unique();
            $table->date('tanggal_masuk');
            $table->string('sumber')->nullable();
            $table->string('berdasarkan')->nullable();
            $table->string('lokasi')->nullable();
            $table->enum('status', ['DRAFT', 'MENUNGGU_KLASIFIKASI', 'SELESAI'])->default('DRAFT');
            $table->text('catatan')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->index(['tanggal_masuk', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('material_mrwi');
    }
};
