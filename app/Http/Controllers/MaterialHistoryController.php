<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\MaterialHistory;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MaterialHistoryExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Pagination\LengthAwarePaginator;


class MaterialHistoryController extends Controller
{
public function index(Request $request, $id = null)
{
    if ($id) {
        $material = Material::findOrFail($id);

        $histories = MaterialHistory::with('material')
            ->onlyMaterialBaru()
            ->where('material_id', $id)
            ->orderBy('created_at', 'asc')
            ->orderBy('id', 'asc') // Penting: urutan input
            ->get();

        // Hitung total masuk & keluar
        $totalMasuk = $histories->sum('masuk');
        $totalKeluar = $histories->sum('keluar');

        // Stok awal = stok akhir - (masuk - keluar)
        $runningStock = ($material->unrestricted_use_stock ?? 0) - ($totalMasuk - $totalKeluar);

        // Hitung sisa persediaan
        $histories = $histories->map(function ($item) use (&$runningStock) {
            $runningStock += ($item->masuk ?? 0);
            $runningStock -= ($item->keluar ?? 0);
            $item->sisa_persediaan = $runningStock;
            return $item;
        });
        $isSearch = $request->query->count() > 0;
        $histories = $isSearch
    ? $histories->values()
    : $histories->reverse()->values();

// $histories = $this->paginateCollection($histories, 10);

return view('material.history', [
    'material'  => $material,
    'histories' => $histories
]);


    }

    // ==============================
    // TANPA ID (All Materials)
    // ==============================

    $histories = MaterialHistory::with('material')
        ->onlyMaterialBaru()
        ->orderBy('created_at', 'asc')
        ->orderBy('id', 'asc')
        ->get();

    $grouped = $histories->groupBy('material_id');
    $finalHistories = collect();

    foreach ($grouped as $materialId => $items) {
        $material = Material::find($materialId);
        if (!$material) continue;

        $totalMasuk = $items->sum('masuk');
        $totalKeluar = $items->sum('keluar');

        $runningStock = ($material->unrestricted_use_stock ?? 0) - ($totalMasuk - $totalKeluar);

        $mapped = $items->sortBy('created_at')->sortBy('id')->values()->map(function ($item) use (&$runningStock) {
            $runningStock += ($item->masuk ?? 0);
            $runningStock -= ($item->keluar ?? 0);
            $item->sisa_persediaan = $runningStock;
            return $item;
        });

        $finalHistories = $finalHistories->merge($mapped);
    }
    $isSearch = $request->filled('q');

        $finalHistories = $isSearch
            ? $finalHistories->values() // SEARCH → baca dari atas
            : $finalHistories
        ->sortByDesc('created_at')
        ->sortByDesc('id')
        ->values();



    // $finalHistories = $this->paginateCollection($finalHistories, 10);

return view('material.history', [
    'histories' => $finalHistories,
    'material'  => null
]);

}




    public function export(Request $request, $id = null)
    {
        $fileName = 'material_history_' . ($id ?? 'all') . '_' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new MaterialHistoryExport($id, $request), $fileName);
    }

    public function exportPdf(Request $request, $id)
{
    $material = Material::findOrFail($id);

    $histories = MaterialHistory::onlyMaterialBaru()
        ->where('material_id', $id)
        ->orderBy('created_at', 'asc')
        ->orderBy('id', 'asc')
        ->get();

    $totalMasuk = $histories->sum('masuk');
    $totalKeluar = $histories->sum('keluar');

    $runningStock = ($material->unrestricted_use_stock ?? 0) - ($totalMasuk - $totalKeluar);

    $histories = $histories->map(function ($item) use (&$runningStock) {
        $runningStock += ($item->masuk ?? 0);
        $runningStock -= ($item->keluar ?? 0);
        $item->sisa_persediaan = $runningStock;
        return $item;
    });

    $pdf = PDF::loadView('exports.kartu-gantung', compact('material', 'histories'))
        ->setPaper('a4', 'portrait');

    return $pdf->stream("kartu_gantung_{$material->material_code}.pdf");
}


}

