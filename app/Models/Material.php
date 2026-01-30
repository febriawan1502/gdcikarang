<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MaterialStock;

/**
 * @mixin IdeHelperMaterial
 */
class Material extends Model
{
    use HasFactory;

    protected $table = 'materials';

    protected $fillable = [
        'nomor',
        'company_code',
        'company_code_description',
        'plant',
        'plant_description',
        'storage_location',
        'storage_location_description',
        'material_type',
        'material_type_description',
        'material_code',
        'material_description',
        'material_group',
        'base_unit_of_measure',
        'valuation_type',
        'valuation_class',
        'valuation_description',
        'harga_satuan',
        'currency',
        'rak',
        'keterangan',
        'created_by',
        'updated_by',
    ];

    protected $attributes = [
        'status' => self::STATUS_BAIK,
    ];

    protected $casts = [
        'tanggal_terima' => 'date',
        'harga_satuan' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    const STATUS_BAIK = 'BAIK';
    const STATUS_RUSAK = 'RUSAK';
    const STATUS_DALAM_PERBAIKAN = 'DALAM PERBAIKAN';

    public static function getStatuses()
    {
        return [
            self::STATUS_BAIK => 'Baik',
            self::STATUS_RUSAK => 'Rusak',
            self::STATUS_DALAM_PERBAIKAN => 'Dalam Perbaikan',
        ];
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function histories()
    {
        return $this->hasMany(MaterialHistory::class, 'material_id');
    }

    public function stocks()
    {
        return $this->hasMany(MaterialStock::class, 'material_id');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('nomor_kr', 'LIKE', "%{$search}%")
                ->orWhere('pabrikan', 'LIKE', "%{$search}%")
                ->orWhere('material_description', 'LIKE', "%{$search}%")
                ->orWhere('material_code', 'LIKE', "%{$search}%")
                ->orWhere('keterangan', 'LIKE', "%{$search}%");
        });
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPabrikan($query, $pabrikan)
    {
        return $query->where('pabrikan', 'LIKE', "%{$pabrikan}%");
    }

    public function scopeLowStock($query, $threshold = 10)
    {
        return $query->whereHas('stocks', function ($q) use ($threshold) {
            $q->where('qty', '<=', $threshold);
        });
    }

    public function getFormattedHargaSatuanAttribute()
    {
        return 'Rp ' . number_format($this->harga_satuan, 0, ',', '.');
    }

    public function getFormattedTotalHargaAttribute()
    {
        return 'Rp ' . number_format($this->total_harga, 0, ',', '.');
    }

    public function getFormattedTanggalTerimaAttribute()
    {
        return $this->tanggal_terima ? $this->tanggal_terima->format('d F Y') : '-';
    }

    public function getStatusBadgeColorAttribute()
    {
        switch ($this->status) {
            case self::STATUS_BAIK:
                return 'success';
            case self::STATUS_RUSAK:
                return 'danger';
            case self::STATUS_DALAM_PERBAIKAN:
                return 'warning';
            default:
                return 'secondary';
        }
    }

    public function getTotalStockAttribute()
    {
        return $this->getStockValue('unrestricted_use_stock')
            + $this->getStockValue('quality_inspection_stock')
            + $this->getStockValue('blocked_stock')
            + $this->getStockValue('in_transit_stock')
            + $this->getStockValue('project_stock');
    }

    public function getTotalHargaAttribute()
    {
        $stok = $this->getStockValue('unrestricted_use_stock');
        $harga = $this->harga_satuan ?? 0;

        return $stok * $harga;
    }

    public static function generateNomor()
    {
        try {
            $lastMaterial = self::orderBy('nomor', 'desc')->first();
            $lastNumber = $lastMaterial ? (int) $lastMaterial->nomor : 0;
            return $lastNumber + 1;
        } catch (\Exception $e) {
            return time();
        }
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (auth()->check()) {
                $model->created_by = auth()->id();
                $model->updated_by = auth()->id();
            }
            if (empty($model->status)) {
                $model->status = self::STATUS_BAIK;
            }
        });

