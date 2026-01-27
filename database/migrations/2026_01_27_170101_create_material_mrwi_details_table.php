<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('material_mrwi_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('material_mrwi_id');
            $table->unsignedBigInteger('material_id')->nullable();
            $table->string('no_material')->nullable();
            $table->string('nama_material');
            $table->unsignedInteger('qty');
            $table->string('satuan', 20);
            $table->string('serial_number')->nullable();
            $table->string('attb_limbah')->nullable();
            $table->string('status_anggaran')->nullable();
            $table->string('no_asset')->nullable();
            $table->string('nama_pabrikan')->nullable();
            $table->string('tahun_buat', 10)->nullable();
            $table->string('id_pelanggan')->nullable();
            $table->unsignedTinyInteger('klasifikasi');
            $table->string('no_polis')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->index(['material_mrwi_id']);
            $table->index(['material_id']);
            $table->index(['klasifikasi']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('material_mrwi_details');
    }
};
