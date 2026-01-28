<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Material;
use App\Models\Concerns\HasUnitScope;
use Illuminate\Support\Facades\DB;

/**
 * @mixin IdeHelperMaterialHistory
 */
class MaterialHistory extends Model
{
    use HasUnitScope;

    protected $fillable = [
    'unit_id',
    'material_id',
     'source_type',     // ✅ TAMBAHKAN
    'source_id',       // ✅ TAMBAHKAN
    'tanggal',
    'tipe',
    'no_slip',
    'masuk',
    'keluar',
    'sisa_persediaan',
    'catatan',
    'keterangan',        // TAMBAH
    'surat_jalan_id',    // TAMBAH
];


    protected $casts = [
        'tanggal' => 'date',
        'masuk' => 'integer',
        'keluar' => 'integer',
        'sisa_persediaan' => 'integer',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function scopeOnlyMaterialBaru($query)
    {
        $mrwiJenis = ['Garansi', 'Perbaikan', 'Rusak', 'Standby'];

        return $query->where(function ($q) use ($mrwiJenis) {
            $q->where('source_type', '!=', 'surat_jalan')
                ->orWhere(function ($sq) use ($mrwiJenis) {
                    $sq->where('source_type', 'surat_jalan')
                        ->whereNotIn('source_id', function ($sub) use ($mrwiJenis) {
                            $sub->select('id')
                                ->from('surat_jalans')
                                ->whereIn('jenis_surat_jalan', $mrwiJenis);
                        });
                });
        });
    }

    /**
     * Relasi polymorphic ke source (material_masuk atau surat_jalan)
     */
    public function source()
    {
        return $this->morphTo('source', 'source_type', 'source_id');
    }

    /**
     * Accessor untuk mendapatkan pekerjaan
     * - Jika material masuk: ambil dari nomor_kr
     * - Jika material keluar: ambil dari keterangan surat jalan
     */
    public function getPekerjaanAttribute()
    {
        if ($this->tipe === 'masuk' && $this->source_type === 'material_masuk') {
            // Ambil dari material_masuk
            $materialMasuk = \App\Models\MaterialMasuk::find($this->source_id);
            return $materialMasuk ? $materialMasuk->nomor_kr : '-';
        }

        if ($this->tipe === 'keluar' && $this->source_type === 'surat_jalan') {
            // Ambil dari surat_jalan
            $suratJalan = \App\Models\SuratJalan::find($this->source_id);
            return $suratJalan ? $suratJalan->keterangan : '-';
        }

        return '-';
    }

    /**
     * =======================================================
     *  UNIVERSAL FUNCTION UNTUK RECORD MATERIAL MASUK/KELUAR
     * =======================================================
     */
    public static function record(
    $material_id,
    $tipe,
    $qty,
    $no_slip = '-',
    $catatan = null,
    $tanggal = null,
    $sourceType = null,
    $sourceId = null
) {
    $material = Material::find($material_id);

    if (!$material) {
        \Log::warning("❌ MaterialHistory::record() gagal — Material ID {$material_id} tidak ditemukan.");
        return null;
    }

    $tipe = strtoupper($tipe);

    if ($tipe === 'MASUK') {
        $material->safeIncrement('unrestricted_use_stock', $qty);
    } elseif ($tipe === 'KELUAR') {
        $material->safeDecrement('unrestricted_use_stock', $qty);
    }

    $existing = self::where('material_id', $material_id)
        ->whereDate('tanggal', $tanggal ?? now())
        ->where('tipe', $tipe)
        ->where('no_slip', $no_slip ?: '-')
        ->first();

    if ($existing) {
        $existing->update([
            'masuk' => $tipe === 'MASUK' ? $qty : 0,
            'keluar' => $tipe === 'KELUAR' ? $qty : 0,
            'sisa_persediaan' => $material->getStockValue('unrestricted_use_stock'),
            'catatan' => $catatan
        ]);
        return $existing;
    }

    return self::create([
        'material_id'     => $material_id,
        'source_type'     => $sourceType,
        'source_id'       => $sourceId,
        'tanggal'         => $tanggal ?? now(),
        'tipe'            => $tipe,
        'no_slip'         => $no_slip ?: '-',
        'masuk'           => $tipe === 'MASUK' ? $qty : 0,
        'keluar'          => $tipe === 'KELUAR' ? $qty : 0,
        'sisa_persediaan' => $material->getStockValue('unrestricted_use_stock'),
        'catatan'         => $catatan,
    ]);
}
    public static function stokBulanan($materialId, $bulan, $tahun)
{
    // 1. Stok awal bulan
    $stokAwal = self::where('material_id', $materialId)
        ->whereDate('tanggal', '<', "{$tahun}-{$bulan}-01")
        ->orderBy('tanggal', 'desc')
        ->value('sisa_persediaan');

    if ($stokAwal === null) {
    $stokAwal = Material::find($materialId)->getStockValue('unrestricted_use_stock') ?? 0;
    }

    // 2. Total masuk bulan ini
    $masuk = self::where('material_id', $materialId)
        ->whereMonth('tanggal', $bulan)
        ->whereYear('tanggal', $tahun)
        ->sum('masuk');

    // 3. Total keluar bulan ini
    $keluar = self::where('material_id', $materialId)
        ->whereMonth('tanggal', $bulan)
        ->whereYear('tanggal', $tahun)
        ->sum('keluar');

    return $stokAwal + $masuk - $keluar;
}


}
