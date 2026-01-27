<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SapCheckController extends Controller
{
    private function normalizeMaterialCode(string $code): string
    {
        $digits = preg_replace('/\D+/', '', $code) ?? '';
        $digits = ltrim($digits, '0');
        if ($digits === '') {
            return '';
        }
        return str_pad($digits, 15, '0', STR_PAD_LEFT);
    }
    //
    public function index()
    {
        return view('sap.check');
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $validator = \Validator::make($request->all(), [
             'file_sap' => 'required|mimes:xlsx,xls,csv'
        ]);

        if ($validator->fails()) {
             return back()->with('error', 'Validasi gagal: ' . $validator->errors()->first());
        }

        try {
            // 2. Cek Upload
            if (!$request->hasFile('file_sap')) {
                return back()->with('error', 'File tidak ditemukan.');
            }

            $file = $request->file('file_sap');
            $extension = $file->getClientOriginalExtension();
            $filename = 'sap_check_' . time() . '.' . $extension;
            
            // 3. Simpan File Sementara (Manual Move)
            $destinationPath = storage_path('app/temp');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            
            $file->move($destinationPath, $filename);
            $fullPath = $destinationPath . DIRECTORY_SEPARATOR . $filename;

            if (!file_exists($fullPath)) {
                return back()->with('error', 'Gagal menyimpan file ke: ' . $fullPath);
            }

            // 4. Parse Excel
            $data = \Maatwebsite\Excel\Facades\Excel::toArray(new \App\Imports\SapImport, $fullPath);
            
            // Cleanup File
            @unlink($fullPath);
            
            $sheet = $data[0] ?? [];

            if (empty($sheet)) {
                return back()->with('error', 'Sheet Excel kosong atau tidak terbaca.');
            }

            // 5. Detect Header (Fuzzy)
            $headerRowIndex = null;
            $colIndexes = [
                'material' => null,
                'desc' => null,
                'stock' => null
            ];

            foreach ($sheet as $index => $row) {
                // Konversi semua ke lowercase string trim
                $rowValues = array_map(fn($v) => strtolower(trim((string)$v)), $row);
                
                $matIdx = false;
                $stockIdx = false;

                foreach ($rowValues as $idx => $val) {
                    // Logic Refined:
                    // 1. Material: must contain 'material', must NOT contain 'desc', 'type', 'group'
                    if (str_contains($val, 'material') && 
                        !str_contains($val, 'desc') && 
                        !str_contains($val, 'type') && 
                        !str_contains($val, 'group')) {
                        $matIdx = $idx;
                    }
                    if (str_contains($val, 'unrestricted') && str_contains($val, 'stock')) {
                        $stockIdx = $idx;
                    }
                    if (str_contains($val, 'description')) {
                         $colIndexes['desc'] = $idx;
                    }
                }
                
                if ($matIdx !== false && $stockIdx !== false) {
                    $headerRowIndex = $index;
                    $colIndexes['material'] = $matIdx;
                    $colIndexes['stock'] = $stockIdx;
                    $colIndexes['desc'] = $colIndexes['desc'] ?? array_search('material description', $rowValues);
                    break;
                }
            }

            if ($headerRowIndex === null) {
                return back()->with('error', 'Format file tidak sesuai. Kolom "Material" dan "Unrestricted Stock" tidak ditemukan. Pastikan header sesuai.');
            }

            // 6. Extraction & Mapping
            $sapData = [];
            for ($i = $headerRowIndex + 1; $i < count($sheet); $i++) {
                $row = $sheet[$i];
                $codeRaw = trim((string)($row[$colIndexes['material']] ?? ''));
                if ($codeRaw === '') continue;
                $code = $this->normalizeMaterialCode($codeRaw);
                if ($code === '') continue;
                
                $stockRaw = $row[$colIndexes['stock']] ?? 0;
                if (is_string($stockRaw)) {
                    $stockRaw = str_replace(',', '', $stockRaw);
                }
                $stock = (float)$stockRaw;

                $desc = isset($colIndexes['desc']) && isset($row[$colIndexes['desc']]) ? $row[$colIndexes['desc']] : '-';

                $sapData[$code] = [
                    'code' => $code,
                    'desc' => $desc,
                    'stock' => $stock
                ];
            }

            // 7. Ambil Data Database
            $user = auth()->user();
            $unitId = null;
            if ($user && $user->unit_id && (!$user->unit || !$user->unit->is_induk)) {
                $unitId = $user->unit_id;
            }

            $dbRows = \DB::table('materials as m')
                ->leftJoin('material_stocks as ms', function ($join) use ($unitId) {
                    $join->on('m.id', '=', 'ms.material_id');
                    if ($unitId) {
                        $join->where('ms.unit_id', $unitId);
                    }
                })
                ->whereNotNull('m.material_code')
                ->groupBy('m.id', 'm.material_code', 'm.material_description')
                ->select(
                    'm.material_code',
                    'm.material_description',
                    \DB::raw('COALESCE(SUM(ms.unrestricted_use_stock), 0) as stock_fisik')
                )
                ->get();

            $dbMaterials = $dbRows->mapWithKeys(function ($row) {
                $code = $this->normalizeMaterialCode($row->material_code);
                if ($code === '') {
                    return [];
                }
                return [$code => $row];
            });
            
            // 8. Bandingkan
            $results = [];
            $allCodes = array_unique(array_merge(array_keys($sapData), $dbMaterials->keys()->toArray()));

            foreach ($allCodes as $code) {
                if (empty($code)) continue;

                $sapItem = $sapData[$code] ?? null;
                $dbItem = $dbMaterials[$code] ?? null;

                $stockSap = $sapItem ? $sapItem['stock'] : 0;
                $stockFisik = $dbItem ? (float)$dbItem->stock_fisik : 0;
                
                $name = $dbItem ? $dbItem->material_description : ($sapItem['desc'] ?? '-'); 
                $normalisasi = $code; 

                $diff = $stockFisik - $stockSap;
                
                $results[] = [
                    'normalisasi' => $normalisasi,
                    'nama_material' => $name,
                    'stock_fisik' => $stockFisik,
                    'stock_sap' => $stockSap,
                    'selisih' => $diff,
                    'keterangan' => ($diff == 0) ? 'Sesuai' : (($diff > 0) ? 'Fisik Lebih Banyak' : 'SAP Lebih Banyak')
                ];
            }

            // Sort by selisih absolute desc
            usort($results, function($a, $b) {
                if (abs($a['selisih']) == abs($b['selisih'])) return 0;
                return (abs($a['selisih']) > abs($b['selisih'])) ? -1 : 1;
            });

            if (empty($results)) {
                 return back()->with('error', 'Tidak ada data material yang cocok antara file Excel dan Database.');
            }
            
            return view('sap.check', compact('results'));

        } catch (\Throwable $e) {
            dd('DEBUG CATCH: Exception Caught!', $e->getMessage(), $e->getTraceAsString());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
