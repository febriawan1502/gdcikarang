<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MaterialMasuk;
use App\Models\MaterialMasukDetail;
use App\Models\Material;
// use App\Models\MaterialMovement; // Model belum ada
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MaterialMasukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil beberapa material yang sudah ada
        $materials = Material::take(10)->get();
        
        if ($materials->count() < 5) {
            $this->command->warn('Tidak cukup material di database. Pastikan MaterialSeeder sudah dijalankan.');
            return;
        }

        DB::beginTransaction();
        try {
            // Data sample material masuk
            $materialMasukData = [
                [
                    'nomor_kr' => 'KR-001/2024',
                    'pabrikan' => 'PT. Supplier Utama',
                    'tanggal_masuk' => Carbon::now()->subDays(30),
                    'keterangan' => 'Pengadaan material rutin bulan ini',
                    'created_by' => 1
                ],
                [
                    'nomor_kr' => 'KR-002/2024',
                    'pabrikan' => 'PT. Indo Material',
                    'tanggal_masuk' => Carbon::now()->subDays(25),
                    'keterangan' => 'Material untuk proyek khusus',
                    'created_by' => 1
                ],
                [
                    'nomor_kr' => 'KR-003/2024',
                    'pabrikan' => 'CV. Material Jaya',
                    'tanggal_masuk' => Carbon::now()->subDays(20),
                    'keterangan' => 'Restok material habis',
                    'created_by' => 1
                ],
                [
                    'nomor_kr' => 'KR-004/2024',
                    'pabrikan' => 'PT. Global Supply',
                    'tanggal_masuk' => Carbon::now()->subDays(15),
                    'keterangan' => 'Material import dari supplier luar',
                    'created_by' => 1
                ],
                [
                    'nomor_kr' => 'KR-005/2024',
                    'pabrikan' => 'PT. Nusantara Material',
                    'tanggal_masuk' => Carbon::now()->subDays(10),
                    'keterangan' => 'Pengadaan material darurat',
                    'created_by' => 1
                ]
            ];

            foreach ($materialMasukData as $index => $data) {
                // Buat record material masuk
                $materialMasuk = MaterialMasuk::create($data);

                // Buat detail material masuk (1-3 material per transaksi)
                $numMaterials = rand(1, 3);
                $selectedMaterials = $materials->random($numMaterials);

                foreach ($selectedMaterials as $material) {
                    $quantity = rand(10, 100);
                    $satuan = ['PCS', 'KG', 'LITER', 'METER'][rand(0, 3)];

                    // Simpan detail
                    MaterialMasukDetail::create([
                        'material_masuk_id' => $materialMasuk->id,
                        'material_id' => $material->id,
                        'quantity' => $quantity,
                        'satuan' => $satuan,
                        'keterangan' => 'Detail material masuk ' . ($index + 1)
                    ]);

                    // Update stok material
                    $material->increment('unrestricted_use_stock', $quantity);
                    $material->increment('qty', $quantity);

                    // TODO: Catat movement ketika MaterialMovement model sudah dibuat
                    // MaterialMovement::create([...]);
                }
            }

            DB::commit();
            $this->command->info('MaterialMasukSeeder berhasil dijalankan. 5 data material masuk telah ditambahkan.');
        } catch (\Exception $e) {
            DB::rollback();
            $this->command->error('Error saat menjalankan MaterialMasukSeeder: ' . $e->getMessage());
        }
    }
}