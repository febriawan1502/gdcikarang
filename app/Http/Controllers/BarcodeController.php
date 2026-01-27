<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\MaterialHistory;
use App\Models\MaterialStock;
use App\Models\Unit;

class BarcodeController extends Controller
{
    /**
     * Tampilkan mutasi material berdasarkan normalisasi (material_code)
     * URL: /barcode/{material_code}
     */
    public function show($unitIdOrMaterialCode, $materialCode = null)
    {
        $unitId = null;
        if ($materialCode === null) {
            $materialCode = $unitIdOrMaterialCode;
        } else {
            $unitId = is_numeric($unitIdOrMaterialCode) ? (int) $unitIdOrMaterialCode : null;
        }

        // Cari material berdasarkan material_code
        $material = Material::where('material_code', $materialCode)->first();

        if (!$material) {
            return view('barcode.not-found', ['materialCode' => $materialCode]);
        }

        $unit = null;
        if ($unitId) {
            $unit = Unit::find($unitId);
        }

        $historyQuery = MaterialHistory::query()->withoutGlobalScopes()->where('material_id', $material->id);
        if ($unitId) {
            $historyQuery->where('unit_id', $unitId);
        }

        // Ambil history material (masuk & keluar) diurutkan dari TERLAMA untuk hitung saldo
        $historiesAsc = $historyQuery
            ->orderBy('tanggal', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();

        // Hitung saldo awal (unit-specific jika ada unit)
        if ($unitId) {
            $stock = MaterialStock::withoutGlobalScopes()
                ->where('material_id', $material->id)
                ->where('unit_id', $unitId)
                ->first();
            $stokSekarang = $stock->unrestricted_use_stock ?? 0;
        } else {
            $stokSekarang = $material->unrestricted_use_stock;
        }
        $totalMasuk = $historiesAsc->sum('masuk');
        $totalKeluar = $historiesAsc->sum('keluar');
        $saldoAwal = $stokSekarang - $totalMasuk + $totalKeluar;

        // Hitung saldo kumulatif untuk setiap history
        $saldoKumulatif = $saldoAwal;
        foreach ($historiesAsc as $history) {
            $saldoKumulatif += $history->masuk;
            $saldoKumulatif -= $history->keluar;
            $history->saldo_akhir_calculated = $saldoKumulatif;
        }

        // Balik urutan untuk tampilan (terbaru di atas)
        $histories = $historiesAsc->reverse()->values();

        // Saldo akhir = stok sekarang
        $saldoAkhir = $stokSekarang;

        return view('barcode.show', compact('material', 'histories', 'saldoAkhir', 'unit'));
    }

    public function showLegacy($materialCode)
    {
        return view('barcode.not-found', ['materialCode' => $materialCode]);
    }
}
