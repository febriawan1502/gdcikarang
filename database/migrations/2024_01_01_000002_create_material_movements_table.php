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
        Schema::create('material_movements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('material_id');
            $table->enum('jenis_transaksi', ['masuk', 'keluar', 'adjustment']);
            $table->integer('jumlah');
            $table->integer('stok_sebelum');
            $table->integer('stok_sesudah');
            $table->decimal('harga_satuan', 15, 2)->nullable();
            $table->decimal('total_nilai', 15, 2)->nullable();
            $table->string('nomor_dokumen')->nullable();
            $table->date('tanggal_transaksi');
            $table->string('supplier')->nullable();
            $table->string('customer')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('referensi')->nullable(); // untuk link ke surat jalan, dll
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            
            $table->foreign('material_id')->references('id')->on('materials')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            
            $table->index(['material_id', 'tanggal_transaksi']);
            $table->index(['jenis_transaksi', 'tanggal_transaksi']);
            $table->index('nomor_dokumen');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_movements');
    }
};