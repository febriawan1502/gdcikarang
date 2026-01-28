<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppInfoController extends Controller
{
    /**
     * Summary for simple client apps.
     */
    public function summary(Request $request)
    {
        $rakExpr = 'COALESCE(ms.rak, m.rak)';
        $rakHeader = $request->header('X-Rak');

        $saldoByRakQuery = DB::table('material_stocks as ms')
            ->join('materials as m', 'ms.material_id', '=', 'm.id')
            ->selectRaw($rakExpr . ' as rak')
            ->selectRaw('SUM(ms.unrestricted_use_stock) as total_stock')
            ->selectRaw('SUM(ms.unrestricted_use_stock * m.harga_satuan) as total_saldo');

        if ($rakHeader) {
            $saldoByRakQuery->where(DB::raw($rakExpr), $rakHeader);
        }

        $saldoByRak = $saldoByRakQuery
            ->groupBy(DB::raw($rakExpr))
            ->orderBy(DB::raw($rakExpr))
            ->get();

        $materialsQuery = DB::table('material_stocks as ms')
            ->join('materials as m', 'ms.material_id', '=', 'm.id')
            ->selectRaw('COALESCE(m.normalisasi, m.material_code) as normalisasi')
            ->selectRaw('m.material_description as nama_material')
            ->selectRaw('SUM(ms.unrestricted_use_stock) as stok')
            ->groupBy('m.id', 'm.normalisasi', 'm.material_code', 'm.material_description')
            ->orderBy('m.material_description');

        if ($rakHeader) {
            $materialsQuery->where(DB::raw($rakExpr), $rakHeader);
        }

        $materials = $materialsQuery->get();

        return response()->json([
            'success' => true,
            'data' => [
                'app_name' => config('app.name'),
                'saldo_by_rak' => $saldoByRak,
                'materials' => $materials,
            ],
        ]);
    }
}
