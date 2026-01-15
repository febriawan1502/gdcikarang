<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PemeriksaanFisik extends Model
{
    protected $table = 'pemeriksaan_fisik';

    protected $fillable = [
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
