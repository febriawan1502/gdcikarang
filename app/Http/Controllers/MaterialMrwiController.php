<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\MaterialMrwi;
use App\Models\MaterialMrwiDetail;
use App\Models\MaterialMrwiSerialMove;
use App\Models\MaterialMrwiStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\MrwiImport;
use App\Models\Setting;

class MaterialMrwiController extends Controller
{
    public function index()
    {
        return view('material.mrwi-index');
    }

    public function getData(Request $request)
    {
        $user = Auth::user();
        $unitId = null;
        if ($user && $user->unit_id && (!$user->unit || !$user->unit->is_induk)) {
            $unitId = $user->unit_id;
        }

        $mrwi = MaterialMrwi::with(['creator', 'details'])
            ->when($unitId, function ($query) use ($unitId) {
                $query->where('unit_id', $unitId);
            })
            ->orderBy('tanggal_masuk', 'desc');

        return DataTables::of($mrwi)
            ->filter(function ($query) use ($request) {
                if ($request->has('search') && $request->search['value']) {
                    $searchValue = strtolower($request->search['value']);
                    $query->where(function ($q) use ($searchValue) {
                        $q->whereRaw('LOWER(nomor_mrwi) LIKE ?', ["%{$searchValue}%"])
                            ->orWhereRaw('LOWER(ulp_pengirim) LIKE ?', ["%{$searchValue}%"])
                            ->orWhereRaw('LOWER(sumber) LIKE ?', ["%{$searchValue}%"])
                            ->orWhereRaw('LOWER(berdasarkan) LIKE ?', ["%{$searchValue}%"]);
                    });
                }

                if ($request->filled('status')) {
                    $query->where('status', $request->status);
                }

                if ($request->filled('start_date')) {
                    $query->whereDate('tanggal_masuk', '>=', $request->start_date);
                }
                if ($request->filled('end_date')) {
                    $query->whereDate('tanggal_masuk', '<=', $request->end_date);
                }
            })
            ->addIndexColumn()
            ->editColumn('tanggal_masuk', function ($row) {
                return optional($row->tanggal_masuk)->format('d/m/Y');
            })
            ->addColumn('total_qty', function ($row) {
                return $row->details->sum('qty');
            })
            ->addColumn('ulp_pengirim', function ($row) {
                return $row->ulp_pengirim ?? $row->sumber ?? '-';
            })
            ->addColumn('creator_name', function ($row) {
                return $row->creator->nama ?? '-';
            })
            ->addColumn('status_badge', function ($row) {
                $map = [
                    'DRAFT' => 'bg-gray-100 text-gray-800 border border-gray-200',
                    'MENUNGGU_KLASIFIKASI' => 'bg-yellow-100 text-yellow-800 border border-yellow-200',
                    'SELESAI' => 'bg-green-100 text-green-800 border border-green-200',
                ];
                $class = $map[$row->status] ?? 'bg-gray-100 text-gray-800 border border-gray-200';
                return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' . $class . '">'
                    . $row->status . '</span>';
            })
            ->addColumn('action', function ($row) {
                return '<div class="action-buttons">
                    <a href="' . route('material-mrwi.show', $row->id) . '" class="action-btn view" title="Detail">
                        <i class="fa fa-eye"></i>
                    </a>
                </div>';
            })
            ->rawColumns(['status_badge', 'action'])
            ->make(true);
    }

    public function create()
    {
        return view('material.mrwi-create');
    }

