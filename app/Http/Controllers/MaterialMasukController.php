<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Material;
use App\Models\MaterialMasuk;
use App\Models\MaterialMasukDetail;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class MaterialMasukController extends Controller
{
    /**
     * Tampilkan daftar material masuk
     */
    public function index()
    {
        return view('material.material-masuk-index');
    }

    /**
     * Data untuk DataTables material masuk
     */
    public function getData(Request $request)
    {
        $materialMasuk = MaterialMasuk::with(['details.material', 'creator'])
                                    ->select('material_masuk.*');

        return DataTables::of($materialMasuk)
            ->addIndexColumn()
            ->addColumn('material_info', function ($row) {
                $materials = $row->details->map(function($detail) {
                    $materialDesc = $detail->material->material_description ?? 'Material tidak diketahui';
                    return $materialDesc . ' (' . $detail->quantity . ' ' . $detail->satuan . ')';
                })->take(2)->implode('<br>');
                
                if ($row->details->count() > 2) {
                    $materials .= '<br><small class="text-muted">+' . ($row->details->count() - 2) . ' material lainnya</small>';
                }
                
                return $materials ?: '-';
            })
            ->addColumn('total_quantity', function ($row) {
                return $row->details->sum('quantity');
            })
            ->addColumn('creator_name', function ($row) {
                return $row->creator->nama ?? '-';
            })
            ->addColumn('tanggal_masuk_formatted', function ($row) {
                return Carbon::parse($row->tanggal_masuk)->format('d/m/Y');
            })
            ->addColumn('action', function ($row) {
                $editUrl = route('material-masuk.edit', $row->id);
                $deleteUrl = route('material-masuk.destroy', $row->id);
                
                return '
                    <div class="btn-group" role="group">
                        <a href="' . $editUrl . '" class="btn btn-sm btn-warning" title="Edit">
                            <i class="fa fa-edit"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-danger" 
                                onclick="deleteItem(' . $row->id . ', \'' . $deleteUrl . '\')" 
                                title="Hapus">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                ';
            })
            ->rawColumns(['material_info', 'action'])
            ->make(true);
    }

    /**
     * Tampilkan form tambah material masuk
     */
    public function create()
    {
        return view('material.material-masuk-create');
    }

    /**
     * Simpan material masuk baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nomor_kr' => 'nullable|string|max:255',
            'pabrikan' => 'nullable|string|max:255',
            'tanggal_masuk' => 'required|date',
            'keterangan' => 'nullable|string',
            'materials' => 'required|array|min:1',
            'materials.*.material_id' => 'required|exists:materials,id',
            'materials.*.quantity' => 'required|integer|min:1',
            'materials.*.satuan' => 'required|string|max:50'
        ]);

        DB::beginTransaction();
        try {
            // Buat record material masuk
            $materialMasuk = MaterialMasuk::create([
                'nomor_kr' => $request->nomor_kr,
                'pabrikan' => $request->pabrikan,
                'tanggal_masuk' => $request->tanggal_masuk,
                'keterangan' => $request->keterangan,
                'created_by' => auth()->id()
            ]);

            // Simpan detail material dan update stok
            foreach ($request->materials as $materialData) {
                if (empty($materialData['material_id']) || empty($materialData['quantity'])) {
                    continue;
                }

                // Simpan detail
                MaterialMasukDetail::create([
                    'material_masuk_id' => $materialMasuk->id,
                    'material_id' => $materialData['material_id'],
                    'quantity' => $materialData['quantity'],
                    'satuan' => $materialData['satuan'],
                    'keterangan' => $materialData['normalisasi'] ?? null
                ]);

                // Update stok material
                $material = Material::findOrFail($materialData['material_id']);
                $material->increment('unrestricted_use_stock', $materialData['quantity']);


            }

            DB::commit();
            return redirect()->route('material-masuk.index')
                           ->with('success', 'Material masuk berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                           ->with('error', 'Gagal menambahkan material masuk: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Tampilkan form edit material masuk
     */
    public function edit($id)
    {
        $materialMasuk = MaterialMasuk::with('details.material')->findOrFail($id);
        $materials = Material::whereNotNull('material_description')->select('id', 'material_description')->get();
        return view('material.material-masuk-edit', compact('materialMasuk', 'materials'));
    }

    /**
     * Update material masuk
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nomor_kr' => 'nullable|string|max:255',
            'pabrikan' => 'nullable|string|max:255',
            'tanggal_masuk' => 'required|date',
            'keterangan' => 'nullable|string',
            'materials' => 'required|array|min:1',
            'materials.*.material_id' => 'required|exists:materials,id',
            'materials.*.quantity' => 'required|integer|min:1',
            'materials.*.satuan' => 'required|string|max:50'
        ]);

        DB::beginTransaction();
        try {
            $materialMasuk = MaterialMasuk::with('details')->findOrFail($id);
            
            // Update material masuk header
            $materialMasuk->update([
                'nomor_kr' => $request->nomor_kr,
                'pabrikan' => $request->pabrikan,
                'tanggal_masuk' => $request->tanggal_masuk,
                'keterangan' => $request->keterangan
            ]);

            // Process materials
            $existingDetailIds = [];
            foreach ($request->materials as $materialData) {
                if (empty($materialData['material_id']) || empty($materialData['quantity'])) {
                    continue;
                }

                if (isset($materialData['detail_id']) && !empty($materialData['detail_id'])) {
                    // Update existing detail
                    $detail = MaterialMasukDetail::find($materialData['detail_id']);
                    if ($detail) {
                        $oldQuantity = $detail->quantity;
                        $newQuantity = $materialData['quantity'];
                        $quantityDiff = $newQuantity - $oldQuantity;
                        
                        // Update detail
                        $detail->update([
                            'quantity' => $newQuantity,
                            'satuan' => $materialData['satuan'],
                            'keterangan' => $materialData['keterangan'] ?? null
                        ]);
                        
                        // Adjust stock if quantity changed
                        if ($quantityDiff != 0) {
                            $material = Material::find($detail->material_id);
                            if ($quantityDiff > 0) {
                                $material->increment('qty', $quantityDiff);
                                $material->increment('unrestricted_use_stock', $quantityDiff);
                            } else {
                                $material->decrement('qty', abs($quantityDiff));
                                $material->decrement('unrestricted_use_stock', abs($quantityDiff));
                            }
                        }
                        
                        $existingDetailIds[] = $detail->id;
                    }
                } else {
                    // Create new detail
                    $newDetail = MaterialMasukDetail::create([
                        'material_masuk_id' => $materialMasuk->id,
                        'material_id' => $materialData['material_id'],
                        'quantity' => $materialData['quantity'],
                        'satuan' => $materialData['satuan'],
                        'keterangan' => $materialData['keterangan'] ?? null
                    ]);

                    // Update stock
                    $material = Material::find($materialData['material_id']);
                    $material->increment('qty', $materialData['quantity']);
                    $material->increment('unrestricted_use_stock', $materialData['quantity']);
                    
                    $existingDetailIds[] = $newDetail->id;
                }
            }
            
            // Delete removed details and revert their stock
            $removedDetails = $materialMasuk->details()->whereNotIn('id', $existingDetailIds)->get();
            foreach ($removedDetails as $detail) {
                $material = Material::find($detail->material_id);
                $material->decrement('qty', $detail->quantity);
                $material->decrement('unrestricted_use_stock', $detail->quantity);
                $detail->delete();
            }

            DB::commit();

            return redirect()->route('material.material-masuk.index')
                           ->with('success', 'Material masuk berhasil diupdate.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                           ->with('error', 'Gagal mengupdate material masuk: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Hapus material masuk
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $materialMasuk = MaterialMasuk::findOrFail($id);
            
            // Kurangi stock material
            $material = Material::find($materialMasuk->material_id);
            $material->decrement('qty', $materialMasuk->quantity);

            // Hapus record material masuk
            $materialMasuk->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Material masuk berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus material masuk: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Autocomplete untuk material
     */
    public function autocompleteMaterial(Request $request)
    {
        $query = $request->get('q');
        
        $materials = Material::where('material_description', 'LIKE', "%{$query}%")
                            ->orWhere('material_code', 'LIKE', "%{$query}%")
                            ->whereNotNull('material_description')
                            ->select('id', 'material_description', 'material_code', 'base_unit_of_measure')
                            ->limit(10)
                            ->get();
        
        return response()->json($materials->map(function($material) {
            return [
                'id' => $material->id,
                'text' => $material->material_description ?? 'Material tidak diketahui',
                'material_code' => $material->material_code,
                'normalisasi' => $material->material_code, // mapping material_code sebagai normalisasi
                'satuan' => $material->base_unit_of_measure
            ];
        }));
    }

    /**
     * Autocomplete untuk normalisasi
     */
    public function autocompleteNormalisasi(Request $request)
    {
        $query = $request->get('q');
        
        $materials = Material::where('material_code', 'LIKE', "%{$query}%")
                            ->whereNotNull('material_description')
                            ->select('id', 'material_description', 'material_code', 'base_unit_of_measure')
                            ->limit(10)
                            ->get();
        
        return response()->json($materials->map(function($material) {
            return [
                'id' => $material->id,
                'text' => $material->material_code,
                'material_description' => $material->material_description ?? 'Material tidak diketahui',
                'normalisasi' => $material->material_code,
                'satuan' => $material->base_unit_of_measure
            ];
        }));
    }
}