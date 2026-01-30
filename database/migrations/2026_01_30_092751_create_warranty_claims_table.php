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
        Schema::create('warranty_claims', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique(); // WC-YYYYMMDD-XXXX
            $table->unsignedBigInteger('material_id');
            $table->string('serial_number');
            $table->string('id_pelanggan')->nullable();
            $table->string('evidence_path')->nullable();
            $table->enum('status', ['SUBMITTED', 'PROCESSED', 'COMPLETED'])->default('SUBMITTED');

            $table->timestamp('submission_date')->useCurrent();

            $table->unsignedBigInteger('pickup_surat_jalan_id')->nullable();
            $table->timestamp('pickup_date')->nullable();

            $table->timestamp('return_date')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');

            $table->foreign('material_id')->references('id')->on('materials');
            $table->foreign('pickup_surat_jalan_id')->references('id')->on('surat_jalan')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warranty_claims');
    }
};
