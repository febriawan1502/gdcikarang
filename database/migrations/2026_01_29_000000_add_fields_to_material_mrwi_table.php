<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('material_mrwi', function (Blueprint $table) {
            $table->string('ulp_pengirim')->nullable()->after('sumber');
            $table->string('ex_gardu')->nullable()->after('ulp_pengirim');
            $table->string('vendor_pengirim')->nullable()->after('ex_gardu');
            $table->string('nama_pengembali')->nullable()->after('vendor_pengirim');
            $table->string('kategori_material')->nullable()->after('nama_pengembali');
            $table->string('kategori_kerusakan')->nullable()->after('kategori_material');
        });
    }

    public function down(): void
    {
        Schema::table('material_mrwi', function (Blueprint $table) {
            $table->dropColumn([
                'ulp_pengirim',
                'ex_gardu',
                'vendor_pengirim',
                'nama_pengembali',
                'kategori_material',
                'kategori_kerusakan',
            ]);
        });
    }
};
