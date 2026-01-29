<?php

namespace App\Http\Controllers;

use App\Models\MaterialMrwiDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class MaterialMrwiItemController extends Controller
{
    public function index()
    {
        return view('material.mrwi-items');
    }

    public function data(Request $request)
    {
        $user = Auth::user();
        $unitId = null;
        if ($user && $user->unit_id && (!$user->unit || !$user->unit->is_induk)) {
            $unitId = $user->unit_id;
        }

        $query = MaterialMrwiDetail::query()
            ->select('material_mrwi_details.*')
            ->join('material_mrwi as mm', 'mm.id', '=', 'material_mrwi_details.material_mrwi_id')
            ->when($unitId, function ($q) use ($unitId) {
                $q->where('mm.unit_id', $unitId);
            });

        return DataTables::of($query)
            ->filter(function ($query) use ($request) {
                if ($request->has('search') && $request->search['value']) {
                    $searchValue = strtolower($request->search['value']);
                    $query->where(function ($q) use ($searchValue) {
                        $like = "%{$searchValue}%";
                        $q->whereRaw('LOWER(material_mrwi_details.serial_number) LIKE ?', [$like])
                            ->orWhereRaw('LOWER(material_mrwi_details.no_material) LIKE ?', [$like])
                            ->orWhereRaw('LOWER(material_mrwi_details.nama_material) LIKE ?', [$like])
                            ->orWhereRaw('LOWER(material_mrwi_details.nama_pabrikan) LIKE ?', [$like])
                            ->orWhereRaw('LOWER(material_mrwi_details.id_pelanggan) LIKE ?', [$like]);
                    });
                }
            })
            ->addIndexColumn()
            ->addColumn('status_inspeksi', function ($row) {
                return $this->resolveStatusLabel((int) ($row->klasifikasi ?? 0));
            })
            ->editColumn('serial_number', function ($row) {
                return $row->serial_number ?? '-';
            })
            ->editColumn('no_material', function ($row) {
                return $row->no_material ?? '-';
            })
            ->editColumn('nama_material', function ($row) {
                return $row->nama_material ?? '-';
            })
            ->editColumn('nama_pabrikan', function ($row) {
                return $row->nama_pabrikan ?? '-';
            })
            ->editColumn('tahun_buat', function ($row) {
                return $row->tahun_buat ?? '-';
            })
            ->editColumn('id_pelanggan', function ($row) {
                return $row->id_pelanggan ?? '-';
            })
            ->make(true);
    }

    private function resolveStatusLabel(int $klasifikasi): string
    {
        if ($klasifikasi === 1) {
            return 'Standby';
        }
        if ($klasifikasi === 2) {
            return 'Garansi';
        }
        if ($klasifikasi === 3) {
            return 'Perbaikan';
        }
        if (in_array($klasifikasi, [4, 5, 6], true)) {
            return 'Rusak';
        }

        return '-';
    }
}
