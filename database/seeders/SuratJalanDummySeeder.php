<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SuratJalan;
use App\Models\SuratJalanDetail;
use App\Models\Material;
use App\Models\User;
use Carbon\Carbon;

class SuratJalanDummySeeder extends Seeder
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

        // Ambil semua material yang ada
        $materials = Material::all();
        
        if ($materials->count() < 5) {
            $this->command->error('Minimal 5 material diperlukan. Silakan buat material terlebih dahulu.');
            return;
        }

        $jenisSuratJalan = ['Normal', 'Garansi', 'Peminjaman', 'Perbaikan'];
        $statusList = ['BUTUH_PERSETUJUAN', 'APPROVED', 'SELESAI'];
        $vendors = [
            'PT PLN Unit Induk Distribusi Jawa Barat',
            'UP3 Cirebon',
            'UP3 Bandung',
            'UP3 Bekasi',
            'CV Karya Mandiri',
            'PT Mitra Sejahtera',
            'PT Bangun Jaya',
            'PT Sukses Makmur',
            'CV Elektro Nusantara',
            'PT Cahaya Listrik'
        ];

        $pekerjaan = [
            'PD G.SSJ',
            'PB PD Rutin',
            'STO',
            'Pemeliharaan Jaringan',
            'Perbaikan Gardu',
            'Instalasi Baru',
            'Penggantian Kabel',
            'Upgrade Transformator'
        ];

        $this->command->info('Membuat 20 surat jalan dummy...');

        for ($i = 1; $i <= 20; $i++) {
            // Random tanggal dalam 30 hari terakhir
            $tanggal = Carbon::now()->subDays(rand(1, 30));
            
            // Random jenis surat jalan
            $jenis = $jenisSuratJalan[array_rand($jenisSuratJalan)];
            
            // Random status
            $status = $statusList[array_rand($statusList)];
            
            // Generate nomor surat
            $sequence = str_pad($i, 3, '0', STR_PAD_LEFT);
            $bulanRomawi = $this->getMonthRoman($tanggal->month);
            $tahun = $tanggal->year;
            
            $jenisKode = [
                'Normal' => 'SJ',
                'Garansi' => 'GRN',
                'Peminjaman' => 'PMJ',
                'Perbaikan' => 'PBK',
            ];
            
            $kode = $jenisKode[$jenis];
            $nomorSurat = "{$sequence}.{$kode}/LOG.00.02/F02050000/{$bulanRomawi}/{$tahun}";
            
            // Data surat jalan
            $suratJalanData = [
                'nomor_surat' => $nomorSurat,
                'jenis_surat_jalan' => $jenis,
                'tanggal' => $tanggal,
                'kepada' => $vendors[array_rand($vendors)],
                'berdasarkan' => 'SLIP ' . rand(10000000, 99999999),
                'security' => rand(0, 1) ? 'Supardi' : 'Budi Santoso',
                'keterangan' => $pekerjaan[array_rand($pekerjaan)],
                'nomor_slip' => 'TUG' . rand(8, 9) . '-' . rand(1000, 9999),
                'kendaraan' => ['Avanza', 'Xenia', 'Innova', 'Elf', 'Colt Diesel'][array_rand(['Avanza', 'Xenia', 'Innova', 'Elf', 'Colt Diesel'])],
                'no_polisi' => 'E ' . rand(1000, 9999) . ' ' . ['DF', 'DG', 'DH', 'DK'][array_rand(['DF', 'DG', 'DH', 'DK'])],
                'pengemudi' => ['Febri', 'Andi', 'Dedi', 'Rudi', 'Hendra'][array_rand(['Febri', 'Andi', 'Dedi', 'Rudi', 'Hendra'])],
                'status' => $status,
                'created_by' => $user->id,
            ];

            // Jika status APPROVED atau SELESAI, tambahkan approved_by dan approved_at
            if (in_array($status, ['APPROVED', 'SELESAI'])) {
                $suratJalanData['approved_by'] = $user->id;
                $suratJalanData['approved_at'] = $tanggal->copy()->addHours(rand(1, 24));
            }

            $suratJalan = SuratJalan::create($suratJalanData);

            // Random jumlah material per surat jalan (1-10)
            $jumlahMaterial = rand(1, 10);
            
            // Ambil random material
            $selectedMaterials = $materials->random(min($jumlahMaterial, $materials->count()));

            foreach ($selectedMaterials as $material) {
                SuratJalanDetail::create([
                    'surat_jalan_id' => $suratJalan->id,
                    'material_id' => $material->id,
                    'is_manual' => false,
                    'quantity' => rand(1, 100),
                    'satuan' => $material->base_unit_of_measure ?? 'PCS',
                    'keterangan' => rand(0, 1) ? null : 'Material untuk ' . $pekerjaan[array_rand($pekerjaan)],
                ]);
            }

            $this->command->info("✓ Surat Jalan #{$i}: {$nomorSurat} ({$status}) - {$jumlahMaterial} material");
        }

        $this->command->info('');
        $this->command->info('✅ Berhasil membuat 20 surat jalan dummy!');
    }

    /**
     * Konversi angka bulan ke romawi
     */
    private function getMonthRoman($month)
    {
        $romans = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
            7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];
        
        return $romans[$month] ?? 'I';
    }
}