    public function preview(Request $request)
    {
        $request->validate([
            'tanggal_masuk' => 'required|date',
            'ulp_pengirim' => 'required|string|max:255',
            'ex_gardu' => 'required|string|max:255',
            'vendor_pengirim' => 'nullable|string|max:255',
            'nama_pengirim' => 'required|string|max:255',
            'kategori_material' => 'required|in:Trafo,Kubikel',
            'kategori_kerusakan' => 'required|string|max:255',
            'mrwi_file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        $file = $request->file('mrwi_file');
        if (!$file) {
            return back()->withErrors(['mrwi_file' => 'File tidak ditemukan.'])->withInput();
        }
        $extension = $file->getClientOriginalExtension();
        $filename = 'mrwi_' . time() . '.' . $extension;
        $destinationPath = storage_path('app/temp');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }
        $file->move($destinationPath, $filename);
        $fullPath = $destinationPath . DIRECTORY_SEPARATOR . $filename;
        if (!file_exists($fullPath)) {
            return back()->withErrors(['mrwi_file' => 'Gagal menyimpan file sementara.'])->withInput();
        }

        $rows = Excel::toArray(new MrwiImport(), $fullPath);
        $sheet = $rows[0] ?? [];
        @unlink($fullPath);
        if (count($sheet) < 2) {
            return back()->withErrors(['mrwi_file' => 'File tidak memiliki data.'])->withInput();
        }

        $headerRow = $sheet[0];
        $normalizedHeaders = array_map([$this, 'normalizeHeader'], $headerRow);
        $expectedHeaders = [
            'no_material',
            'nama_material',
            'qty',
            'satuan',
            'serial_number',
            'attb_limbah',
            'status_anggaran',
            'no_asset',
            'nama_pabrikan',
            'tahun_buat',
            'id_pelanggan',
            'klasifikasi',
            'no_polis',
        ];

        $headerIndexes = [];
        foreach ($normalizedHeaders as $index => $header) {
            if (in_array($header, $expectedHeaders, true)) {
                $headerIndexes[$header] = $index;
            }
        }

        $trafoBrands = json_decode(Setting::get('mrwi_brands_trafo', '[]'), true) ?? [];
        $kubikelBrands = json_decode(Setting::get('mrwi_brands_kubikel', '[]'), true) ?? [];

        $items = [];
        foreach (array_slice($sheet, 1) as $index => $row) {
            $rowData = [];
            foreach ($expectedHeaders as $key) {
                $colIndex = $headerIndexes[$key] ?? null;
                $rowData[$key] = ($colIndex !== null) ? ($row[$colIndex] ?? null) : null;
            }

            // Skip empty rows
            $isEmpty = true;
            foreach ($rowData as $val) {
                if (trim((string) $val) !== '') {
                    $isEmpty = false;
                    break;
                }
            }
            if ($isEmpty) {
                continue;
            }

            $namaMaterialRaw = trim((string) ($rowData['nama_material'] ?? ''));
            $namaMaterialLower = strtolower($namaMaterialRaw);
            $hasPrefix = str_starts_with($namaMaterialLower, 'cub;') || str_starts_with($namaMaterialLower, 'trf;');
            if (!$hasPrefix) {
                continue;
            }

            // Parsing without strict validation
            $rowNumber = $index + 2;
            $klasifikasi = (int) $rowData['klasifikasi'];
            $qty = (int) $rowData['qty'];
            $serialNumber = trim((string) ($rowData['serial_number'] ?? ''));
            $idPelanggan = trim((string) ($rowData['id_pelanggan'] ?? ''));

            $material = null;
            $noMaterial = trim((string) $rowData['no_material']);
            $namaMaterial = $namaMaterialRaw;

            if ($noMaterial !== '') {
                $candidates = [$noMaterial];
                if (stripos($noMaterial, 'E+') !== false || stripos($noMaterial, 'e+') !== false) {
                    $asFloat = (float) $noMaterial;
                    if ($asFloat > 0) $candidates[] = sprintf('%.0f', $asFloat);
                }
                $normalizedDigits = preg_replace('/\D+/', '', $noMaterial);
                if ($normalizedDigits !== '' && $normalizedDigits !== $noMaterial) $candidates[] = $normalizedDigits;
                $strippedZero = ltrim($normalizedDigits ?: $noMaterial, '0');
                if ($strippedZero !== '' && !in_array($strippedZero, $candidates, true)) $candidates[] = $strippedZero;

                foreach ($candidates as $code) {
                    if ($code === '') continue;
                    $material = Material::where('material_code', $code)->first();
                    if ($material) break;
                    $material = Material::where('material_code', 'like', '%' . $code)->first();
                    if ($material) break;
                }
            }

            if (!$material && $namaMaterial !== '') {
                $material = Material::whereRaw('LOWER(material_description) LIKE ?', ['%' . strtolower($namaMaterial) . '%'])->first();
            }

            $currentName = $namaMaterial ?: ($material->material_description ?? '');
            $normalizedName = strtolower($currentName);
            $materialType = 'other';
            if (str_starts_with($normalizedName, 'trf;') || str_contains($normalizedName, 'trafo')) {
                $materialType = 'trafo';
            } elseif (str_starts_with($normalizedName, 'cub;') || str_contains($normalizedName, 'cub') || str_contains($normalizedName, 'kubikel') || str_contains($normalizedName, 'tm k')) {
                $materialType = 'kubikel';
            }

            if (!in_array($materialType, ['trafo', 'kubikel'], true)) {
                continue;
            }

            $items[] = [
                'row_number' => $rowNumber,
                'material_id' => $material ? $material->id : null,
                'no_material' => $noMaterial ?: ($material->material_code ?? ''),
                'nama_material' => $currentName,
                'material_type' => $materialType,
                'qty' => $qty > 0 ? $qty : ($rowData['qty'] ?? ''),
                'satuan' => trim((string) ($rowData['satuan'] ?? '')) ?: ($material->base_unit_of_measure ?? ''),
                'serial_number' => $serialNumber,
                'attb_limbah' => $rowData['attb_limbah'] ?? null,
                'status_anggaran' => $rowData['status_anggaran'] ?? null,
                'no_asset' => $rowData['no_asset'] ?? null,
                'nama_pabrikan' => $rowData['nama_pabrikan'] ?? null,
                'tahun_buat' => $rowData['tahun_buat'] ?? null,
                'id_pelanggan' => $idPelanggan,
                'klasifikasi' => $klasifikasi > 0 ? $klasifikasi : ($rowData['klasifikasi'] ?? ''),
                'no_polis' => $rowData['no_polis'] ?? null,
            ];
        }

        $requestData = $request->except(['mrwi_file', '_token']);
        return view('material.mrwi-create', compact('items', 'requestData', 'trafoBrands', 'kubikelBrands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_masuk' => 'required|date',
            'ulp_pengirim' => 'required|string|max:255',
            'ex_gardu' => 'required|string|max:255',
            'vendor_pengirim' => 'nullable|string|max:255',
            'nama_pengirim' => 'required|string|max:255',
            'kategori_material' => 'required|in:Trafo,Kubikel',
            'kategori_kerusakan' => 'required|string|max:255',
            'items' => 'required|array|min:1',
        ]);

        $errors = [];
        $validatedItems = [];

        foreach ($request->items as $index => $item) {
            $rowNum = $index + 1;

            $materialId = $item['material_id'] ?? null;
            if (!$materialId || !Material::find($materialId)) {
                $errors[] = "Baris #{$rowNum}: Material tidak valid.";
            }

            $mandatory = ['qty', 'satuan', 'serial_number', 'klasifikasi', 'id_pelanggan'];
            foreach ($mandatory as $field) {
                if (empty($item[$field])) {
                    $errors[] = "Baris #{$rowNum}: Kolom '$field' wajib diisi.";
                }
            }
            if (empty($item['nama_pabrikan'] ?? null)) {
                $errors[] = "Baris #{$rowNum}: Tolong pilih merk material. Kalau tidak ada, hubungi admin UID.";
            }

            $klasifikasi = (int) ($item['klasifikasi'] ?? 0);
            if ($klasifikasi < 1 || $klasifikasi > 6) {
                $errors[] = "Baris #{$rowNum}: Klasifikasi tidak valid (1-6).";
            }
            if ((int) ($item['qty'] ?? 0) <= 0) {
                $errors[] = "Baris #{$rowNum}: Qty harus > 0.";
            }

            $serialNumber = trim((string)($item['serial_number'] ?? ''));
            if ($serialNumber && MaterialMrwiDetail::where('serial_number', $serialNumber)->exists()) {
                $errors[] = "Baris #{$rowNum}: Serial Number '$serialNumber' (PLN) sudah terdaftar.";
            }

            $idPelanggan = trim((string)($item['id_pelanggan'] ?? ''));
            if ($idPelanggan && MaterialMrwiDetail::where('id_pelanggan', $idPelanggan)->exists()) {
                $errors[] = "Baris #{$rowNum}: ID Pelanggan '$idPelanggan' (Pabrikan) sudah terdaftar.";
            }

            $validatedItems[] = $item;
        }

        if (!empty($errors)) {
            $trafoBrands = json_decode(Setting::get('mrwi_brands_trafo', '[]'), true) ?? [];
            $kubikelBrands = json_decode(Setting::get('mrwi_brands_kubikel', '[]'), true) ?? [];
            return view('material.mrwi-create', [
                'items' => $request->items,
                'requestData' => $request->except(['items', '_token']),
                'validationErrors' => $errors,
                'trafoBrands' => $trafoBrands,
                'kubikelBrands' => $kubikelBrands,
            ]);
        }

        $user = Auth::user();
        $unitId = $user->unit_id ?? null;

        DB::beginTransaction();
        try {
            $mrwi = MaterialMrwi::create([
                'unit_id' => $unitId,
                'nomor_mrwi' => MaterialMrwi::generateNomorMrwi(),
                'tanggal_masuk' => $request->tanggal_masuk,
                'sumber' => $request->ulp_pengirim,
                'ulp_pengirim' => $request->ulp_pengirim,
                'ex_gardu' => $request->ex_gardu,
                'vendor_pengirim' => $request->vendor_pengirim,
                'nama_pengirim' => $request->nama_pengirim,
                'kategori_material' => $request->kategori_material,
                'kategori_kerusakan' => $request->kategori_kerusakan,
                'berdasarkan' => $request->berdasarkan,
                'lokasi' => $request->lokasi,
                'status' => 'SELESAI',
                'catatan' => $request->catatan,
                'created_by' => $user->id ?? null,
            ]);

            foreach ($validatedItems as $item) {
                $material = Material::find($item['material_id']);
                $detail = MaterialMrwiDetail::create([
                    'material_mrwi_id' => $mrwi->id,
                    'material_id' => $item['material_id'],
                    'no_material' => $item['no_material'] ?? $material->material_code,
                    'nama_material' => $item['nama_material'] ?? $material->material_description,
                    'qty' => $item['qty'],
                    'satuan' => $item['satuan'],
                    'serial_number' => $item['serial_number'],
                    'attb_limbah' => $item['attb_limbah'] ?? null,
                    'status_anggaran' => $item['status_anggaran'] ?? null,
                    'no_asset' => $item['no_asset'] ?? null,
                    'nama_pabrikan' => $item['nama_pabrikan'] ?? null,
                    'tahun_buat' => $item['tahun_buat'] ?? null,
                    'id_pelanggan' => $item['id_pelanggan'] ?? null,
                    'klasifikasi' => $item['klasifikasi'],
                    'no_polis' => $item['no_polis'] ?? null,
                    'catatan' => $item['catatan'] ?? null,
                ]);

                $bucket = $this->resolveStockBucket((int) $detail->klasifikasi);
                $stock = MaterialMrwiStock::firstOrCreate(
                    ['material_id' => $detail->material_id, 'unit_id' => $unitId],
                    ['standby_stock' => 0, 'garansi_stock' => 0, 'perbaikan_stock' => 0, 'rusak_stock' => 0]
                );
                $stock->{$bucket} = ($stock->{$bucket} ?? 0) + (int) $detail->qty;
                $stock->save();

                $bucketLabel = $this->resolveBucketLabel((int) $detail->klasifikasi);
                MaterialMrwiSerialMove::create([
                    'serial_number' => $detail->serial_number,
                    'material_id' => $detail->material_id,
                    'unit_id' => $unitId,
                    'jenis' => 'masuk',
                    'status_bucket' => $bucketLabel,
                    'reference_type' => 'mrwi',
                    'reference_number' => $mrwi->nomor_mrwi,
                    'tanggal' => $mrwi->tanggal_masuk,
                ]);
            }

            DB::commit();
            return redirect()->route('material-mrwi.index')->with('success', 'MRWI berhasil disimpan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan MRWI: ' . $e->getMessage())->withInput();
        }
    }

    private function normalizeHeader(string $header): string
    {
        $header = strtolower(trim($header));
        $header = preg_replace('/[^a-z0-9]+/i', '_', $header);
        $header = trim($header, '_');
        return $header;
    }

    public function show($id)
    {
        $mrwi = MaterialMrwi::with(['details', 'creator'])->findOrFail($id);
        return view('material.mrwi-show', compact('mrwi'));
    }

    private function resolveStockBucket(int $klasifikasi): string
    {
        if ($klasifikasi === 1) {
            return 'standby_stock';
        }
        if ($klasifikasi === 2) {
            return 'garansi_stock';
        }
        if ($klasifikasi === 3) {
            return 'perbaikan_stock';
        }
        if (in_array($klasifikasi, [4, 5, 6], true)) {
            return 'rusak_stock';
        }

        throw new \InvalidArgumentException('Klasifikasi tidak valid.');
    }

    private function resolveBucketLabel(int $klasifikasi): string
    {
        if ($klasifikasi === 1) {
            return 'standby';
        }
        if ($klasifikasi === 2) {
            return 'garansi';
        }
        if ($klasifikasi === 3) {
            return 'perbaikan';
        }
        if (in_array($klasifikasi, [4, 5, 6], true)) {
            return 'rusak';
        }

        throw new \InvalidArgumentException('Klasifikasi tidak valid.');
    }

    public function getAvailableSerials(Request $request)
    {
        $request->validate([
            'material_id' => 'required|integer',
            'jenis' => 'required|string',
        ]);

        $buckets = match ($request->jenis) {
            'Garansi' => ['garansi'],
            'Perbaikan' => ['perbaikan', 'rusak'],
            'Rusak' => ['rusak'],
            'Standby' => ['standby'],
            default => [],
        };

        if (empty($buckets)) {
            return response()->json([
                'serials' => [],
                'available_count' => 0,
            ]);
        }

        $user = Auth::user();
        $unitId = $user->unit_id ?? null;

        $latestIds = MaterialMrwiSerialMove::selectRaw('MAX(id) as id')
            ->where('material_id', $request->material_id)
            ->when($unitId, function ($query) use ($unitId) {
                $query->where('unit_id', $unitId);
            })
            ->groupBy('serial_number');

        $moves = MaterialMrwiSerialMove::whereIn('id', $latestIds)
            ->whereIn('status_bucket', $buckets)
            ->whereIn('jenis', ['masuk', 'kembali'])
            ->orderBy('serial_number')
            ->get();

        $serials = $moves->pluck('serial_number')->values();

        return response()->json([
            'serials' => $serials,
            'available_count' => $serials->count(),
        ]);
    }

    public function lookupSerial(Request $request)
    {
        $request->validate([
            'serial' => 'required|string',
            'jenis' => 'required|string',
        ]);

        $buckets = match ($request->jenis) {
            'Garansi' => ['garansi'],
            'Perbaikan' => ['perbaikan', 'rusak'],
            'Rusak' => ['rusak'],
            'Standby' => ['standby'],
            default => [],
        };

        if (empty($buckets)) {
            return response()->json([
                'success' => false,
                'message' => 'Jenis surat jalan tidak didukung.',
            ], 422);
        }

        $serial = trim((string) $request->serial);
        $user = Auth::user();
        $unitId = $user->unit_id ?? null;

        $latest = MaterialMrwiSerialMove::where('serial_number', $serial)
            ->when($unitId, function ($query) use ($unitId) {
                $query->where('unit_id', $unitId);
            })
            ->orderByDesc('id')
            ->first();

        // Fallback: Check if it's an ID Pelanggan (Manufacturer Serial)
        if (!$latest) {
            $mappedSerial = \App\Models\MaterialMrwiDetail::where('id_pelanggan', $serial)
                ->value('serial_number');

            if ($mappedSerial) {
                $serial = $mappedSerial; // Use the mapped serial from now on
                $latest = MaterialMrwiSerialMove::where('serial_number', $serial)
                    ->when($unitId, function ($query) use ($unitId) {
                        $query->where('unit_id', $unitId);
                    })
                    ->orderByDesc('id')
                    ->first();
            }
        }

        if (!$latest) {
            return response()->json([
                'success' => false,
                'message' => 'Serial number atau No Seri Pabrikan tidak ditemukan.',
            ], 404);
        }

        if (!in_array($latest->jenis, ['masuk', 'kembali'], true) || !in_array($latest->status_bucket, $buckets)) {
            return response()->json([
                'success' => false,
                'message' => 'Serial number tidak tersedia pada stok yang dipilih.',
            ], 422);
        }

        $material = Material::find($latest->material_id);
        if (!$material) {
            return response()->json([
                'success' => false,
                'message' => 'Material tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'material' => [
                'id' => $material->id,
                'code' => $material->material_code,
                'description' => $material->material_description,
                'satuan' => $material->base_unit_of_measure,
            ],
        ]);
    }
}
