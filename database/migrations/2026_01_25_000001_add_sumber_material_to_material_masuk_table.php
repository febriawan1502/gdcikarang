<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('material_masuk', function (Blueprint $table) {
            $table->string('sumber_material', 100)->nullable()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('material_masuk', function (Blueprint $table) {
            $table->dropColumn('sumber_material');
        });
    }
};
