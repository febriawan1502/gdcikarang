<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Concerns\HasUnitScope;

/**
 * @mixin IdeHelperSuratJalanDetail
 */
class SuratJalanDetail extends Model
{
    use HasFactory;
    use HasUnitScope;

    protected $table = 'surat_jalan_detail';

    protected $fillable = [
        'unit_id',
        'surat_jalan_id',
        'material_id',
        'quantity',
        'satuan',
        'serial_numbers',
        'keterangan',
        'is_checked',
        'checked_by',
        'checked_at',
        'is_manual',
        'nama_barang_manual',
        'satuan_manual',
        'jumlah_kembali',
        'tanggal_kembali',
    ];

    protected $casts = [
        'serial_numbers' => 'array',
    ];

    public function suratJalan(): BelongsTo
    {
        return $this->belongsTo(SuratJalan::class, 'surat_jalan_id', 'id');
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class, 'material_id', 'id');
    }
    
    public function pengembalianHistories()
{
    return $this->hasMany(\App\Models\PengembalianHistory::class, 'surat_jalan_detail_id');
}


}
