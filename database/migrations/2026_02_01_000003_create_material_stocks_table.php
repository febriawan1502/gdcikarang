<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('material_stocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('material_id');
            $table->unsignedBigInteger('unit_id');
            $table->decimal('unrestricted_use_stock', 15, 3)->default(0);
            $table->decimal('quality_inspection_stock', 15, 3)->default(0);
            $table->decimal('blocked_stock', 15, 3)->default(0);
            $table->decimal('in_transit_stock', 15, 3)->default(0);
            $table->decimal('project_stock', 15, 3)->default(0);
            $table->integer('qty')->default(0);
            $table->integer('min_stock')->default(0);
            $table->timestamps();

            $table->unique(['material_id', 'unit_id']);
            $table->index('unit_id');

            $table->foreign('material_id')->references('id')->on('materials')->onDelete('cascade');
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('material_stocks');
    }
};
