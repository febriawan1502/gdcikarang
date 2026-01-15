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
        DB::statement("
            ALTER TABLE material_masuk_detail
            CHANGE keterangan normalisasi VARCHAR(255) NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        DB::statement("
            ALTER TABLE material_masuk_detail
            CHANGE normalisasi keterangan VARCHAR(255) NULL
        ");
    }

};