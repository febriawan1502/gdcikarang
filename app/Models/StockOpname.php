<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Concerns\HasUnitScope;

class StockOpname extends Model
{
    use HasFactory;
    use HasUnitScope;

    protected $fillable = [
        'unit_id',
        'material_id',
        'material_description',
        'stock_fisik',
        'stock_system',
        'selisih',
        'keterangan',
        'created_by'
    ];

    protected $casts = [
        'stock_fisik' => 'decimal:2',
        'stock_system' => 'decimal:2',
        'selisih' => 'decimal:2',
    ];

    /**
     * Get the material that owns the stock opname.
     */
    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    /**
     * Get the user who created the stock opname.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
