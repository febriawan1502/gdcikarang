<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $trafoBrands = [
            'Trafindo',
            'B&D',
            'Weltraf',
            'Tri Putra',
            'Montana',
            'Amtra',
            'Master Green',
            'Unindo',
            'Lain-Lain'
        ];

        $kubikelBrands = [
            'EGA',
            'GAE',
            'MG',
            'Ulusoy',
            'Arlisco',
            'Tri Putra',
            'Lain-Lain'
        ];

        Setting::updateOrCreate(
            ['key' => 'mrwi_brands_trafo'],
            [
                'value' => json_encode($trafoBrands),
                'type' => 'json',
                'group' => 'mrwi',
                'label' => 'Daftar Merk Trafo',
                'description' => 'Daftar pilihan merk untuk material Trafo MRWI',
            ]
        );

        Setting::updateOrCreate(
            ['key' => 'mrwi_brands_kubikel'],
            [
                'value' => json_encode($kubikelBrands),
                'type' => 'json',
                'group' => 'mrwi',
                'label' => 'Daftar Merk Kubikel',
                'description' => 'Daftar pilihan merk untuk material Kubikel MRWI',
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Setting::where('key', 'mrwi_brands_trafo')->delete();
        Setting::where('key', 'mrwi_brands_kubikel')->delete();
    }
};
