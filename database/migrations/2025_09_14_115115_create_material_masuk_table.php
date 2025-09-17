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
        Schema::create('material_masuk', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_kr')->nullable();
            $table->string('pabrikan')->nullable();
            $table->date('tanggal_masuk');
            $table->string('supplier')->nullable();
            $table->string('nomor_dokumen')->nullable();
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            
            // Indexes
            $table->index(['tanggal_masuk', 'created_at']);
            $table->index('nomor_kr');
            $table->index('supplier');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_masuk');
    }
};
