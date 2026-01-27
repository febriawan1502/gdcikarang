<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasUnitScope;

/**
 * @mixin IdeHelperMonitoring
 */
class Monitoring extends Model
{
    use HasFactory;
    use HasUnitScope;

    protected $fillable = [
        'unit',
        'unit_id',
        'target_harian',
        'lm1_1b1_eff',
        'lm2_b1_dal',
        'lm3_b2_sar',
        'jml_realisasi_harian',
        'penerimaan_material',
        'target_persediaan',
        'realisasi_persediaan',
        'saldo_sebelumnya',
        'target_pemakaian',
        'realisasi_pemakaian',
        'target_ito',
        'realisasi_ito',
        'tanggal',
    ];
}
