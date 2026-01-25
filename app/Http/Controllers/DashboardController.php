<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\User;
use App\Models\MaterialMasuk;
use App\Models\SuratJalan;
use App\Models\MaterialStock;
use Yajra\DataTables\Facades\DataTables;
use App\Exports\MaterialExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Monitoring;
use App\Models\MaterialSavingConfig;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard
     */
    public function index()
    {
        $user = auth()->user();
        $unitId = null;
        if ($user && $user->unit_id && (!$user->unit || !$user->unit->is_induk)) {
            $unitId = $user->unit_id;
        }

        $totalStock = MaterialStock::withoutGlobalScopes()
            ->when($unitId, function ($query) use ($unitId) {
                $query->where('unit_id', $unitId);
            })
            ->sum('unrestricted_use_stock');

        $totalSaldo = DB::table('material_stocks as ms')
            ->join('materials as m', 'ms.material_id', '=', 'm.id')
            ->when($unitId, function ($query) use ($unitId) {
                $query->where('ms.unit_id', $unitId);
            })
            ->sum(DB::raw('ms.unrestricted_use_stock * m.harga_satuan'));

        $stats = [
            'total_materials' => Material::count(),
            'total_stock' => $totalStock, 
            'pemakaian_kumulatif' => \DB::table('surat_jalan_detail')
                ->join('materials', 'surat_jalan_detail.material_id', '=', 'materials.id')
                ->join('surat_jalan', 'surat_jalan_detail.surat_jalan_id', '=', 'surat_jalan.id')
                ->whereYear('surat_jalan.created_at', date('Y'))
                ->whereNull('materials.deleted_at')
                ->when($unitId, function ($query) use ($unitId) {
                    $query->where('surat_jalan.unit_id', $unitId);
                })
                ->sum(\DB::raw('surat_jalan_detail.quantity * materials.harga_satuan')),
            'total_saldo' => $totalSaldo,
        ];

        // 10 Surat Jalan Terakhir
        $latest_surat_jalan = SuratJalan::latest()->take(10)->get();
        
        // 10 Material Masuk Terakhir
        $latest_material_masuk = MaterialMasuk::latest()->take(10)->get();
        
        $stockSub = DB::table('material_stocks as ms')
            ->select('ms.material_id', DB::raw('SUM(ms.unrestricted_use_stock) as total_stock'))
            ->when($unitId, function ($query) use ($unitId) {
                $query->where('ms.unit_id', $unitId);
            })
            ->groupBy('ms.material_id');

        // 10 Nilai Material Terbesar (Highest Value)
        $top_value_materials = Material::joinSub($stockSub, 'ms', function ($join) {
                $join->on('materials.id', '=', 'ms.material_id');
            })
            ->select('materials.*', DB::raw('(ms.total_stock * materials.harga_satuan) as calculated_total_value'))
            ->orderBy('calculated_total_value', 'desc')
            ->take(10)
            ->get();
        
        // 10 Material dengan volume stock terbesar
        $top_stock_materials = Material::joinSub($stockSub, 'ms', function ($join) {
                $join->on('materials.id', '=', 'ms.material_id');
            })
            ->select('materials.*', DB::raw('ms.total_stock as unrestricted_use_stock'))
            ->orderBy('ms.total_stock', 'desc')
            ->take(10)
            ->get();

        // 10 Material Fast Moving (hardcoded list)
        $fast_moving_codes = [
            '000000003110025' => ['name' => 'CABLE PWR;NFA2X;2X10mm2;0.6/1kV;OH', 'stok_min' => 12000],
            '000000003110029' => ['name' => 'CABLE PWR;NFA2X;4X16mm2;0.6/1kV;OH', 'stok_min' => 1000],
            '000000002190224' => ['name' => 'MTR;kWH E;;3P;230V/400V;5-80A;1;;2W', 'stok_min' => 1000],
            '000000002190218' => ['name' => 'MTR;kWH E;;3P;230/400V;5-80A;1;;4W', 'stok_min' => 90],
            '000000002190252' => ['name' => 'MTR;kWH E-PR;;3P;230/400V;5-80A;1;;4W', 'stok_min' => 30],
            '000000002190438' => ['name' => 'MTR;kWHE;;3P;57.7/100V-230/400;5A;0.5;4W', 'stok_min' => 30],
            '000000002190502' => ['name' => 'MTR;kWH E;;1P;230V;5-80A;1;;2W', 'stok_min' => 500],
            '000000003250048' => ['name' => 'MCB;230/400V;1P;2A;50Hz;', 'stok_min' => 500],
            '000000003250046' => ['name' => 'MCB;230/400V;1P;2A;50Hz;', 'stok_min' => 500],
            '000000003250050' => ['name' => 'MCB;230/400V;1P;6A;50Hz;', 'stok_min' => 500],
        ];
        
        $fast_moving_materials = collect($fast_moving_codes)->map(function($data, $code) {
            // Find material in database by code
            $material = Material::where('material_code', $code)->whereNull('deleted_at')->first();
            
            
            // If material not found in database, use hardcoded data with stock 0
            if (!$material) {
                return (object)[
                    'id' => null,
                    'material_code' => $code,
                    'material_description' => $data['name'],
                    'unrestricted_use_stock' => 0,
                    'stok_minimum' => $data['stok_min'],
                    'stock_status' => 'habis',
                    'stock_badge' => 'danger',
                ];
            }
            
            $stok_minimum = $material->min_stock > 0 ? $material->min_stock : $data['stok_min'];
            
            $current_stock = $material->unrestricted_use_stock;
            
            // Determine stock status
            if ($current_stock == 0) {
                $stock_status = 'habis';
                $stock_badge = 'danger';
            } elseif ($current_stock <= $stok_minimum) {
                $stock_status = 'kurang';
                $stock_badge = 'warning';
            } else {
                $stock_status = 'aman';
                $stock_badge = 'success';
            }
            
            return (object)[
                'id' => $material->id,
                'material_code' => $material->material_code,
                'material_description' => $material->material_description,
                'unrestricted_use_stock' => $current_stock,
                'stok_minimum' => $stok_minimum,
                'stock_status' => $stock_status,
                'stock_badge' => $stock_badge,
            ];
        });

        $monitorings = Monitoring::all();
        
        // Get or create material saving config
        $material_saving_config = MaterialSavingConfig::first();
        if (!$material_saving_config) {
            $material_saving_config = MaterialSavingConfig::create([
                'standby' => 0,
                'garansi' => 0,
                'perbaikan' => 0,
                'usul_hapus' => 0,
                'saldo_awal_tahun' => 0,
            ]);
        }

        // Calculate ITO
        // Rumus: Pemakaian Komulatif / ((Saldo Awal + Saldo Akhir) / 2)
        $saldo_awal = $material_saving_config->saldo_awal_tahun ?? 0;
        $saldo_akhir = $stats['total_saldo'] ?? 0;
        $rata_rata_persediaan = ($saldo_awal + $saldo_akhir) / 2;
        
        $ito = 0;
        if ($rata_rata_persediaan > 0) {
            $ito = $stats['pemakaian_kumulatif'] / $rata_rata_persediaan;
        }

        $stats['ito'] = $ito;
        
        return view('dashboard.index', compact(
            'stats',
            'monitorings',
            'latest_surat_jalan',
            'latest_material_masuk',
            'top_value_materials',
            'top_stock_materials',
            'fast_moving_materials',
            'material_saving_config'
        ));
    }

    /**
     * Data untuk DataTables
     */
    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $materials = Material::with(['creator', 'updater'])
            ->whereNull('deleted_at')
            ->select('materials.*');

            return DataTables::of($materials)
                ->filter(function ($query) use ($request) {
                    if ($request->has('search') && $request->search['value'] && strlen($request->search['value']) >= 2) {
                        $searchValue = $request->search['value'];
                        $query->where(function($q) use ($searchValue) {
                            $q->whereRaw('LOWER(material_code) LIKE ?', ['%' . strtolower($searchValue) . '%'])
                              ->orWhereRaw('LOWER(material_description) LIKE ?', ['%' . strtolower($searchValue) . '%']);
                        });
                    }
                })
                ->addIndexColumn()
                ->editColumn('qty', function ($row) {
                    return $row->unrestricted_use_stock;
                })
                ->addColumn('action', function ($row) {
                    $actions = '<div class="btn-group" role="group">';
                    
                    // View Detail - semua role bisa lihat
                    $actions .= '<button type="button" class="btn btn-info btn-sm" onclick="viewDetail(' . $row->id . ')" title="Lihat Detail">';
                    $actions .= '<i class="fa fa-eye"></i>';
                    $actions .= '</button>';
                    
                    // Edit dan Delete - hanya admin yang bisa
                    if (auth()->user()->isAdmin()) {
                        // Edit
                        $actions .= '<button type="button" class="btn btn-warning btn-sm" onclick="editMaterial(' . $row->id . ')" title="Edit">';
                        $actions .= '<i class="fa fa-edit"></i>';
                        $actions .= '</button>';
                        
                        // Delete
                        $actions .= '<button type="button" class="btn btn-danger btn-sm" onclick="deleteMaterial(' . $row->id . ')" title="Hapus">';
                        $actions .= '<i class="fas fa-trash"></i>';
                        $actions .= '</button>';
                    }
                    
                    $actions .= '</div>';
                    
                    return $actions;
                })
                ->editColumn('tanggal_terima', function ($row) {
                    return $row->tanggal_terima ? $row->tanggal_terima->format('d F Y') : '-';
                })
                ->editColumn('rak', function ($row) {
                    return $row->rak ?? '-';
                })
                ->editColumn('harga_satuan', function ($row) {
                    return 'Rp ' . number_format($row->harga_satuan, 0, ',', '.');
                })
                ->editColumn('total_harga', function ($row) {
                    return 'Rp ' . number_format($row->total_harga, 0, ',', '.');
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return response()->json(['error' => 'Invalid request'], 400);
    }

    /**
     * Tampilkan detail material
     */
    public function show($id)
    {
        $material = Material::with(['creator', 'updater'])->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => $material
        ]);
    }

    /**
     * Hapus material
     */
    public function destroy($id)
    {
        try {
            $material = Material::findOrFail($id);
            $material->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Material berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus material: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Halaman stock opname
     */
    public function stockOpname()
    {
        return view('dashboard.stock-opname');
    }

    /**
     * Get dashboard statistics for API
     */
    public function getStats(Request $request)
    {
        try {
            $user = auth()->user();
            $unitId = null;
            if ($user && $user->unit_id && (!$user->unit || !$user->unit->is_induk)) {
                $unitId = $user->unit_id;
            }

            $stockSub = DB::table('material_stocks as ms')
                ->select('ms.material_id', DB::raw('SUM(ms.qty) as qty'))
                ->when($unitId, function ($query) use ($unitId) {
                    $query->where('ms.unit_id', $unitId);
                })
                ->groupBy('ms.material_id');

            $stats = [
                'total_materials' => Material::count(),
                'total_stock' => MaterialStock::withoutGlobalScopes()
                    ->when($unitId, function ($query) use ($unitId) {
                        $query->where('unit_id', $unitId);
                    })
                    ->sum('qty'),
                'total_material_masuk' => MaterialMasuk::count(),
                'total_surat_jalan' => SuratJalan::count(),
                'active_materials' => Material::where('status', Material::STATUS_BAIK)->count(),
                'low_stock_materials' => Material::whereHas('stocks', function ($query) use ($unitId) {
                    $query->when($unitId, function ($q) use ($unitId) {
                        $q->where('unit_id', $unitId);
                    })->where('qty', '<=', 10);
                })->count(),
                'total_value' => DB::table('material_stocks as ms')
                    ->join('materials as m', 'ms.material_id', '=', 'm.id')
                    ->when($unitId, function ($query) use ($unitId) {
                        $query->where('ms.unit_id', $unitId);
                    })
                    ->sum(DB::raw('ms.unrestricted_use_stock * m.harga_satuan')),
                'recent_materials' => Material::leftJoinSub($stockSub, 'ms', function ($join) {
                        $join->on('materials.id', '=', 'ms.material_id');
                    })
                    ->latest('materials.created_at')
                    ->take(5)
                    ->get([
                        'materials.id',
                        'materials.material_code',
                        'materials.material_description',
                        DB::raw('COALESCE(ms.qty, 0) as qty'),
                        'materials.status',
                        'materials.created_at'
                    ])
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil statistik: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update saldo awal tahun only
     */
    public function updateSaldoAwal(Request $request)
    {
        try {
            $validated = $request->validate([
                'saldo_awal_tahun' => 'required|numeric|min:0',
            ]);

            $config = MaterialSavingConfig::first();
            if (!$config) {
                $config = MaterialSavingConfig::create($validated);
            } else {
                $config->update($validated);
            }

            return response()->json([
                'success' => true,
                'message' => 'Saldo awal berhasil disimpan',
                'data' => $config
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan saldo awal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update material saving configuration
     */
    public function updateMaterialSavingConfig(Request $request)
    {
        try {
            $validated = $request->validate([
                'standby' => 'required|numeric|min:0',
                'garansi' => 'required|numeric|min:0',
                'perbaikan' => 'required|numeric|min:0',
                'usul_hapus' => 'required|numeric|min:0',
                // 'saldo_awal_tahun' removed from here as it's handled separately now
            ]);

            $config = MaterialSavingConfig::first();
            if (!$config) {
                $config = MaterialSavingConfig::create($validated);
            } else {
                $config->update($validated);
            }

            return response()->json([
                'success' => true,
                'message' => 'Konfigurasi berhasil disimpan',
                'data' => $config
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan konfigurasi: ' . $e->getMessage()
            ], 500);
        }
    }

    

    /**
     * Export data material ke Excel
     */
    public function export()
    {
        try {
            $fileName = 'material_export_' . date('Y-m-d_H-i-s') . '.xlsx';
            
            return Excel::download(new MaterialExport, $fileName);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengexport data: ' . $e->getMessage());
        }
    }

    public function updateFastMovingConfig(Request $request)
    {
        $request->validate([
            'configs' => 'required|array',
            'configs.*.id' => 'required|exists:materials,id',
            'configs.*.min_stock' => 'required|integer|min:0',
        ]);

        try {
            DB::beginTransaction();
            
            foreach ($request->configs as $config) {
                Material::where('id', $config['id'])->update(['min_stock' => $config['min_stock']]);
            }
            
            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
