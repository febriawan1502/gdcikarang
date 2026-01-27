<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('material_mrwi_stocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('material_id');
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->unsignedInteger('standby_stock')->default(0);
            $table->unsignedInteger('garansi_stock')->default(0);
            $table->unsignedInteger('perbaikan_stock')->default(0);
            $table->unsignedInteger('rusak_stock')->default(0);
            $table->timestamps();

            $table->unique(['material_id', 'unit_id'], 'mrwi_stocks_material_unit_uq');
            $table->index(['standby_stock', 'garansi_stock', 'perbaikan_stock', 'rusak_stock'], 'mrwi_stocks_qty_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('material_mrwi_stocks');
    }
};
