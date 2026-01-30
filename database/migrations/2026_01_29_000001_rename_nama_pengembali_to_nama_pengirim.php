<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('material_mrwi', function (Blueprint $table) {
            $table->renameColumn('nama_pengembali', 'nama_pengirim');
        });
    }

    public function down(): void
    {
        Schema::table('material_mrwi', function (Blueprint $table) {
            $table->renameColumn('nama_pengirim', 'nama_pengembali');
        });
    }
};
