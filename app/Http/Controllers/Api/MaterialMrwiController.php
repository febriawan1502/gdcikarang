<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MaterialMrwiDetail;
use App\Models\MaterialMrwiSerialMove;
use App\Models\Material;
use App\Models\WarrantyClaim;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MaterialMrwiController extends Controller
{
    /**
     * Search material MRWI Data by Serial Number
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchBySn(Request $request)
    {
        $sn = $request->get('sn');

        if (!$sn) {
            return response()->json(['error' => 'Serial Number wajib diisi'], 400);
        }

        // Cari detail berdasarkan Serial Number
        // Eager load MRWI untuk mendapatkan jenis kerusakan dan tanggal
        $detail = MaterialMrwiDetail::with(['mrwi', 'material'])
            ->where('serial_number', $sn)
            ->latest('created_at') // Ambil yang paling baru jika ada duplikat SN
            ->first();

        if (!$detail) {
            return response()->json(['error' => 'Data dengan Serial Number tersebut tidak ditemukan.'], 404);
        }

        $existingClaim = WarrantyClaim::where('serial_number', $detail->serial_number)->first();
        if ($existingClaim) {
            return response()->json([
                'success' => false,
                'error' => 'Serial number sudah pernah diajukan klaim garansi (Tiket: ' . $existingClaim->ticket_number . ').'
            ], 409);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'material_id' => $detail->material_id,
                'material_code' => $detail->material->material_code ?? '-',
                'material_description' => $detail->material->material_description ?? '-',
                'serial_number' => $detail->serial_number,

                // Asset Details
                'merk' => $detail->nama_pabrikan ?? '-',
                'tahun' => $detail->tahun_buat ?? '-',
                'id_pelanggan' => $detail->id_pelanggan ?? '-',

                // Kerusakan (dari parent MRWI)
                'jenis_kerusakan' => $detail->mrwi->kategori_kerusakan ?? '-',
                'asal_unit' => $detail->mrwi->ulp_pengirim ?? '-',
            ]
        ]);
    }

    public function history(Request $request)
    {
        $serial = trim((string) $request->get('serial', ''));
        if ($serial === '') {
            return response()->json(['error' => 'Serial Number wajib diisi'], 400);
        }

        $history = MaterialMrwiSerialMove::where('serial_number', $serial)
            ->orderBy('id')
            ->get();

        $latest = $history->last();
        $material = $latest ? Material::find($latest->material_id) : null;

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
            ->sortBy('tanggal')
            ->values();

        if ($history->isEmpty() && $warrantyClaims->isEmpty() && !$mrwiDetail) {
            return response()->json(['error' => 'Serial number tidak ditemukan'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'serial' => $serial,
                'summary' => [
                    'material' => $material->material_description ?? '-',
                    'status' => $latest ? ucfirst($latest->status_bucket) : '-',
                    'last_update' => $latest && $latest->tanggal
                        ? Carbon::parse($latest->tanggal)->format('d/m/Y')
                        : '-',
                    'last_update_type' => $latest ? ($latest->jenis ?? '-') : '-',
                ],
                'details' => [
                    'merk' => $mrwiDetail->nama_pabrikan ?? '-',
                    'sn_pabrikan' => $mrwiDetail->id_pelanggan ?? '-',
                    'tahun' => $mrwiDetail->tahun_buat ?? '-',
                    'jenis_kerusakan' => $mrwiDetail->mrwi->kategori_kerusakan ?? '-',
                    'ulp_pengirim' => $mrwiDetail->mrwi->ulp_pengirim ?? '-',
                    'vendor_pengirim' => $mrwiDetail->mrwi->vendor_pengirim ?? '-',
                    'ex_gardu' => $mrwiDetail->mrwi->ex_gardu ?? '-',
                ],
                'timeline' => $timeline->values(),
            ],
        ]);
    }
}
