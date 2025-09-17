<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard
     */
    public function index()
    {
        $stats = [
            'total_materials' => Material::count(),
            'total_stock' => Material::sum('qty'),
            'total_users' => User::count(),
            'pending_materials' => Material::where('status', Material::STATUS_PENDING)->count(),
        ];

        return view('dashboard.index', compact('stats'));
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
                    
                    // View Detail
                    $actions .= '<button type="button" class="btn btn-info btn-sm" onclick="viewDetail(' . $row->id . ')" title="Lihat Detail">';
                    $actions .= '<i class="fa fa-eye"></i>';
                    $actions .= '</button>';
                    
                    // Edit
                    $actions .= '<button type="button" class="btn btn-warning btn-sm" onclick="editMaterial(' . $row->id . ')" title="Edit">';
                    $actions .= '<i class="fa fa-edit"></i>';
                    $actions .= '</button>';
                    
                    // Delete
                    $actions .= '<button type="button" class="btn btn-danger btn-sm" onclick="deleteMaterial(' . $row->id . ')" title="Hapus">';
                    $actions .= '<i class="fas fa-trash"></i>';
                    $actions .= '</button>';
                    
                    $actions .= '</div>';
                    
                    return $actions;
                })
                ->editColumn('tanggal_terima', function ($row) {
                    return $row->tanggal_terima ? $row->tanggal_terima->format('d F Y') : '-';
                })
                ->editColumn('status', function ($row) {
                    $badgeClass = match($row->status) {
                        Material::STATUS_SELESAI => 'success',
                        Material::STATUS_PENDING => 'warning',
                        Material::STATUS_PROSES => 'info',
                        default => 'secondary'
                    };
                    
                    return '<span class="badge bg-' . $badgeClass . '">' . $row->status . '</span>';
                })
                ->editColumn('harga_satuan', function ($row) {
                    return 'Rp ' . number_format($row->harga_satuan, 0, ',', '.');
                })
                ->editColumn('total_harga', function ($row) {
                    return 'Rp ' . number_format($row->total_harga, 0, ',', '.');
                })
                ->rawColumns(['action', 'status'])
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
}