        static::updating(function ($model) {
            if (auth()->check()) {
                $model->updated_by = auth()->id();
            }
        });
    }

    public function canBeDeleted()
    {
        return true;
    }

    public function updateStock($newQty, $reason = null)
    {
        $this->setStockValue('qty', (int) $newQty);
        $this->setStockValue('unrestricted_use_stock', (float) $newQty);
        return $this;
    }

    // === START FIX: Anti Stok Negatif ===
    public function safeIncrement($column, $amount)
    {
        if ($this->isStockColumn($column)) {
            $this->adjustStock($column, $amount);
            if ($column === 'unrestricted_use_stock') {
                $this->adjustStock('qty', $amount);
            }
            return;
        }

        $this->$column = ($this->$column ?? 0) + $amount;
        $this->save();
    }

    public function safeDecrement($column, $amount)
    {
        if ($this->isStockColumn($column)) {
            $current = $this->getStockValue($column);

            if ($current < $amount) {
                throw new \Exception("Stok material '{$this->material_description}' tidak mencukupi! (tersedia: {$current}, dibutuhkan: {$amount})");
            }

            $this->adjustStock($column, -$amount);
            if ($column === 'unrestricted_use_stock') {
                $this->adjustStock('qty', -$amount);
            }
            return;
        }

        $current = $this->$column ?? 0;

        if ($current < $amount) {
            throw new \Exception("Stok material '{$this->material_description}' tidak mencukupi! (tersedia: {$current}, dibutuhkan: {$amount})");
        }

        $this->$column = $current - $amount;
        $this->save();
    }
    // === END FIX ===

    public function getRakAttribute($value)
    {
        // 1. Try resolving unit from the logged-in user (context-aware)
        $unitId = $this->resolveUnitId();

        // 2. If user has context, return that unit's rack
        if ($unitId) {
            return MaterialStock::withoutGlobalScopes()
                ->where('material_id', $this->id)
                ->where('unit_id', $unitId)
                ->value('rak') ?? $value;
        }

        // 3. Fallback: If user is Admin/Induk (no context), show the rack for the material's owning Unit
        // Try finding unit by Storage Location first (more specific)
        $owningUnit = \App\Models\Unit::where('storage_location', $this->storage_location)->first();
        
        // If not found, try finding unit by Plant
        if (!$owningUnit) {
            $owningUnit = \App\Models\Unit::where('plant', $this->plant)->first();
        }

        if ($owningUnit) {
            return MaterialStock::withoutGlobalScopes()
                ->where('material_id', $this->id)
                ->where('unit_id', $owningUnit->id)
                ->value('rak') ?? $value;
        }

        return $value;
    }

    public function getUnrestrictedUseStockAttribute($value)
    {
        return $this->getStockValue('unrestricted_use_stock');
    }

    public function getQualityInspectionStockAttribute($value)
    {
        return $this->getStockValue('quality_inspection_stock');
    }

    public function getBlockedStockAttribute($value)
    {
        return $this->getStockValue('blocked_stock');
    }

    public function getInTransitStockAttribute($value)
    {
        return $this->getStockValue('in_transit_stock');
    }

    public function getProjectStockAttribute($value)
    {
        return $this->getStockValue('project_stock');
    }

    public function getQtyAttribute($value)
    {
        return (int) $this->getStockValue('qty');
    }

    public function getMinStockAttribute($value)
    {
        return (int) $this->getStockValue('min_stock');
    }

    private function isStockColumn(string $column): bool
    {
        return in_array($column, [
            'unrestricted_use_stock',
            'quality_inspection_stock',
            'blocked_stock',
            'in_transit_stock',
            'project_stock',
            'qty',
            'min_stock',
        ], true);
    }

    private function resolveUnitId(?int $unitId = null): ?int
    {
        if ($unitId !== null) {
            return $unitId;
        }

        $user = auth()->user();
        if (!$user || !$user->unit_id) {
            return null;
        }

        if ($user->unit && $user->unit->is_induk) {
            return null;
        }

        return $user->unit_id;
    }

    public function getStockValue(string $column, ?int $unitId = null): float
    {
        $unitId = $this->resolveUnitId($unitId);

        if ($unitId) {
            return (float) MaterialStock::withoutGlobalScopes()
                ->where('material_id', $this->id)
                ->where('unit_id', $unitId)
                ->value($column) ?? 0;
        }

        return (float) MaterialStock::withoutGlobalScopes()
            ->where('material_id', $this->id)
            ->sum($column);
    }

    public function setStockValue(string $column, $value, ?int $unitId = null): void
    {
        $unitId = $this->resolveUnitId($unitId);
        if (!$unitId) {
            return;
        }

        $stock = MaterialStock::withoutGlobalScopes()->firstOrCreate(
            [
                'material_id' => $this->id,
                'unit_id' => $unitId,
            ],
            [
                'unrestricted_use_stock' => 0,
                'quality_inspection_stock' => 0,
                'blocked_stock' => 0,
                'in_transit_stock' => 0,
                'project_stock' => 0,
                'qty' => 0,
                'min_stock' => 0,
            ]
        );

        $stock->{$column} = $value;
        $stock->save();
    }

    private function adjustStock(string $column, $amount, ?int $unitId = null): void
    {
        $unitId = $this->resolveUnitId($unitId);
        if (!$unitId) {
            return;
        }

        $stock = MaterialStock::withoutGlobalScopes()->firstOrCreate(
            [
                'material_id' => $this->id,
                'unit_id' => $unitId,
            ],
            [
                'unrestricted_use_stock' => 0,
                'quality_inspection_stock' => 0,
                'blocked_stock' => 0,
                'in_transit_stock' => 0,
                'project_stock' => 0,
                'qty' => 0,
                'min_stock' => 0,
            ]
        );

        $current = $stock->{$column} ?? 0;
        $stock->{$column} = $current + $amount;
        $stock->save();
    }
}
