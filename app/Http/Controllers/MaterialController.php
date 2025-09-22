<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\Material;
use App\Models\StockOpname;
use App\Models\SuratJalan;
use App\Models\SuratJalanDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SuratJalanExport;
use Barryvdh\DomPDF\Facade\Pdf;

class MaterialController extends Controller
{
    /**
     * Tampilkan daftar material
     */
    public function index()
    {
        $materials = Material::orderBy('nomor_kr')->paginate(15);
        return view('material.index', compact('materials'));
    }

    /**
     * Tampilkan form tambah material
     */
    public function create()
    {
        return view('material.create');
    }

    /**
     * Simpan material baru
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_code' => 'required|string|max:10',
            'company_code_description' => 'required|string|max:100',
            'plant' => 'required|string|max:10',
            'plant_description' => 'required|string|max:100',
            'storage_location' => 'required|string|max:10',
            'storage_location_description' => 'required|string|max:100',
            'material_type' => 'required|string|max:10',
            'material_type_description' => 'required|string|max:100',
            'material_code' => 'required|string|max:50|unique:materials,material_code',
            'material_description' => 'required|string|max:255',
            'material_group' => 'required|string|max:20',
            'base_unit_of_measure' => 'required|string|max:10',
            'valuation_type' => 'required|string|max:20',
            'unrestricted_use_stock' => 'required|numeric|min:0',
            'quality_inspection_stock' => 'nullable|numeric|min:0',
            'blocked_stock' => 'nullable|numeric|min:0',
            'in_transit_stock' => 'nullable|numeric|min:0',
            'project_stock' => 'nullable|numeric|min:0',
            'valuation_class' => 'required|string|max:10',
            'valuation_description' => 'required|string|max:100',
            'harga_satuan' => 'required|numeric|min:0',
            'total_harga' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'pabrikan' => 'required|string|max:100',
            'qty' => 'required|integer|min:1',
            'tanggal_terima' => 'required|date',
            'keterangan' => 'nullable|string|max:255',
            'rak' => 'nullable|string|max:50',
            'status' => 'required|in:' . implode(',', [
                Material::STATUS_SELESAI,
                Material::STATUS_PENDING,
                Material::STATUS_PROSES
            ]),
        ], [
            'company_code.required' => 'Company Code wajib diisi',
            'plant.required' => 'Plant wajib diisi',
            'material_code.required' => 'Material Code wajib diisi',
            'material_code.unique' => 'Material Code sudah digunakan',
            'material_description.required' => 'Deskripsi Material wajib diisi',
            'qty.required' => 'Quantity wajib diisi',
            'qty.min' => 'Quantity minimal 1',
            'tanggal_terima.required' => 'Tanggal terima wajib diisi',
            'tanggal_terima.date' => 'Format tanggal tidak valid',
            'harga_satuan.required' => 'Harga satuan wajib diisi',
            'total_harga.required' => 'Total harga wajib diisi',
            'pabrikan.required' => 'Pabrikan wajib diisi',
            'status.required' => 'Status wajib dipilih',
            'status.in' => 'Status tidak valid',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Log untuk debugging
            Log::info('=== MATERIAL CREATE ATTEMPT ===');
            Log::info('Request data:', $request->all());
            Log::info('User ID: ' . auth()->id());
            
            // Generate nomor otomatis
            $lastMaterial = Material::orderBy('nomor', 'desc')->first();
            $nextNomor = $lastMaterial ? $lastMaterial->nomor + 1 : 1;

            $materialData = $request->all();
            $materialData['nomor'] = $nextNomor;
            $materialData['tanggal_terima'] = Carbon::parse($request->tanggal_terima);
            
            \Illuminate\Support\Facades\Log::info('Material data to be saved:', $materialData);

            $material = Material::create($materialData);
            
            Log::info('Material created successfully with ID: ' . $material->id);

            return redirect()->route('dashboard')->with('success', 'Material berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error('Material creation failed: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return back()->withErrors(['error' => 'Gagal menyimpan material: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Tampilkan form edit material
     */
    public function edit($id)
    {
        $material = Material::findOrFail($id);
        return view('material.edit', compact('material'));
    }

