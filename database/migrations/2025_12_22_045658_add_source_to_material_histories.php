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
        Schema::table('material_histories', function (Blueprint $table) {
    $table->string('source_type')->after('material_id');
    $table->unsignedBigInteger('source_id')->after('source_type');

    $table->index(['source_type', 'source_id']);
});

    }

    /**
     * Reverse the migrations.
     */
public function down(): void
{
    Schema::table('material_histories', function (Blueprint $table) {
        $table->dropIndex(['source_type', 'source_id']);
        $table->dropColumn(['source_type', 'source_id']);
    });
}
};
