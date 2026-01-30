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
                ->filter(fn ($row) => !empty($row['tanggal']))
                ->sortByDesc('tanggal')
                ->values();

            if ($history->isEmpty() && $warrantyClaims->isEmpty() && !$mrwiDetail) {
                $notFound = true;
            }
        }

        return view('material.mrwi-history', compact('serial', 'history', 'latest', 'material', 'notFound', 'timeline', 'mrwiDetail'));
    }
}
