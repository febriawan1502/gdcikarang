<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\MaterialMrwiStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MaterialMrwiStockDetailExport;

use Illuminate\Support\Facades\Auth;

class MaterialMrwiStockController extends Controller
{
    public function index(Request $request, ?string $category = 'standby')
    {
        $category = $this->normalizeCategory($category);
        return view('material.mrwi-stock', compact('category'));
    }

    public function getData(Request $request, string $category)
    {
        $category = $this->normalizeCategory($category);
        $column = $this->getColumnByCategory($category);

        $user = Auth::user();
        $unitId = null;
        if ($user && $user->unit_id && (!$user->unit || !$user->unit->is_induk)) {
            $unitId = $user->unit_id;
        }

        $stockSub = MaterialMrwiStock::select(
            'material_id',
            DB::raw("SUM({$column}) as stock_qty")
        )
            ->when($unitId, function ($query) use ($unitId) {
                $query->where('unit_id', $unitId);
            })
            ->groupBy('material_id');

        $materials = Material::leftJoinSub($stockSub, 'ms', function ($join) {
            $join->on('materials.id', '=', 'ms.material_id');
        })
            ->select([
                'materials.id',
                'materials.material_code',
                'materials.material_description',
                DB::raw('COALESCE(ms.stock_qty, 0) as stock_qty'),
                'materials.base_unit_of_measure',
            ])
            ->whereRaw('COALESCE(ms.stock_qty, 0) > 0')
            ->orderBy('materials.material_code');

        return DataTables::of($materials)
            ->addIndexColumn()
            ->make(true);
    }

    private function normalizeCategory(?string $category): string
    {
        $category = strtolower($category ?? 'standby');
        if (!in_array($category, ['standby', 'garansi', 'perbaikan', 'rusak'], true)) {
            return 'standby';
        }
        return $category;
    }

    private function getColumnByCategory(string $category): string
    {
        return match ($category) {
            'garansi' => 'garansi_stock',
            'perbaikan' => 'perbaikan_stock',
            'rusak' => 'rusak_stock',
            default => 'standby_stock',
        };
    }

    public function export(Request $request, string $category)
    {
        $category = $this->normalizeCategory($category);
        $timestamp = now()->format('Y-m-d_H-i');
        return Excel::download(new MaterialMrwiStockDetailExport($category), "stok_mrwi_{$category}_{$timestamp}.xlsx");
    }
}
