<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialMrwiStock extends Model
{
    use HasFactory;

    protected $table = 'material_mrwi_stocks';

    protected $fillable = [
        'material_id',
        'unit_id',
        'standby_stock',
        'garansi_stock',
        'perbaikan_stock',
        'rusak_stock',
    ];

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class, 'material_id');
    }
}
