<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasUnitScope;

class MaterialStock extends Model
{
    use HasFactory;
    use HasUnitScope;

    protected $fillable = [
        'material_id',
        'unit_id',
        'unrestricted_use_stock',
        'quality_inspection_stock',
        'blocked_stock',
        'in_transit_stock',
        'project_stock',
        'qty',
        'min_stock',
    ];

    protected $casts = [
        'unrestricted_use_stock' => 'decimal:3',
        'quality_inspection_stock' => 'decimal:3',
        'blocked_stock' => 'decimal:3',
        'in_transit_stock' => 'decimal:3',
        'project_stock' => 'decimal:3',
        'qty' => 'integer',
        'min_stock' => 'integer',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
