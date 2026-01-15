<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('material_histories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('material_id')
                ->constrained()
                ->cascadeOnDelete();

            // tanggal transaksi
            $table->date('tanggal');

            // tipe histori
            $table->enum('tipe', ['masuk', 'keluar']);

            // nomor dokumen / slip
            $table->string('no_slip')->nullable();

            // qty masuk & keluar
            $table->integer('masuk')->default(0);
            $table->integer('keluar')->default(0);

            // stok setelah transaksi
            $table->integer('sisa_persediaan')->default(0);

            // catatan histori
            $table->text('catatan')->nullable();

            // relasi surat jalan (optional)
            $table->unsignedBigInteger('surat_jalan_id')->nullable();
            $table->foreign('surat_jalan_id')
                ->references('id')
                ->on('surat_jalan')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('material_histories');
    }
};
