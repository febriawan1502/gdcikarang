<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\MaterialHistory;

class BarcodeController extends Controller
{
    /**
     * Tampilkan mutasi material berdasarkan normalisasi (material_code)
     * URL: /barcode/{material_code}
     */
    public function show($materialCode)
    {
        // Cari material berdasarkan material_code
        $material = Material::where('material_code', $materialCode)->first();

        if (!$material) {
            return view('barcode.not-found', ['materialCode' => $materialCode]);
        }

        // Ambil history material (masuk & keluar) diurutkan dari TERLAMA untuk hitung saldo
        $historiesAsc = MaterialHistory::where('material_id', $material->id)
            ->orderBy('tanggal', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();

        // Hitung saldo awal
        $stokSekarang = $material->unrestricted_use_stock;
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

        return view('barcode.show', compact('material', 'histories', 'saldoAkhir'));
    }
}
