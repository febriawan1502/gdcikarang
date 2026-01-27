<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasUnitScope;

/**
 * @mixin IdeHelperPengembalianHistory
 */
class PengembalianHistory extends Model
{
    use HasFactory;
    use HasUnitScope;

    protected $fillable = [
        'unit_id',
        'surat_jalan_detail_id',
        'nomor_surat_masuk',
        'tanggal_masuk',
        'jumlah_kembali',
        'keterangan',
    ];

    public function detail()
    {
        return $this->belongsTo(SuratJalanDetail::class, 'surat_jalan_detail_id');
    }
}
