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
        Schema::create('material_masuk_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('material_masuk_id');
            $table->unsignedBigInteger('material_id');
            $table->integer('quantity');
            $table->string('satuan');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('material_masuk_id')->references('id')->on('material_masuk')->onDelete('cascade');
            $table->foreign('material_id')->references('id')->on('materials')->onDelete('cascade');
            
            // Indexes
            $table->index(['material_masuk_id', 'material_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_masuk_detail');
    }
};
