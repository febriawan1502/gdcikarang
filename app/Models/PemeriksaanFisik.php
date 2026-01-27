<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasUnitScope;

/**
 * @mixin IdeHelperPemeriksaanFisik
 */
class PemeriksaanFisik extends Model
{
    use HasUnitScope;

    protected $table = 'pemeriksaan_fisik';

    protected $fillable = [
        'unit_id',
        'material_id',
        'bulan',
        'sap',
        'fisik',
        'sn_mims',
        'selisih_sf',
        'selisih_ss',
        'selisih_fs',
        'justifikasi_sf',
        'justifikasi_ss',
        'justifikasi_fs',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }
}
