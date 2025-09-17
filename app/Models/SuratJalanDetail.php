<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuratJalanDetail extends Model
{
    use HasFactory;

    protected $table = 'surat_jalan_detail';

    protected $fillable = [
        'surat_jalan_id',
        'material_id',
        'quantity',
        'satuan',
        'keterangan'
    ];

    /**
     * Relasi ke surat jalan
     */
    public function suratJalan(): BelongsTo
    {
        return $this->belongsTo(SuratJalan::class);
    }

    /**
     * Relasi ke material
     */
    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }
}