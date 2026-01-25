<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('units', 'kode_surat')) {
            Schema::table('units', function (Blueprint $table) {
                $table->string('kode_surat', 30)->default('F02050000')->after('storage_location');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('units', 'kode_surat')) {
            Schema::table('units', function (Blueprint $table) {
                $table->dropColumn('kode_surat');
            });
        }
    }
};
