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
    Schema::table('surat_jalan', function (Blueprint $table) {
        $table->timestamp('security_checked_at')
              ->nullable()
              ->after('approved_at');
    });
}

public function down(): void
{
    Schema::table('surat_jalan', function (Blueprint $table) {
        $table->dropColumn('security_checked_at');
    });
}

};
