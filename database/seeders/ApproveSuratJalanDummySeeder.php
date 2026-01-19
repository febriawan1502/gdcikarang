<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SuratJalan;
use App\Models\SuratJalanDetail;
use App\Models\Material;
use App\Models\MaterialHistory;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ApproveSuratJalanDummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        
        if (!$user) {
            $this->command->error('Tidak ada user di database.');
            return;
        }

        $this->command->info('Mengupdate surat jalan dummy menjadi APPROVED dan membuat history...');

        // Ambil semua surat jalan yang bukan APPROVED
        $suratJalans = SuratJalan::whereIn('status', ['BUTUH_PERSETUJUAN', 'SELESAI'])
            ->with('details.material')
            ->get();

        $totalUpdated = 0;
        $totalHistory = 0;

        DB::beginTransaction();
        try {
            foreach ($suratJalans as $suratJalan) {
                // Update status menjadi APPROVED
                $suratJalan->update([
                    'status' => 'APPROVED',
                    'approved_by' => $user->id,
                    'approved_at' => $suratJalan->tanggal->copy()->addHours(rand(1, 6))
                ]);

                $this->command->info("  → {$suratJalan->nomor_surat} diupdate ke APPROVED");

                // Buat history untuk setiap detail (hanya untuk jenis Normal)
                foreach ($suratJalan->details as $detail) {
                    // Skip jika manual atau bukan jenis Normal
                    if ($detail->is_manual || $suratJalan->jenis_surat_jalan !== 'Normal') {
                        continue;
                    }

                    $material = $detail->material;
                    
                    if (!$material) {
                        $this->command->warn("    ⚠ Material ID {$detail->material_id} tidak ditemukan");
                        continue;
                    }

                    // Cek apakah history sudah ada untuk detail ini
                    $existingHistory = MaterialHistory::where('source_type', 'surat_jalan')
                        ->where('source_id', $suratJalan->id)
                        ->where('material_id', $material->id)
                        ->first();

                    if ($existingHistory) {
                        $this->command->info("    ✓ History sudah ada untuk {$material->material_code}");
                        continue;
                    }

                    // Kurangi stok material
                    $material->decrement('qty', $detail->quantity);
                    $material->decrement('unrestricted_use_stock', $detail->quantity);

                    // Buat history material keluar
                    MaterialHistory::create([
                        'material_id' => $material->id,
                        'source_type' => 'surat_jalan',
                        'source_id' => $suratJalan->id,
                        'tanggal' => $suratJalan->tanggal,
                        'tipe' => 'keluar',
                        'no_slip' => $suratJalan->nomor_slip ?? $suratJalan->berdasarkan,
                        'masuk' => 0,
                        'keluar' => $detail->quantity,
                        'sisa_persediaan' => $material->unrestricted_use_stock,
                        'catatan' => $suratJalan->kepada,
                    ]);

                    $totalHistory++;
                    $this->command->info("    ✓ History dibuat: {$material->material_code} - Keluar {$detail->quantity}");
                }

                $totalUpdated++;
            }

            DB::commit();

            $this->command->info('');
            $this->command->info("✅ Berhasil update {$totalUpdated} surat jalan menjadi APPROVED");
            $this->command->info("✅ Berhasil membuat {$totalHistory} history material");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('❌ Error: ' . $e->getMessage());
            $this->command->error($e->getTraceAsString());
        }
    }
}
