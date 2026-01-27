<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\MaterialMrwi;
use App\Models\MaterialMrwiDetail;
use App\Models\MaterialMrwiSerialMove;
use App\Models\MaterialMrwiStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\MrwiImport;

class MaterialMrwiController extends Controller
{
    public function index()
    {
        return view('material.mrwi-index');
    }

    public function getData(Request $request)
    {
        $user = auth()->user();
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

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_masuk' => 'required|date',
            'sumber' => 'required|string|max:255',
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

        $missing = array_diff($expectedHeaders, array_keys($headerIndexes));
        if (!empty($missing)) {
            return back()->withErrors([
                'mrwi_file' => 'Header tidak sesuai template MRWI.',
            ])->withInput();
        }

        $items = [];
        foreach (array_slice($sheet, 1) as $index => $row) {
            $rowData = [];
            foreach ($expectedHeaders as $key) {
                $colIndex = $headerIndexes[$key];
                $rowData[$key] = $row[$colIndex] ?? null;
            }

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

            $rowNumber = $index + 2;
            $klasifikasi = (int) $rowData['klasifikasi'];
            if ($klasifikasi === 7) {
                return back()->withErrors([
                    'mrwi_file' => "Klasifikasi 7 (Claim Asuransi) tidak didukung. Row: {$rowNumber}",
                ])->withInput();
            }
            if ($klasifikasi < 1 || $klasifikasi > 7) {
                return back()->withErrors([
                    'mrwi_file' => "Klasifikasi tidak valid di row {$rowNumber}.",
                ])->withInput();
            }

            $qty = (int) $rowData['qty'];
            if ($qty <= 0) {
                return back()->withErrors([
                    'mrwi_file' => "Qty tidak valid di row {$rowNumber}.",
                ])->withInput();
            }

            $serialNumber = trim((string) ($rowData['serial_number'] ?? ''));
            if ($serialNumber === '') {
                return back()->withErrors([
                    'mrwi_file' => "Serial Number wajib diisi di row {$rowNumber}.",
                ])->withInput();
            }

            $material = null;
            $noMaterial = trim((string) $rowData['no_material']);
            $namaMaterial = trim((string) $rowData['nama_material']);

            if ($noMaterial !== '') {
                $candidates = [$noMaterial];

                if (stripos($noMaterial, 'E+') !== false || stripos($noMaterial, 'e+') !== false) {
                    $asFloat = (float) $noMaterial;
                    if ($asFloat > 0) {
                        $candidates[] = sprintf('%.0f', $asFloat);
                    }
                }

                $normalizedDigits = preg_replace('/\D+/', '', $noMaterial);
                if ($normalizedDigits !== '' && $normalizedDigits !== $noMaterial) {
                    $candidates[] = $normalizedDigits;
                }

                $strippedZero = ltrim($normalizedDigits ?: $noMaterial, '0');
                if ($strippedZero !== '' && !in_array($strippedZero, $candidates, true)) {
                    $candidates[] = $strippedZero;
                }

                foreach ($candidates as $code) {
                    if ($code === '') {
                        continue;
                    }
                    $material = Material::where('material_code', $code)->first();
                    if ($material) {
                        break;
                    }
                }

                if (!$material) {
                    foreach ($candidates as $code) {
                        if ($code === '') {
                            continue;
                        }
                        $material = Material::where('material_code', 'like', '%' . $code)->first();
                        if ($material) {
                            break;
                        }
                    }
                }
            }

            if (!$material && $namaMaterial !== '') {
                $material = Material::whereRaw('LOWER(material_description) LIKE ?', ['%' . strtolower($namaMaterial) . '%'])->first();
            }
            if (!$material) {
                return back()->withErrors([
                    'mrwi_file' => "Material belum terdaftar. Silahkan buat di daftar material dulu. (Row {$rowNumber}, Normalisasi: {$noMaterial})",
                ])->withInput();
            }

            $items[] = [
                'material_id' => $material->id,
                'no_material' => $noMaterial ?: $material->material_code,
                'nama_material' => $namaMaterial ?: $material->material_description,
                'qty' => $qty,
                'satuan' => trim((string) ($rowData['satuan'] ?? '')) ?: ($material->base_unit_of_measure ?? ''),
                'serial_number' => $serialNumber,
                'attb_limbah' => $rowData['attb_limbah'] ?? null,
                'status_anggaran' => $rowData['status_anggaran'] ?? null,
                'no_asset' => $rowData['no_asset'] ?? null,
                'nama_pabrikan' => $rowData['nama_pabrikan'] ?? null,
                'tahun_buat' => $rowData['tahun_buat'] ?? null,
                'id_pelanggan' => $rowData['id_pelanggan'] ?? null,
                'klasifikasi' => $klasifikasi,
                'no_polis' => $rowData['no_polis'] ?? null,
            ];
        }

        if (empty($items)) {
            return back()->withErrors(['mrwi_file' => 'Tidak ada baris data yang valid.'])->withInput();
        }

        $user = auth()->user();
        $unitId = $user->unit_id ?? null;

        DB::beginTransaction();
        try {
            $mrwi = MaterialMrwi::create([
                'unit_id' => $unitId,
                'nomor_mrwi' => MaterialMrwi::generateNomorMrwi(),
                'tanggal_masuk' => $request->tanggal_masuk,
                'sumber' => $request->sumber,
                'berdasarkan' => $request->berdasarkan,
                'lokasi' => $request->lokasi,
                'status' => 'SELESAI',
                'catatan' => $request->catatan,
                'created_by' => $user->id ?? null,
            ]);

            foreach ($items as $item) {
                $material = Material::find($item['material_id']);
                $detail = MaterialMrwiDetail::create([
                    'material_mrwi_id' => $mrwi->id,
                    'material_id' => $item['material_id'],
                    'no_material' => $item['no_material'] ?? ($material->material_code ?? null),
                    'nama_material' => $item['nama_material'] ?? ($material->material_description ?? ''),
                    'qty' => $item['qty'],
                    'satuan' => $item['satuan'] ?? ($material->base_unit_of_measure ?? ''),
                    'serial_number' => $item['serial_number'] ?? null,
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

        $bucket = match ($request->jenis) {
            'Garansi' => 'garansi',
            'Perbaikan' => 'perbaikan',
            'Rusak' => 'rusak',
            default => null,
        };

        if (!$bucket) {
            return response()->json([
                'serials' => [],
                'available_count' => 0,
            ]);
        }

        $user = auth()->user();
        $unitId = $user->unit_id ?? null;

        $latestIds = MaterialMrwiSerialMove::selectRaw('MAX(id) as id')
            ->where('material_id', $request->material_id)
            ->when($unitId, function ($query) use ($unitId) {
                $query->where('unit_id', $unitId);
            })
            ->groupBy('serial_number');

        $moves = MaterialMrwiSerialMove::whereIn('id', $latestIds)
            ->where('status_bucket', $bucket)
            ->whereIn('jenis', ['masuk', 'kembali'])
            ->orderBy('serial_number')
            ->get();

        $serials = $moves->pluck('serial_number')->values();

        return response()->json([
            'serials' => $serials,
            'available_count' => $serials->count(),
        ]);
    }
}
