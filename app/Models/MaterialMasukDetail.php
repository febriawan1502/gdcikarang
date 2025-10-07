<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialMasukDetail extends Model
{
    use HasFactory;

    protected $table = 'material_masuk_detail';

    protected $fillable = [
        'material_masuk_id',
        'material_id',
        'quantity',
        'satuan',
        'normalisasi'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Relasi ke MaterialMasuk
     */
    public function materialMasuk()
    {
        return $this->belongsTo(MaterialMasuk::class, 'material_masuk_id');
    }

    /**
     * Relasi ke Material
     */
    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }
}