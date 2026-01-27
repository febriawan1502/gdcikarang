<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\MaterialMrwiSerialMove;
use Illuminate\Http\Request;

class MaterialMrwiHistoryController extends Controller
{
    public function index(Request $request)
    {
        $serial = trim((string) $request->get('serial', ''));
        $history = collect();
        $latest = null;
        $material = null;
        $notFound = false;

        if ($serial !== '') {
            $history = MaterialMrwiSerialMove::where('serial_number', $serial)
                ->orderBy('id')
                ->get();

            if ($history->isEmpty()) {
                $notFound = true;
            } else {
                $latest = $history->last();
                $material = Material::find($latest->material_id);
            }
        }

        return view('material.mrwi-history', compact('serial', 'history', 'latest', 'material', 'notFound'));
    }
}
