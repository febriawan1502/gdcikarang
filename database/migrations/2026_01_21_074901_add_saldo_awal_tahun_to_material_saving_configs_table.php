<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('material_saving_config', function (Blueprint $table) {
            $table->decimal('saldo_awal_tahun', 15, 2)->default(0)->after('usul_hapus');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('material_saving_config', function (Blueprint $table) {
            $table->dropColumn('saldo_awal_tahun');
        });
    }
};
