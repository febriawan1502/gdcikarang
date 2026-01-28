<?php

namespace App\Exports;

use App\Models\MaterialMrwiDetail;
use App\Models\MaterialMrwiSerialMove;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MaterialMrwiStockDetailExport implements FromCollection, WithHeadings, WithMapping
{
    protected $category;

    public function __construct($category)
    {
        $this->category = $category;
    }

    public function collection()
    {
        $category = $this->category;

        // Map category to bucket names
        $buckets = match ($category) {
            'garansi' => ['garansi'],
            'perbaikan' => ['perbaikan'],
            'rusak' => ['rusak'],
            'standby' => ['standby'],
            default => ['standby'],
        };

        $user = Auth::user();
        $unitId = null;
        if ($user && $user->unit_id && (!$user->unit || !$user->unit->is_induk)) {
            $unitId = $user->unit_id;
        }

        // Subquery for latest moves
        $latestMoves = MaterialMrwiSerialMove::selectRaw('MAX(id) as id')
            ->when($unitId, function ($query) use ($unitId) {
                $query->where('unit_id', $unitId);
            })
            ->groupBy('serial_number');

        // Fetch serials that are currently in the target buckets
        $serials = MaterialMrwiSerialMove::whereIn('id', $latestMoves)
            ->whereIn('status_bucket', $buckets)
            ->whereIn('jenis', ['masuk', 'kembali'])
            ->pluck('serial_number');

        // Fetch details from MaterialMrwiDetail for these serials
        return MaterialMrwiDetail::with('material')
            ->whereIn('serial_number', $serials)
            ->get();
    }

    public function headings(): array
    {
        return [
            'No Material',
            'Nama Material',
            'Qty',
            'Satuan',
            'Serial Number (PLN)',
            'No Seri Material (Pabrikan)',
            'Merk',
            'Tahun',
            'Klasifikasi',
            'Status Anggaran',
            'No Asset',
        ];
    }

    public function map($detail): array
    {
        return [
            $detail->no_material,
            $detail->nama_material,
            $detail->qty,
            $detail->satuan,
            $detail->serial_number,
            $detail->id_pelanggan,
            $detail->nama_pabrikan,
            $detail->tahun_buat,
            $this->mapKlasifikasi($detail->klasifikasi),
            $detail->status_anggaran,
            $detail->no_asset,
        ];
    }

    private function mapKlasifikasi($klasifikasi)
    {
        return match ((int)$klasifikasi) {
            1 => 'Standby',
            2 => 'Garansi',
            3 => 'Perbaikan',
            4, 5, 6 => 'Rusak',
            default => 'Unknown',
        };
    }
}
