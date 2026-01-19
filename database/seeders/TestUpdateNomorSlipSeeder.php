<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SuratJalan;
use App\Models\MaterialHistory;

class TestUpdateNomorSlipSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Testing Update Nomor Slip SAP...');

        // Ambil surat jalan pertama yang punya history
        $suratJalan = SuratJalan::whereHas('histories')->first();
        
        if (!$suratJalan) {
            $this->command->error('Tidak ada surat jalan dengan history');
            return;
        }

        $this->command->info("Surat Jalan ID: {$suratJalan->id}");
        $this->command->info("Nomor: {$suratJalan->nomor_surat}");
        $this->command->info("Nomor Slip SAP (sebelum): {$suratJalan->nomor_slip}");

        // Cari history yang terkait
        $histories = MaterialHistory::where('source_type', 'surat_jalan')
            ->where('source_id', $suratJalan->id)
            ->get();

        $this->command->info("Jumlah history: {$histories->count()}");
        
        foreach ($histories as $history) {
            $this->command->info("  - History ID {$history->id}: no_slip = {$history->no_slip}");
        }

        // Update nomor slip di surat jalan
        $newNomorSlip = 'TEST-SLIP-' . now()->format('His');
        $this->command->info("\nMengupdate nomor_slip menjadi: {$newNomorSlip}");
        
        $suratJalan->update(['nomor_slip' => $newNomorSlip]);

        // Update juga di history
        MaterialHistory::where('source_type', 'surat_jalan')
            ->where('source_id', $suratJalan->id)
            ->update(['no_slip' => $newNomorSlip]);

        $this->command->info("\n✅ Update selesai!");
        
        // Verifikasi
        $suratJalan->refresh();
        $this->command->info("Nomor Slip SAP (sesudah): {$suratJalan->nomor_slip}");
        
        $histories = MaterialHistory::where('source_type', 'surat_jalan')
            ->where('source_id', $suratJalan->id)
            ->get();
            
        foreach ($histories as $history) {
            $this->command->info("  - History ID {$history->id}: no_slip = {$history->no_slip}");
        }
    }
}
