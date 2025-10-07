<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Material;
use App\Models\SuratJalan;
use App\Models\SuratJalanDetail;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SuratJalanExport;
use Barryvdh\DomPDF\Facade\Pdf;

class SuratJalanController extends Controller
{
    /**
     * Tampilkan halaman surat jalan
     */
    public function index()
    {
        return view('material.surat-jalan');
    }

    /**
     * Data untuk DataTables surat jalan
     */
    public function getData(Request $request)
    {
        $suratJalans = SuratJalan::with(['creator', 'approver'])
                                 ->select('surat_jalan.*')
                                 ->orderBy('tanggal', 'desc');

        return datatables($suratJalans)
            ->filter(function ($query) use ($request) {
                if ($request->has('search') && $request->search['value'] && strlen($request->search['value']) >= 1) {
                    $searchValue = $request->search['value'];
                    $query->where(function($q) use ($searchValue) {
                        $q->whereRaw('LOWER(nomor_surat) LIKE ?', ['%' . strtolower($searchValue) . '%'])
                          ->orWhereRaw('LOWER(kepada) LIKE ?', ['%' . strtolower($searchValue) . '%'])
                          ->orWhereRaw('LOWER(berdasarkan) LIKE ?', ['%' . strtolower($searchValue) . '%'])
                          ->orWhereRaw('LOWER(keterangan) LIKE ?', ['%' . strtolower($searchValue) . '%']);
                    });
                }
            })
            ->addIndexColumn()
            ->editColumn('tanggal', function($row) {
                return $row->tanggal->format('d/m/Y');
            })
            ->editColumn('berdasarkan', function($row) {
                return $row->berdasarkan ?? '-';
            })
            ->editColumn('keterangan', function($row) {
                return $row->keterangan ?? '-';
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
                } elseif ($row->status == 'APPROVED') {
                    $actions .= '<button class="btn btn-sm btn-success" onclick="printSuratJalan(' . $row->id . ')"><i class="fa fa-print"></i></button>';
                }
		$actions .= '<button class="btn btn-sm btn-danger" onclick="deleteSuratJalan(' . $row->id . ')"><i class="fa fa-trash"></i></button>';
                return $actions;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    /**
     * Tampilkan form create surat jalan
     */
    public function create()
    {
        $nomorSurat = SuratJalan::generateNomorSurat('Normal');
        $materials = Material::all();
        return view('material.surat-jalan-create', compact('nomorSurat', 'materials'));
    }
    
    /**
     * Generate nomor surat untuk AJAX call
     */
    public function generateNomor(Request $request)
    {
        $jenisSuratJalan = $request->get('jenis', 'Normal');
        $nomorSurat = SuratJalan::generateNomorSurat($jenisSuratJalan);
        
        return response()->json([
            'nomor_surat' => $nomorSurat
        ]);
    }

    /**
     * Simpan surat jalan baru
     */
    public function store(Request $request)
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
            }

            DB::commit();
            Log::info('Transaction committed successfully');

            Log::info('Redirecting to surat jalan index with success message');
            return redirect()->route('surat-jalan.index')
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
    public function show(SuratJalan $suratJalan)
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
    public function edit(SuratJalan $suratJalan)
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
    public function update(Request $request, SuratJalan $suratJalan)
    {
        // Log awal untuk debugging
        Log::info('=== SURAT JALAN UPDATE STARTED ===');
        Log::info('Surat Jalan ID: ' . $suratJalan->id);
        Log::info('Current Status: ' . $suratJalan->status);
        Log::info('Request data:', $request->all());
        Log::info('User ID: ' . auth()->id());

        if ($suratJalan->status != 'BUTUH_PERSETUJUAN') {
            Log::warning('Update failed: Surat jalan status is not BUTUH_PERSETUJUAN', [
                'status' => $suratJalan->status,
                'surat_jalan_id' => $suratJalan->id
            ]);
            return redirect()->route('surat-jalan.index')
                           ->with('swal_error', 'Surat jalan yang sudah disetujui tidak dapat diedit!');
        }

        Log::info('Status check passed, proceeding with validation');

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
            Log::error('Validation failed for surat jalan update:', $validator->errors()->toArray());
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        Log::info('Validation passed, starting database transaction');

        try {
            DB::beginTransaction();

            Log::info('Updating surat jalan main data');
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
            Log::info('Surat jalan main data updated successfully');

            // Hapus detail lama
            Log::info('Deleting old surat jalan details');
            $deletedCount = $suratJalan->details()->count();
            $suratJalan->details()->delete();
            Log::info('Deleted ' . $deletedCount . ' old details');

            // Tambah detail baru
            Log::info('Creating new surat jalan details', ['materials_count' => count($request->materials)]);
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
            }

            DB::commit();
            Log::info('Database transaction committed successfully');

            Log::info('Redirecting to surat-jalan.index with success message');
            return redirect()->route('surat-jalan.index')
                           ->with('swal_success', 'Surat jalan berhasil diupdate!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Exception occurred during surat jalan update:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                           ->with('swal_error', 'Gagal mengupdate surat jalan: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Hapus surat jalan
     */
    public function destroy(SuratJalan $suratJalan)
    {
        

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
    public function approval()
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
    public function approve(SuratJalan $suratJalan)
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

            // Update material stock setelah approval - mengurangi qty dan unrestricted_use_stock
            Log::info('Updating material stock after approval for Surat Jalan ID: ' . $suratJalan->id);
            foreach ($suratJalan->details as $detail) {
                $materialModel = Material::find($detail->material_id);
                if ($materialModel) {
                    $materialModel->qty = $materialModel->qty - $detail->quantity;
                    $materialModel->unrestricted_use_stock = $materialModel->unrestricted_use_stock - $detail->quantity;
                    $materialModel->save();
                    Log::info("Material stock updated after approval for ID: {$detail->material_id}", [
                        'quantity_reduced' => $detail->quantity,
                        'new_qty' => $materialModel->qty,
                        'new_unrestricted_stock' => $materialModel->unrestricted_use_stock
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Surat jalan berhasil disetujui dan stock material telah diperbarui.'
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
    public function export(SuratJalan $suratJalan)
    {
        if ($suratJalan->status != 'APPROVED') {
            return redirect()->back()
                           ->with('error', 'Hanya surat jalan yang sudah disetujui yang dapat dicetak.');
        }

        $suratJalan->load('details.material', 'creator', 'approver');
        
        // Calculate number of pages needed
        $totalPages = $this->calculatePagesNeeded($suratJalan);
        
        // Replace invalid filename characters
        $filename = 'surat-jalan-' . str_replace(['/', '\\'], '-', $suratJalan->nomor_surat) . '.pdf';
        
        $pdf = Pdf::loadView('exports.surat-jalan-pdf', compact('suratJalan', 'totalPages'))
                  ->setPaper('a4', 'portrait');
        
        return $pdf->download($filename);
        
    }

    /**
     * Calculate the number of pages needed for the PDF
     */
    private function calculatePagesNeeded(SuratJalan $suratJalan)
    {
        // Base content takes approximately 1 page
        $baseContentHeight = 1;
        
        // Each material row takes approximately 25px
        // A4 page has approximately 700px usable height after margins
        // Header, info tables, and signature take about 400px
        // So we have about 300px for material table per page
        $materialsPerPage = 24; // Approximately 20 rows per page
        
        $totalMaterials = $suratJalan->details->count();
        
        if ($totalMaterials <= $materialsPerPage) {
            return 1; // All fits in one page
        }
        
        // Calculate additional pages needed for materials
        $additionalPages = ceil(($totalMaterials - $materialsPerPage) / 15); // 15 rows per additional page
        
        return $baseContentHeight + $additionalPages;
    }
    
    /**
     * Export surat jalan ke Excel (method lama untuk backup)
     */
    public function exportExcel(SuratJalan $suratJalan)
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