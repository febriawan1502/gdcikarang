<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\MaterialHistory;
use App\Models\Unit;
use Illuminate\Http\Request;

class MaterialHistoryController extends Controller
{
    public function index(Request $request)
    {
        $unitHeader = trim((string) $request->header('X-Unit'));
        $materialHeader = trim((string) $request->header('X-Material'));

        if ($unitHeader === '' || $materialHeader === '') {
            return response()->json([
                'success' => false,
                'message' => 'Header X-Unit dan X-Material wajib diisi.',
            ], 422);
        }

        $unit = Unit::where('code', $unitHeader)->first();
        if (!$unit) {
            return response()->json([
                'success' => false,
                'message' => 'Unit tidak ditemukan.',
            ], 404);
        }

        $normalized = $this->normalizeMaterialCode($materialHeader);
        if ($normalized === '') {
            return response()->json([
                'success' => false,
                'message' => 'Nomor material tidak valid.',
            ], 422);
        }

        $digits = ltrim($normalized, '0');
        $material = Material::where('material_code', $normalized)
            ->orWhere('material_code', $digits)
            ->orWhere('normalisasi', $normalized)
            ->orWhere('normalisasi', $digits)
            ->first();

        if (!$material) {
            return response()->json([
                'success' => false,
                'message' => 'Material tidak ditemukan.',
            ], 404);
        }

        $histories = MaterialHistory::with('material')
            ->onlyMaterialBaru()
            ->where('material_id', $material->id)
            ->where('unit_id', $unit->id)
            ->orderBy('created_at', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $totalMasuk = $histories->sum('masuk');
        $totalKeluar = $histories->sum('keluar');
        $runningStock = $material->getStockValue('unrestricted_use_stock', $unit->id) - ($totalMasuk - $totalKeluar);

        $histories = $histories->map(function ($item) use (&$runningStock) {
            $runningStock += ($item->masuk ?? 0);
            $runningStock -= ($item->keluar ?? 0);
            $item->sisa_persediaan = $runningStock;
            return $item;
        });

        return response()->json([
            'success' => true,
            'data' => [
                'unit' => [
                    'id' => $unit->id,
                    'code' => $unit->code,
                    'name' => $unit->name,
                ],
                'material' => [
                    'id' => $material->id,
                    'material_code' => $material->material_code,
                    'normalisasi' => $material->normalisasi,
                    'material_description' => $material->material_description,
                    'base_unit_of_measure' => $material->base_unit_of_measure,
                ],
                'histories' => $histories->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'tanggal' => optional($item->tanggal)->format('Y-m-d'),
                        'tipe' => $item->tipe,
                        'no_slip' => $item->no_slip,
                        'masuk' => $item->masuk,
                        'keluar' => $item->keluar,
                        'sisa_persediaan' => $item->sisa_persediaan,
                        'catatan' => $item->catatan,
                        'keterangan' => $item->keterangan,
                        'source_type' => $item->source_type,
                        'source_id' => $item->source_id,
                        'created_at' => optional($item->created_at)->toDateTimeString(),
                    ];
                }),
            ],
        ]);
    }

    private function normalizeMaterialCode(string $code): string
    {
        $digits = preg_replace('/\D+/', '', $code) ?? '';
        $digits = ltrim($digits, '0');
        if ($digits === '') {
            return '';
        }
        return str_pad($digits, 15, '0', STR_PAD_LEFT);
    }
}
