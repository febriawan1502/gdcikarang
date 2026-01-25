<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasUnitScope;

class MaterialMasukDetail extends Model
{
    use HasFactory;
    use HasUnitScope;

    protected $table = 'material_masuk_detail';

    protected $fillable = [
        'unit_id',
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
