<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            
            // Nomor urut dan identifikasi
            $table->integer('nomor')->unique();
            $table->string('nomor_kr')->nullable();
            
            // Company dan Plant Information
            $table->string('company_code', 10)->nullable();
            $table->string('company_code_description')->nullable();
            $table->string('plant', 10)->nullable();
            $table->string('plant_description')->nullable();
            $table->string('storage_location', 10)->nullable();
            $table->string('storage_location_description')->nullable();
            
            // Material Information
            $table->string('material_type', 10)->nullable();
            $table->string('material_type_description')->nullable();
            $table->string('material_code', 50)->nullable();
            $table->text('material_description')->nullable();
            $table->string('material_group', 20)->nullable();
            $table->string('base_unit_of_measure', 10)->nullable();
            
            // Stock Information
            $table->string('valuation_type', 20)->nullable();
            $table->decimal('unrestricted_use_stock', 15, 3)->default(0);
            $table->decimal('quality_inspection_stock', 15, 3)->default(0);
            $table->decimal('blocked_stock', 15, 3)->default(0);
            $table->decimal('in_transit_stock', 15, 3)->default(0);
            $table->decimal('project_stock', 15, 3)->default(0);
            $table->string('wbs_element')->nullable();
            
            // Valuation Information
            $table->string('valuation_class', 10)->nullable();
            $table->string('valuation_description')->nullable();
            $table->decimal('harga_satuan', 15, 2)->default(0);
            $table->decimal('total_harga', 15, 2)->default(0);
            $table->string('currency', 3)->default('IDR');
            
            // Additional Information
            $table->string('pabrikan')->nullable();
            $table->string('normalisasi')->nullable();
            $table->integer('qty')->default(0);
            $table->date('tanggal_terima')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('status')->default('PENDING');
            
            // Audit fields
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes untuk performa query
            $table->index(['nomor_kr']);
            $table->index(['pabrikan']);
            $table->index(['material_code']);
            $table->index(['status']);
            $table->index(['tanggal_terima']);
            $table->index(['company_code', 'plant']);
            $table->index(['material_type']);
            $table->index(['qty']);
            $table->index(['created_at']);
            
            // Full text search index untuk material_description (disabled for SQLite)
            // $table->fullText(['material_description', 'keterangan']);
            
            // Foreign keys
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};