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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('text'); // text, number, boolean
            $table->string('group')->default('general'); // general, company, etc
            $table->string('label');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        DB::table('settings')->insert([
            [
                'key' => 'company_name',
                'value' => 'PT PLN (Persero)',
                'type' => 'text',
                'group' => 'company',
                'label' => 'Nama Perusahaan',
                'description' => 'Nama perusahaan yang ditampilkan di dokumen',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'up3_name',
                'value' => 'UP3 Cimahi',
                'type' => 'text',
                'group' => 'company',
                'label' => 'Nama UP3',
                'description' => 'Nama UP3 yang ditampilkan di dokumen dan label',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'warehouse_location',
                'value' => 'Gudang Material UP3 Cimahi',
                'type' => 'text',
                'group' => 'company',
                'label' => 'Lokasi Gudang',
                'description' => 'Alamat atau lokasi gudang',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
