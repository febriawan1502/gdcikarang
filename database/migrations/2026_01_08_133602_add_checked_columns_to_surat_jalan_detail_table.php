<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::table('surat_jalan_detail', function (Blueprint $table) {
            $table->boolean('is_checked')
                  ->default(false)
                  ->after('keterangan');

            $table->unsignedBigInteger('checked_by')
                  ->nullable()
                  ->after('is_checked');

            $table->timestamp('checked_at')
                  ->nullable()
                  ->after('checked_by');

            // Foreign key (opsional tapi disarankan)
            $table->foreign('checked_by')
                  ->references('id')
                  ->on('users')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('surat_jalan_detail', function (Blueprint $table) {
            $table->dropForeign(['checked_by']);
            $table->dropColumn([
                'is_checked',
                'checked_by',
                'checked_at'
            ]);
        });
    }
};
