<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('unit_id')->nullable()->after('id');
            $table->index('unit_id');
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('set null');
        });

        Schema::table('surat_jalan', function (Blueprint $table) {
            $table->unsignedBigInteger('unit_id')->nullable()->after('id');
            $table->index('unit_id');
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('set null');
            $table->dropUnique(['nomor_surat']);
            $table->unique(['unit_id', 'nomor_surat']);
        });

        Schema::table('surat_jalan_detail', function (Blueprint $table) {
            $table->unsignedBigInteger('unit_id')->nullable()->after('id');
            $table->index('unit_id');
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('set null');
        });

        Schema::table('material_masuk', function (Blueprint $table) {
            $table->unsignedBigInteger('unit_id')->nullable()->after('id');
            $table->index('unit_id');
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('set null');
        });

        Schema::table('material_masuk_detail', function (Blueprint $table) {
            $table->unsignedBigInteger('unit_id')->nullable()->after('id');
            $table->index('unit_id');
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('set null');
        });

        Schema::table('material_movements', function (Blueprint $table) {
            $table->unsignedBigInteger('unit_id')->nullable()->after('id');
            $table->index('unit_id');
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('set null');
        });

        Schema::table('material_histories', function (Blueprint $table) {
            $table->unsignedBigInteger('unit_id')->nullable()->after('id');
            $table->index('unit_id');
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('set null');
        });

        Schema::table('stock_opnames', function (Blueprint $table) {
            $table->unsignedBigInteger('unit_id')->nullable()->after('id');
            $table->index('unit_id');
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('set null');
        });

        Schema::table('pengembalian_histories', function (Blueprint $table) {
            $table->unsignedBigInteger('unit_id')->nullable()->after('id');
            $table->index('unit_id');
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('set null');
        });

        Schema::table('berita_acaras', function (Blueprint $table) {
            $table->unsignedBigInteger('unit_id')->nullable()->after('id');
            $table->index('unit_id');
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('set null');
        });

        Schema::table('pemeriksaan_fisik', function (Blueprint $table) {
            $table->unsignedBigInteger('unit_id')->nullable()->after('id');
            $table->index('unit_id');
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('set null');
        });

        Schema::table('monitorings', function (Blueprint $table) {
            $table->unsignedBigInteger('unit_id')->nullable()->after('id');
            $table->index('unit_id');
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('monitorings', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->dropIndex(['unit_id']);
            $table->dropColumn('unit_id');
        });

        Schema::table('pemeriksaan_fisik', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->dropIndex(['unit_id']);
            $table->dropColumn('unit_id');
        });

        Schema::table('berita_acaras', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->dropIndex(['unit_id']);
            $table->dropColumn('unit_id');
        });

        Schema::table('pengembalian_histories', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->dropIndex(['unit_id']);
            $table->dropColumn('unit_id');
        });

        Schema::table('stock_opnames', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->dropIndex(['unit_id']);
            $table->dropColumn('unit_id');
        });

        Schema::table('material_histories', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->dropIndex(['unit_id']);
            $table->dropColumn('unit_id');
        });

        Schema::table('material_movements', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->dropIndex(['unit_id']);
            $table->dropColumn('unit_id');
        });

        Schema::table('material_masuk_detail', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->dropIndex(['unit_id']);
            $table->dropColumn('unit_id');
        });

        Schema::table('material_masuk', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->dropIndex(['unit_id']);
            $table->dropColumn('unit_id');
        });

        Schema::table('surat_jalan_detail', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->dropIndex(['unit_id']);
            $table->dropColumn('unit_id');
        });

        Schema::table('surat_jalan', function (Blueprint $table) {
            $table->dropUnique(['unit_id', 'nomor_surat']);
            $table->unique('nomor_surat');
            $table->dropForeign(['unit_id']);
            $table->dropIndex(['unit_id']);
            $table->dropColumn('unit_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->dropIndex(['unit_id']);
            $table->dropColumn('unit_id');
        });
    }
};
