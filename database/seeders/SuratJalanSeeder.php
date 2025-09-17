<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SuratJalan;
use App\Models\SuratJalanDetail;
use App\Models\Material;
use App\Models\User;
use Carbon\Carbon;

class SuratJalanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil user pertama sebagai creator
        $user = User::first();
        
        if (!$user) {
            $this->command->error('Tidak ada user di database. Silakan buat user terlebih dahulu.');
            return;
        }

        // Ambil material untuk detail
        $materials = Material::take(3)->get();
        
        if ($materials->count() < 3) {
            $this->command->error('Minimal 3 material diperlukan. Silakan buat material terlebih dahulu.');
            return;
        }

        // Data surat jalan yang butuh persetujuan (3 item)
        $suratJalanPending = [
            [
                'nomor_surat' => 'SJ/2024/12/001',
                'tanggal' => Carbon::now()->subDays(5),
                'kepada' => 'PT. Mitra Sejahtera',
                'alamat' => 'Jl. Sudirman No. 123, Jakarta Pusat',
                'keterangan' => 'Pengiriman material untuk proyek pembangunan gedung perkantoran',
                'status' => 'BUTUH_PERSETUJUAN',
                'created_by' => $user->id,
            ],
            [
                'nomor_surat' => 'SJ/2024/12/002',
                'tanggal' => Carbon::now()->subDays(3),
                'kepada' => 'CV. Karya Mandiri',
                'alamat' => 'Jl. Gatot Subroto No. 456, Jakarta Selatan',
                'keterangan' => 'Pengiriman material untuk renovasi pabrik',
                'status' => 'BUTUH_PERSETUJUAN',
                'created_by' => $user->id,
            ],
            [
                'nomor_surat' => 'SJ/2024/12/003',
                'tanggal' => Carbon::now()->subDays(1),
                'kepada' => 'PT. Bangun Jaya',
                'alamat' => 'Jl. HR Rasuna Said No. 789, Jakarta Selatan',
                'keterangan' => 'Pengiriman material untuk proyek infrastruktur jalan',
                'status' => 'BUTUH_PERSETUJUAN',
                'created_by' => $user->id,
            ]
        ];

        // Data surat jalan yang sudah approved (1 item)
        $suratJalanApproved = [
            [
                'nomor_surat' => 'SJ/2024/11/025',
                'tanggal' => Carbon::now()->subDays(10),
                'kepada' => 'PT. Sukses Makmur',
                'alamat' => 'Jl. Thamrin No. 321, Jakarta Pusat',
                'keterangan' => 'Pengiriman material untuk proyek perumahan',
                'status' => 'APPROVED',
                'created_by' => $user->id,
                'approved_by' => $user->id,
                'approved_at' => Carbon::now()->subDays(8),
            ]
        ];

        // Buat surat jalan yang butuh persetujuan
        foreach ($suratJalanPending as $index => $data) {
            $suratJalan = SuratJalan::create($data);
            
            // Tambahkan detail untuk setiap surat jalan
            SuratJalanDetail::create([
                'surat_jalan_id' => $suratJalan->id,
                'material_id' => $materials[$index % $materials->count()]->id,
                'quantity' => rand(10, 100),
                'satuan' => 'pcs',
                'keterangan' => 'Material utama untuk proyek'
            ]);
            
            // Tambahkan detail kedua
            SuratJalanDetail::create([
                'surat_jalan_id' => $suratJalan->id,
                'material_id' => $materials[($index + 1) % $materials->count()]->id,
                'quantity' => rand(5, 50),
                'satuan' => 'unit',
                'keterangan' => 'Material pendukung'
            ]);
            
            $this->command->info("Surat Jalan {$data['nomor_surat']} (BUTUH_PERSETUJUAN) berhasil dibuat.");
        }

        // Buat surat jalan yang sudah approved
        foreach ($suratJalanApproved as $data) {
            $suratJalan = SuratJalan::create($data);
            
            // Tambahkan detail
            SuratJalanDetail::create([
                'surat_jalan_id' => $suratJalan->id,
                'material_id' => $materials[0]->id,
                'quantity' => rand(20, 80),
                'satuan' => 'pcs',
                'keterangan' => 'Material berkualitas tinggi'
            ]);
            
            SuratJalanDetail::create([
                'surat_jalan_id' => $suratJalan->id,
                'material_id' => $materials[1]->id,
                'quantity' => rand(15, 60),
                'satuan' => 'unit',
                'keterangan' => 'Material tambahan'
            ]);
            
            $this->command->info("Surat Jalan {$data['nomor_surat']} (APPROVED) berhasil dibuat.");
        }

        $this->command->info('Seeder SuratJalan selesai dijalankan!');
        $this->command->info('Total: 3 surat jalan butuh persetujuan, 1 surat jalan approved');
    }
}