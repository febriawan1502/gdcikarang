<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MaterialHistory;
use App\Models\Material;
use Illuminate\Support\Facades\DB;

class FixMaterialHistorySaldoSeeder extends Seeder
{
    /**
     * Perbaiki saldo akhir di material_histories
     * Saldo akhir harus dihitung secara kumulatif berdasarkan urutan tanggal
     */
    public function run(): void
    {
        $this->command->info('Memperbaiki saldo akhir di material histories...');

        // Ambil semua material yang punya history
        $materials = Material::whereHas('histories')->get();

        $totalFixed = 0;

        DB::beginTransaction();
        try {
            foreach ($materials as $material) {
                // Ambil semua history untuk material ini, diurutkan dari terlama
                $histories = MaterialHistory::where('material_id', $material->id)
                    ->orderBy('tanggal', 'asc')
                    ->orderBy('created_at', 'asc')
                    ->get();

                if ($histories->isEmpty()) {
                    continue;
                }

                // Hitung saldo kumulatif
                $saldoKumulatif = 0;

                // Cari saldo awal (dari history pertama atau dari stok material saat ini)
                // Kita akan menghitung mundur dari stok saat ini
                $stokSekarang = $material->unrestricted_use_stock;
                
                // Hitung total masuk dan keluar dari semua history
                $totalMasuk = $histories->sum('masuk');
                $totalKeluar = $histories->sum('keluar');
                
                // Saldo awal = stok sekarang - total masuk + total keluar
                $saldoAwal = $stokSekarang - $totalMasuk + $totalKeluar;
                
                $saldoKumulatif = $saldoAwal;

                foreach ($histories as $history) {
                    // Tambah masuk, kurangi keluar
                    $saldoKumulatif += $history->masuk;
                    $saldoKumulatif -= $history->keluar;

                    // Update sisa_persediaan
                    $history->update([
                        'sisa_persediaan' => $saldoKumulatif
                    ]);

                    $totalFixed++;
                }

                $this->command->info("  ✓ {$material->material_code}: {$histories->count()} history diperbaiki (Saldo akhir: {$saldoKumulatif})");
            }

            DB::commit();

            $this->command->info('');
            $this->command->info("✅ Berhasil memperbaiki {$totalFixed} record history");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('❌ Error: ' . $e->getMessage());
        }
    }
}
