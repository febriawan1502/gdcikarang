<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitsSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            ['code' => 'UP3-PWK', 'name' => 'UP3 Purwakarta', 'plant' => '5318', 'storage_location' => '2070', 'is_induk' => false],
            ['code' => 'UP3-DPK', 'name' => 'UP3 Depok', 'plant' => '5323', 'storage_location' => '2120', 'is_induk' => false],
            ['code' => 'UP3-SMD', 'name' => 'UP3 Sumedang', 'plant' => '5325', 'storage_location' => '2140', 'is_induk' => false],
            ['code' => 'UP3-CKR', 'name' => 'UP3 Cikarang', 'plant' => '5328', 'storage_location' => '2011', 'is_induk' => false],
            ['code' => 'UP3-KRW', 'name' => 'UP3 Karawang', 'plant' => '5324', 'storage_location' => '2130', 'is_induk' => false],
            ['code' => 'UP3-BGR', 'name' => 'UP3 Bogor', 'plant' => '5316', 'storage_location' => '2050', 'is_induk' => false],
            ['code' => 'UP3-TSM', 'name' => 'UP3 Tasikmalaya', 'plant' => '5312', 'storage_location' => '2010', 'is_induk' => false],
            ['code' => 'UP3-BKS', 'name' => 'UP3 Bekasi', 'plant' => '5322', 'storage_location' => '2110', 'is_induk' => false],
            ['code' => 'UP3-BDG', 'name' => 'UP3 Bandung', 'plant' => '5320', 'storage_location' => '2022', 'is_induk' => false],
            ['code' => 'UP3-SUK', 'name' => 'UP3 Sukabumi', 'plant' => '5314', 'storage_location' => '2040', 'is_induk' => false],
            ['code' => 'UP3-MJL', 'name' => 'UP3 Majalaya', 'plant' => '5321', 'storage_location' => '2100', 'is_induk' => false],
            ['code' => 'UP3-CRB', 'name' => 'UP3 Cirebon', 'plant' => '5311', 'storage_location' => '2000', 'is_induk' => false],
            ['code' => 'UP3-IDR', 'name' => 'UP3 Indramayu', 'plant' => '5329', 'storage_location' => '2016', 'is_induk' => false],
            ['code' => 'UP3-CMH', 'name' => 'UP3 Cimahi', 'plant' => '5319', 'storage_location' => '2080', 'is_induk' => false],
            ['code' => 'UP3-CJR', 'name' => 'UP3 Cianjur', 'plant' => '5314', 'storage_location' => '2030', 'is_induk' => false],
            ['code' => 'UP3-GRT', 'name' => 'UP3 Garut', 'plant' => '5313', 'storage_location' => '2020', 'is_induk' => false],
            ['code' => 'UP3-GPU', 'name' => 'UP3 Gunung Putri', 'plant' => '5327', 'storage_location' => '2170', 'is_induk' => false],
            ['code' => 'UP2D-BDG', 'name' => 'UP2D Bandung', 'plant' => '5356', 'storage_location' => '2150', 'is_induk' => false],
            ['code' => 'UID-JBR', 'name' => 'UID Jabar', 'plant' => '5300', 'storage_location' => '53IP', 'is_induk' => true],
        ];

        foreach ($units as $unit) {
            DB::table('units')->updateOrInsert(
                ['code' => $unit['code']],
                $unit
            );
        }
    }
}