    /**
     * Update material
     */
    public function update(Request $request, $id)
    {
        $material = Material::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'company_code' => 'required|string|max:10',
            'company_code_description' => 'required|string|max:100',
            'plant' => 'required|string|max:10',
            'plant_description' => 'required|string|max:100',
            'storage_location' => 'required|string|max:10',
            'storage_location_description' => 'required|string|max:100',
            'material_type' => 'required|string|max:10',
            'material_type_description' => 'required|string|max:100',
            'material_code' => 'required|string|max:50|unique:materials,material_code,' . $id,
            'material_description' => 'required|string|max:255',
            'material_group' => 'required|string|max:20',
            'base_unit_of_measure' => 'required|string|max:10',
            'valuation_type' => 'required|string|max:20',
            'unrestricted_use_stock' => 'required|numeric|min:0',
            'quality_inspection_stock' => 'nullable|numeric|min:0',
            'blocked_stock' => 'nullable|numeric|min:0',
            'in_transit_stock' => 'nullable|numeric|min:0',
            'project_stock' => 'nullable|numeric|min:0',
            'valuation_class' => 'required|string|max:10',
            'valuation_description' => 'required|string|max:100',
            'harga_satuan' => 'required|numeric|min:0',
            'total_harga' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'pabrikan' => 'required|string|max:100',
            'qty' => 'required|integer|min:1',
            'tanggal_terima' => 'required|date',
            'keterangan' => 'nullable|string|max:255',
            'rak' => 'nullable|string|max:50',
            'status' => 'required|in:' . implode(',', [
                Material::STATUS_SELESAI,
                Material::STATUS_PENDING,
                Material::STATUS_PROSES
            ]),
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $materialData = $request->all();
            $materialData['tanggal_terima'] = Carbon::parse($request->tanggal_terima);

            $material->update($materialData);

            return redirect()->route('dashboard')->with('success', 'Material berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal memperbarui material: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Template kosong untuk Input Material Masuk
     */
    public function inputMaterialMasuk()
    {
        return view('material.input-masuk');
    }

    /**
     * Template kosong untuk Surat Jalan
     */
    public function suratJalan()
    {
        return view('material.surat-jalan');
    }

    /**
     * Tampilkan detail material
     */
    public function show(Material $material)
    {
        return view('material.show', compact('material'));
    }

    /**
     * Tampilkan form stock opname
     */
    public function stockOpname()
    {
        $materials = Material::select('id', 'nomor_kr', 'material_description', 'qty', 'base_unit_of_measure')
                            ->orderBy('nomor_kr')
                            ->get();
        
        return view('material.stock-opname', compact('materials'));
    }

    /**
     * Proses stock opname - update quantity material
     */
    public function processStockOpname(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'material_id' => 'required|exists:materials,id',
            'new_qty' => 'required|integer|min:0',
            'keterangan' => 'nullable|string|max:255'
        ], [
            'material_id.required' => 'Material harus dipilih',
            'material_id.exists' => 'Material tidak ditemukan',
            'new_qty.required' => 'Quantity baru wajib diisi',
            'new_qty.integer' => 'Quantity harus berupa angka',
            'new_qty.min' => 'Quantity tidak boleh kurang dari 0'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $material = Material::findOrFail($request->material_id);
            $oldQty = $material->qty;
            
            // Update quantity material
            $material->update([
                'qty' => $request->new_qty,
                'keterangan' => $request->keterangan ? $request->keterangan : $material->keterangan,
                'updated_by' => auth()->id()
            ]);

            $message = "Stock opname berhasil! Material '{$material->nomor_kr}' diupdate dari {$oldQty} menjadi {$request->new_qty} {$material->base_unit_of_measure}";
            
            return redirect()->route('material.stock-opname')->with('success', $message);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal melakukan stock opname: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * API untuk pencarian material (AJAX)
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        $materials = Material::where('material_code', 'LIKE', "%{$query}%")
                            ->orWhere('material_description', 'LIKE', "%{$query}%")
                            ->select('id', 'nomor_kr', 'material_description', 'qty', 'base_unit_of_measure')
                            ->limit(10)
                            ->get();
        
        return response()->json($materials);
    }

    /**
     * API untuk autocomplete material berdasarkan normalisasi
     */
    public function autocomplete(Request $request)
    {
        $query = $request->get('query') ?: $request->get('q');
        
        if (strlen($query) < 3) {
            return response()->json([]);
        }
        
        $materials = Material::where('material_code', 'LIKE', "%{$query}%")
                            ->orWhere('material_description', 'LIKE', "%{$query}%")
                            ->select('id', 'material_code', 'material_description', 'base_unit_of_measure', 'unrestricted_use_stock')
                            ->limit(10)
                            ->get()
                            ->map(function($material) {
                                return [
                                    'id' => $material->id,
                                    'text' => $material->material_code . ' - ' . $material->material_description,
                                    'material_code' => $material->material_code,
                                    'material_description' => $material->material_description,
                                    'base_unit_of_measure' => $material->base_unit_of_measure,
                                    'unrestricted_use_stock' => $material->unrestricted_use_stock
                                ];
                            });
        
        return response()->json($materials);
    }

    /**
     * Get data untuk DataTable stock opname
     */
    public function getStockOpnameData(Request $request)
    {
        $stockOpnames = StockOpname::with(['material', 'createdBy'])
                                  ->select('stock_opnames.*');

        return datatables($stockOpnames)
            ->addIndexColumn()
            ->editColumn('created_at', function($row) {
                return $row->created_at->format('d/m/Y H:i');
            })
            ->editColumn('stock_system', function($row) {
                return number_format($row->stock_system, 2);
            })
            ->editColumn('stock_fisik', function($row) {
                return number_format($row->stock_fisik, 2);
            })
            ->editColumn('selisih', function($row) {
                $selisih = $row->selisih;
                $class = $selisih > 0 ? 'text-success' : ($selisih < 0 ? 'text-danger' : 'text-muted');
                $sign = $selisih > 0 ? '+' : '';
                return '<span class="' . $class . '">' . $sign . number_format($selisih, 2) . '</span>';
            })
            ->editColumn('keterangan', function($row) {
                return $row->keterangan ?: '-';
            })
            ->rawColumns(['selisih'])
            ->make(true);
    }

    /**
     * Simpan data stock opname
     */
    public function storeStockOpname(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'material_id' => 'required|exists:materials,id',
            'stock_fisik' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Ambil data material
            $material = Material::findOrFail($request->material_id);
            $stockSystem = $material->unrestricted_use_stock;
            $stockFisik = $request->stock_fisik;
            $selisih = $stockFisik - $stockSystem;

            // Insert ke tabel stock_opnames
            $stockOpname = StockOpname::create([
                'material_id' => $material->id,
                'material_description' => $material->material_description,
                'stock_fisik' => $stockFisik,
                'stock_system' => $stockSystem,
                'selisih' => $selisih,
                'keterangan' => $request->keterangan,
                'created_by' => Auth::id()
            ]);

            // Update unrestricted_stock di tabel materials
            $material->update([
                'unrestricted_use_stock' => $stockFisik
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Stock opname berhasil disimpan',
                'data' => $stockOpname
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan stock opname: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus material
     */
    public function destroy(Material $material)
    {
        try {
            $materialName = $material->nomor_kr;
            $material->delete();
            
            return redirect()->route('material.index')->with('success', "Material '{$materialName}' berhasil dihapus.");
        } catch (\Exception $e) {
            return redirect()->route('material.index')->with('error', 'Gagal menghapus material: ' . $e->getMessage());
        }
    }

    // ==================== SURAT JALAN METHODS ====================

    /**
     * Tampilkan halaman surat jalan
     */
    public function indexSuratJalan()
    {
        return view('material.surat-jalan');
    }

    /**
     * Data untuk DataTables surat jalan
     */
    public function getSuratJalanData(Request $request)
    {
        $suratJalans = SuratJalan::with(['creator', 'approver'])
                                 ->select('surat_jalan.*');

        return datatables($suratJalans)
            ->addIndexColumn()
            ->editColumn('tanggal', function($row) {
                return $row->tanggal->format('d/m/Y');
            })
            ->editColumn('status', function($row) {
                $badge = $row->status == 'APPROVED' ? 'success' : 'warning';
                return '<span class="badge badge-' . $badge . '">' . $row->status . '</span>';
            })
            ->editColumn('created_by', function($row) {
                return $row->creator->nama ?? '-';
            })
            ->addColumn('action', function($row) {
                $actions = '';
                if ($row->status == 'BUTUH_PERSETUJUAN') {
                    $actions .= '<a href="' . route('surat-jalan.edit', $row->id) . '" class="btn btn-sm btn-primary mr-1"><i class="fa fa-edit"></i></a>';
                    $actions .= '<button class="btn btn-sm btn-danger" onclick="deleteSuratJalan(' . $row->id . ')"><i class="fa fa-trash"></i></button>';
                } else {
                    $actions .= '<button class="btn btn-sm btn-success" onclick="printSuratJalan(' . $row->id . ')"><i class="fa fa-print"></i></button>';
                }
                return $actions;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    /**
     * Tampilkan form create surat jalan
     */
    public function createSuratJalan()
    {
        $nomorSurat = (new SuratJalan())->generateNomorSurat('Normal');
        $materials = Material::all();
        return view('material.surat-jalan-create', compact('nomorSurat', 'materials'));
    }

    /**
     * Simpan surat jalan baru
     */
    public function storeSuratJalan(Request $request)
    {
        // Log data yang diterima dari frontend
        Log::info('=== SURAT JALAN FORM SUBMISSION ===');
        Log::info('Request data:', $request->all());
        Log::info('User ID: ' . auth()->id());
        $validator = Validator::make($request->all(), [
            'nomor_surat' => 'required|string',
            'jenis_surat_jalan' => 'required|in:Normal,Gangguan,Garansi,Peminjaman',
            'tanggal' => 'required|date',
            'kepada' => 'required|string|max:255',
            'berdasarkan' => 'required|string',
            'security' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'kendaraan' => 'nullable|string|max:255',
            'no_polisi' => 'nullable|string|max:20',
            'pengemudi' => 'nullable|string|max:255',
            'materials' => 'required|array|min:1',
            'materials.*.material_id' => 'required|exists:materials,id',
            'materials.*.quantity' => 'required|integer|min:1',
            'materials.*.satuan' => 'required|string',
            'materials.*.keterangan' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed:', $validator->errors()->toArray());
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        Log::info('Validation passed, starting database transaction');

        try {
            DB::beginTransaction();

            Log::info('Creating SuratJalan record with data:', [
                'nomor_surat' => $request->nomor_surat,
                'jenis_surat_jalan' => $request->jenis_surat_jalan,
                'tanggal' => $request->tanggal,
                'kepada' => $request->kepada,
                'berdasarkan' => $request->berdasarkan,
                'security' => $request->security,
                'keterangan' => $request->keterangan,
                'kendaraan' => $request->kendaraan,
                'no_polisi' => $request->no_polisi,
                'pengemudi' => $request->pengemudi,
                'status' => 'BUTUH_PERSETUJUAN',
                'created_by' => auth()->id()
            ]);

            $suratJalan = SuratJalan::create([
                'nomor_surat' => $request->nomor_surat,
                'jenis_surat_jalan' => $request->jenis_surat_jalan,
                'tanggal' => $request->tanggal,
                'kepada' => $request->kepada,
                'berdasarkan' => $request->berdasarkan,
                'security' => $request->security,
                'keterangan' => $request->keterangan,
                'kendaraan' => $request->kendaraan,
                'no_polisi' => $request->no_polisi,
                'pengemudi' => $request->pengemudi,
                'status' => 'BUTUH_PERSETUJUAN',
                'created_by' => auth()->id()
            ]);

            Log::info('SuratJalan created successfully with ID:', ['id' => $suratJalan->id]);
            Log::info('Creating SuratJalanDetail records for materials:', $request->materials);

            foreach ($request->materials as $index => $material) {
                Log::info("Creating detail #{$index}", ['material' => $material]);
                $detail = SuratJalanDetail::create([
                    'surat_jalan_id' => $suratJalan->id,
                    'material_id' => $material['material_id'],
                    'quantity' => $material['quantity'],
                    'satuan' => $material['satuan'],
                    'keterangan' => $material['keterangan'] ?? null
                ]);
                Log::info("Detail created with ID: {$detail->id}");
                
                // Update material stock - mengurangi qty dan unrestricted_use_stock
                $materialModel = Material::find($material['material_id']);
                if ($materialModel) {
                    $materialModel->qty = $materialModel->qty - $material['quantity'];
                    $materialModel->unrestricted_use_stock = $materialModel->unrestricted_use_stock - $material['quantity'];
                    $materialModel->save();
                    Log::info("Material stock updated for ID: {$material['material_id']}", [
                        'new_qty' => $materialModel->qty,
                        'new_unrestricted_stock' => $materialModel->unrestricted_use_stock
                    ]);
                }
            }

            DB::commit();
            Log::info('Transaction committed successfully');

            Log::info('Redirecting to surat jalan index with success message');
            return redirect()->route('material.surat-jalan')
                           ->with('success', 'Surat jalan berhasil dibuat dan menunggu persetujuan.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating surat jalan:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                           ->with('error', 'Gagal membuat surat jalan: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Tampilkan detail surat jalan
     */
    public function showSuratJalan(SuratJalan $suratJalan)
    {
        $suratJalan->load('details.material', 'creator', 'approver');
        return view('material.surat-jalan-show', compact('suratJalan'));
    }

    /**
     * Tampilkan detail surat jalan untuk modal (tanpa layout)
     */
    public function getModalDetail(SuratJalan $suratJalan)
    {
        $suratJalan->load('details.material', 'creator', 'approver');
        return view('material.surat-jalan-modal-detail', compact('suratJalan'));
    }

    /**
     * Tampilkan form edit surat jalan
     */
    public function editSuratJalan(SuratJalan $suratJalan)
    {
        if ($suratJalan->status != 'BUTUH_PERSETUJUAN') {
            return redirect()->route('surat-jalan.index')
                           ->with('swal_error', 'Surat jalan yang sudah disetujui tidak dapat diedit!');
        }

        $suratJalan->load('details.material');
        $materials = Material::all();
        return view('material.surat-jalan-edit', compact('suratJalan', 'materials'));
    }

    /**
     * Update surat jalan
     */
    public function updateSuratJalan(Request $request, SuratJalan $suratJalan)
    {
        if ($suratJalan->status != 'BUTUH_PERSETUJUAN') {
            return redirect()->route('material.surat-jalan')
                           ->with('error', 'Surat jalan yang sudah disetujui tidak dapat diedit.');
        }

        $validator = Validator::make($request->all(), [
            'nomor_surat' => 'required|string|unique:surat_jalan,nomor_surat,' . $suratJalan->id,
            'jenis_surat_jalan' => 'required|in:Normal,Gangguan,Garansi,Peminjaman',
            'tanggal' => 'required|date',
            'kepada' => 'required|string|max:255',
            'berdasarkan' => 'required|string',
            'security' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'kendaraan' => 'nullable|string|max:255',
            'no_polisi' => 'nullable|string|max:20',
            'pengemudi' => 'nullable|string|max:255',
            'materials' => 'required|array|min:1',
            'materials.*.material_id' => 'required|exists:materials,id',
            'materials.*.quantity' => 'required|integer|min:1',
            'materials.*.satuan' => 'required|string',
            'materials.*.keterangan' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        try {
            DB::beginTransaction();

            $suratJalan->update([
                'nomor_surat' => $request->nomor_surat,
                'jenis_surat_jalan' => $request->jenis_surat_jalan,
                'tanggal' => $request->tanggal,
                'kepada' => $request->kepada,
                'berdasarkan' => $request->berdasarkan,
                'security' => $request->security,
                'keterangan' => $request->keterangan,
                'kendaraan' => $request->kendaraan,
                'no_polisi' => $request->no_polisi,
                'pengemudi' => $request->pengemudi
            ]);

            // Hapus detail lama
            $suratJalan->details()->delete();

            // Tambah detail baru
            foreach ($request->materials as $material) {
                SuratJalanDetail::create([
                    'surat_jalan_id' => $suratJalan->id,
                    'material_id' => $material['material_id'],
                    'quantity' => $material['quantity'],
                    'satuan' => $material['satuan'],
                    'keterangan' => $material['keterangan'] ?? null
                ]);
            }

            DB::commit();

            return redirect()->route('material.surat-jalan')
                           ->with('success', 'Surat jalan berhasil diupdate.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                           ->with('error', 'Gagal mengupdate surat jalan: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Hapus surat jalan
     */
    public function destroySuratJalan(SuratJalan $suratJalan)
    {
        if ($suratJalan->status != 'BUTUH_PERSETUJUAN') {
            return response()->json([
                'success' => false,
                'message' => 'Surat jalan yang sudah disetujui tidak dapat dihapus.'
            ]);
        }

        try {
            $suratJalan->delete();
            return response()->json([
                'success' => true,
                'message' => 'Surat jalan berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus surat jalan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Tampilkan halaman approval surat jalan
     */
    public function approvalSuratJalan()
    {
        return view('material.surat-jalan-approval');
    }

    /**
     * Data untuk DataTables approval surat jalan
     */
    public function getApprovalData(Request $request)
    {
        $status = $request->get('status', 'BUTUH_PERSETUJUAN');
        
        $suratJalans = SuratJalan::with(['creator', 'approver'])
                                 ->byStatus($status)
                                 ->select('surat_jalan.*');

        return datatables($suratJalans)
            ->addIndexColumn()
            ->editColumn('tanggal', function($row) {
                return $row->tanggal->format('d/m/Y');
            })
            ->editColumn('status', function($row) {
                $badge = $row->status == 'APPROVED' ? 'success' : 'warning';
                return '<span class="badge badge-' . $badge . '">' . $row->status . '</span>';
            })
            ->editColumn('created_by', function($row) {
                return $row->creator->nama ?? '-';
            })
            ->editColumn('approved_by', function($row) {
                return $row->approver->nama ?? '-';
            })
            ->editColumn('approved_at', function($row) {
                return $row->approved_at ? $row->approved_at->format('d/m/Y H:i') : '-';
            })
            ->addColumn('action', function($row) {
                if ($row->status == 'BUTUH_PERSETUJUAN') {
                    return '<button class="btn btn-sm btn-success" onclick="approveSuratJalan(' . $row->id . ')"><i class="fa fa-check"></i> Approve</button>';
                } else {
                    return '<button class="btn btn-sm btn-primary" onclick="printSuratJalan(' . $row->id . ')"><i class="fa fa-print"></i> Print</button>';
                }
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    /**
     * Approve surat jalan
     */
    public function approveSuratJalan(SuratJalan $suratJalan)
    {
        if ($suratJalan->status != 'BUTUH_PERSETUJUAN') {
            return response()->json([
                'success' => false,
                'message' => 'Surat jalan sudah disetujui sebelumnya.'
            ]);
        }

        try {
            $suratJalan->update([
                'status' => 'APPROVED',
                'approved_by' => auth()->id(),
                'approved_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Surat jalan berhasil disetujui.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyetujui surat jalan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Export surat jalan ke PDF
     */
    public function exportSuratJalan(SuratJalan $suratJalan)
    {
        if ($suratJalan->status != 'APPROVED') {
            return redirect()->back()
                           ->with('error', 'Hanya surat jalan yang sudah disetujui yang dapat dicetak.');
        }

        $suratJalan->load('details.material', 'creator', 'approver');
        
        // Replace invalid filename characters
        $filename = 'surat-jalan-' . str_replace(['/', '\\'], '-', $suratJalan->nomor_surat) . '.pdf';
        
        $pdf = Pdf::loadView('exports.surat-jalan-pdf', compact('suratJalan'))
                  ->setPaper('a4', 'portrait');
        
        return $pdf->download($filename);
    }
    
    /**
     * Export surat jalan ke Excel (method lama untuk backup)
     */
    public function exportSuratJalanExcel(SuratJalan $suratJalan)
    {
        if ($suratJalan->status != 'APPROVED') {
            return redirect()->back()
                           ->with('error', 'Hanya surat jalan yang sudah disetujui yang dapat dicetak.');
        }

        $suratJalan->load('details.material', 'creator', 'approver');
        
        // Replace invalid filename characters
        $filename = 'surat-jalan-' . str_replace(['/', '\\'], '-', $suratJalan->nomor_surat) . '.xlsx';
        
        return Excel::download(new SuratJalanExport($suratJalan), $filename);
    }
}