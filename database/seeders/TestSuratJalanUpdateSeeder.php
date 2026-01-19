<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SuratJalan;
use App\Models\MaterialHistory;

class TestSuratJalanUpdateSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Testing Surat Jalan Update...');

        // Ambil surat jalan pertama
        $suratJalan = SuratJalan::first();
        
        if (!$suratJalan) {
            $this->command->error('Tidak ada surat jalan di database');
            return;
        }

        $this->command->info("Surat Jalan ID: {$suratJalan->id}");
        $this->command->info("Nomor: {$suratJalan->nomor_surat}");
        $this->command->info("Keterangan: {$suratJalan->keterangan}");

        // Cari history yang terkait
        $history = MaterialHistory::where('source_type', 'surat_jalan')
            ->where('source_id', $suratJalan->id)
            ->first();

        if ($history) {
            $this->command->info("History ID: {$history->id}");
            $this->command->info("Material ID: {$history->material_id}");
            $this->command->info("Pekerjaan (dari accessor): {$history->pekerjaan}");
        } else {
            $this->command->warn("Tidak ada history untuk surat jalan ini");
        }

        // Test update
        $this->command->info("\nMengupdate keterangan surat jalan...");
        $suratJalan->update(['keterangan' => 'TEST UPDATE ' . now()->format('H:i:s')]);
        
        // Refresh dan cek lagi
        $suratJalan->refresh();
        $this->command->info("Keterangan baru: {$suratJalan->keterangan}");

        if ($history) {
            // Refresh history juga
            $history->refresh();
            $this->command->info("Pekerjaan baru (dari accessor): {$history->pekerjaan}");
        }
    }
}
