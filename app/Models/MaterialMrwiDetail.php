<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialMrwiDetail extends Model
{
    use HasFactory;

    protected $table = 'material_mrwi_details';

    protected $fillable = [
        'material_mrwi_id',
        'material_id',
        'no_material',
        'nama_material',
        'qty',
        'satuan',
        'serial_number',
        'attb_limbah',
        'status_anggaran',
        'no_asset',
        'nama_pabrikan',
        'tahun_buat',
        'id_pelanggan',
        'klasifikasi',
        'no_polis',
        'catatan',
    ];

    public function mrwi(): BelongsTo
    {
        return $this->belongsTo(MaterialMrwi::class, 'material_mrwi_id');
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class, 'material_id');
    }
}
