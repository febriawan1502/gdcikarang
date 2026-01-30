<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MaterialMasuk;
use App\Models\MaterialHistory;
use App\Models\SuratJalan;
use App\Models\SuratJalanDetail;
use App\Models\PengembalianHistory;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PurgeOldTransactionsSeeder extends Seeder
{
    public function run(): void
    {
        $cutoff = Carbon::create(2026, 1, 25)->startOfDay();

        DB::transaction(function () use ($cutoff) {
            // Material Masuk sebelum cutoff
            $materialMasukIds = MaterialMasuk::whereDate('tanggal_masuk', '<', $cutoff)
                ->pluck('id')
                ->toArray();

            if (!empty($materialMasukIds)) {
                MaterialHistory::where('source_type', 'material_masuk')
                    ->whereIn('source_id', $materialMasukIds)
                    ->delete();

                DB::table('material_masuk_detail')
                    ->whereIn('material_masuk_id', $materialMasukIds)
                    ->delete();

                MaterialMasuk::whereIn('id', $materialMasukIds)->delete();
            }

            // Surat Jalan sebelum cutoff
            $suratJalanIds = SuratJalan::whereDate('tanggal', '<', $cutoff)
                ->pluck('id')
                ->toArray();

            if (!empty($suratJalanIds)) {
                MaterialHistory::where('source_type', 'surat_jalan')
                    ->whereIn('source_id', $suratJalanIds)
                    ->delete();

                $detailIds = SuratJalanDetail::whereIn('surat_jalan_id', $suratJalanIds)
                    ->pluck('id')
                    ->toArray();

                if (!empty($detailIds)) {
                    PengembalianHistory::whereIn('surat_jalan_detail_id', $detailIds)->delete();
                }

                SuratJalanDetail::whereIn('surat_jalan_id', $suratJalanIds)->delete();
                DB::table('surat_jalan')->whereIn('id', $suratJalanIds)->delete();
            }
        });
    }
}
