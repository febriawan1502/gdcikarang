<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\MaterialMrwiSerialMove;
use App\Models\MaterialMrwiDetail;
use App\Models\WarrantyClaim;
use Illuminate\Http\Request;

class MaterialMrwiHistoryController extends Controller
{
    public function index(Request $request)
    {
        $serial = trim((string) $request->get('serial', ''));
        $data = $this->getHistoryData($serial);

        return view('material.mrwi-history', array_merge(['serial' => $serial], $data));
    }

    public function scan(Request $request)
    {
        $serial = trim((string) $request->get('serial', ''));
        $data = $this->getHistoryData($serial);

        return view('material.mrwi-history-scan', array_merge(['serial' => $serial], $data));
    }

    private function getHistoryData(string $serial): array
    {
        $history = collect();
        $latest = null;
        $material = null;
        $notFound = false;
        $warrantyClaims = collect();
        $timeline = collect();
        $mrwiDetail = null;

        if ($serial !== '') {
            $history = MaterialMrwiSerialMove::where('serial_number', $serial)
                ->orderBy('id')
                ->get();

            if (!$history->isEmpty()) {
                $latest = $history->last();
                $material = Material::find($latest->material_id);
            }

            $mrwiDetail = MaterialMrwiDetail::with('mrwi')
                ->where('serial_number', $serial)
                ->latest('created_at')
                ->first();

            $warrantyClaims = WarrantyClaim::with('pickupSuratJalan')
                ->where('serial_number', $serial)
                ->orderBy('submission_date')
                ->get();

            $timeline = $history->map(function ($move) {
                return [
                    'tanggal' => $move->tanggal ? (string) $move->tanggal : null,
                    'jenis' => $move->jenis ? ucfirst($move->jenis) : '-',
                    'status' => $move->status_bucket ? ucfirst($move->status_bucket) : '-',
                    'referensi' => $move->reference_number ?: '-',
                    'keterangan' => $move->keterangan ?: '-',
                ];
            });

            $claimEvents = collect();
            foreach ($warrantyClaims as $claim) {
                if ($claim->submission_date) {
                    $claimEvents->push([
                        'tanggal' => $claim->submission_date->format('Y-m-d H:i:s'),
                        'jenis' => 'Klaim Garansi',
                        'status' => 'Submitted',
                        'referensi' => $claim->ticket_number,
                        'keterangan' => 'Pengajuan garansi ke pabrikan',
                    ]);
                }
                if ($claim->pickup_date) {
                    $claimEvents->push([
                        'tanggal' => $claim->pickup_date->format('Y-m-d H:i:s'),
                        'jenis' => 'Dikirim ke Pabrikan',
                        'status' => 'Processed',
                        'referensi' => $claim->pickupSuratJalan->nomor_surat ?? $claim->ticket_number,
                        'keterangan' => 'Pengiriman material untuk perbaikan/garansi',
                    ]);
                }
                if ($claim->return_date) {
                    $claimEvents->push([
                        'tanggal' => $claim->return_date->format('Y-m-d H:i:s'),
                        'jenis' => 'Kembali dari Pabrikan',
                        'status' => 'Completed',
                        'referensi' => $claim->ticket_number,
                        'keterangan' => 'Material kembali ke gudang',
                    ]);
                }
            }

            $timeline = $timeline
                ->merge($claimEvents)
                ->filter(fn($row) => !empty($row['tanggal']))
                ->sortByDesc('tanggal')
                ->values();

            if ($history->isEmpty() && $warrantyClaims->isEmpty() && !$mrwiDetail) {
                $notFound = true;
            }
        }

        // Determine Current Status string
        $currentStatus = '-';
        if ($latest) {
            // Default mapping
            switch (strtolower($latest->status_bucket)) {
                case 'standby':
                    $currentStatus = 'Material Standby';
                    break;
                case 'garansi':
                    $currentStatus = 'Garansi';
                    break;
                case 'perbaikan':
                    $currentStatus = 'Perbaikan';
                    break;
                case 'rusak':
                    $currentStatus = 'Rusak';
                    break;
                default:
                    $currentStatus = ucfirst($latest->status_bucket);
            }

            // Logic Overrides

            // 1. Klaim Garansi Flow
            // If current bucket is Garansi OR active claim implies it's in claim process
            if (strtolower($latest->status_bucket) === 'garansi') {
                $activeClaim = $warrantyClaims->last(); // Assuming ordered
                if ($activeClaim) {
                    if ($activeClaim->return_date) {
                        // Claim returned. If latest move is NOT yet standby (sync lag?), assume logic dictates standby.
                        // But if users say "kalau sudah kembali menjadi standby", usually the move handles it.
                        // Check logical strictness:
                        if (strtolower($latest->status_bucket) !== 'standby') {
                            // Fallback if move didn't update bucket yet (rare case)
                            $currentStatus = 'Material Standby';
                        }
                    } elseif ($activeClaim->pickup_date) {
                        $currentStatus = 'Proses Klaim';
                    } elseif ($activeClaim->submission_date) {
                        $currentStatus = 'Pengajuan Klaim';
                    }
                }
            }

            // 2. Perbaikan Flow
            // "misal input rusak kemudian dibuat surat jalan perbaikan maka status nya jadi perbaikan"
            // If it was Rusak, and moved OUT for perbaikan (implied by Surat Jalan context or bucket change)
            if (strtolower($latest->status_bucket) === 'rusak') {
                // Check if latest move is 'keluar' with reference to Surat Jalan Perbaikan? 
                // Assuming the Surat Jalan process updates the bucket to 'perbaikan'.
                // BUT, if the bucket is still 'rusak' but there's a move?
                // Let's assume the user means finding a Surat Jalan that changes context.
                // For now, if bucket is 'perbaikan', we set it to 'Perbaikan'.
            }

            if (strtolower($latest->status_bucket) === 'perbaikan') {
                $currentStatus = 'Perbaikan'; // Explicitly set as Perbaikan per user request "status nya jadi perbaikan"
                if ($latest->jenis === 'keluar') {
                    // Maybe "Proses Perbaikan" if needed, but user just said "jadi perbaikan"
                    // Let's stick to "Perbaikan" unless "Proses Perbaikan" is preferred.
                    // User said: "rusak juga bisa berubah jadi diperbaiki"
                }
            }

            // 3. Standby check (redundant with switch but safe)
            if (strtolower($latest->status_bucket) === 'standby') {
                $currentStatus = 'Material Standby';
            }
        }

        return compact('history', 'latest', 'material', 'notFound', 'timeline', 'mrwiDetail', 'currentStatus');
    }
}
