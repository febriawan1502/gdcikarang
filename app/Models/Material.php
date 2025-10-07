<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Material extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Nama tabel dalam database
     */
    protected $table = 'materials';

    /**
     * Atribut yang dapat diisi secara massal
     */
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
        'unrestricted_use_stock',
        'quality_inspection_stock',
        'blocked_stock',
        'in_transit_stock',
        'project_stock',
        'wbs_element',
        'valuation_class',
        'valuation_description',
        'harga_satuan',
        'total_harga',
        'currency',
        'pabrikan',
        'normalisasi',
        'qty',
        'tanggal_terima',
        'keterangan',
        'status',
        'rak',
        'created_by',
    ];

    /**
     * Atribut yang harus di-cast ke tipe data tertentu
     */
    protected $casts = [
        'tanggal_terima' => 'date',
        'harga_satuan' => 'decimal:2',
        'total_harga' => 'decimal:2',
        'qty' => 'integer',
        'unrestricted_use_stock' => 'decimal:0',
        'quality_inspection_stock' => 'decimal:0',
        'blocked_stock' => 'decimal:0',
        'in_transit_stock' => 'decimal:0',
        'project_stock' => 'decimal:0',
        'is_active' => 'boolean'
    ];

    /**
     * Status constants
     */
    const STATUS_BAIK = 'BAIK';
    const STATUS_RUSAK = 'RUSAK';
    const STATUS_DALAM_PERBAIKAN = 'DALAM PERBAIKAN';

    /**
     * Mendapatkan semua status yang tersedia
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_BAIK => 'Baik',
            self::STATUS_RUSAK => 'Rusak',
            self::STATUS_DALAM_PERBAIKAN => 'Dalam Perbaikan'
        ];
    }

    /**
     * Relasi dengan user yang membuat record
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relasi dengan user yang mengupdate record
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scope untuk pencarian
     */
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

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk filter berdasarkan pabrikan
     */
    public function scopeByPabrikan($query, $pabrikan)
    {
        return $query->where('pabrikan', 'LIKE', "%{$pabrikan}%");
    }

    /**
     * Scope untuk material dengan stock rendah
     */
    public function scopeLowStock($query, $threshold = 10)
    {
        return $query->where('qty', '<=', $threshold);
    }

    /**
     * Accessor untuk format harga satuan
     */
    public function getFormattedHargaSatuanAttribute()
    {
        return 'Rp ' . number_format($this->harga_satuan, 0, ',', '.');
    }

    /**
     * Accessor untuk format total harga
     */
    public function getFormattedTotalHargaAttribute()
    {
        return 'Rp ' . number_format($this->total_harga, 0, ',', '.');
    }

    /**
     * Accessor untuk format tanggal terima
     */
    public function getFormattedTanggalTerimaAttribute()
    {
        return $this->tanggal_terima ? $this->tanggal_terima->format('d F Y') : '-';
    }

    /**
     * Accessor untuk status badge color
     */
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

    /**
     * Accessor untuk total stock
     */
    public function getTotalStockAttribute()
    {
        return $this->unrestricted_use_stock + 
               $this->quality_inspection_stock + 
               $this->blocked_stock + 
               $this->in_transit_stock + 
               $this->project_stock;
    }

    /**
     * Method untuk generate nomor otomatis
     */
    public static function generateNomor()
    {
        try {
            $lastMaterial = self::orderBy('nomor', 'desc')->first();
            $lastNumber = $lastMaterial ? (int) $lastMaterial->nomor : 0;
            return $lastNumber + 1;
        } catch (\Exception $e) {
            // Fallback jika ada masalah dengan query
            return time(); // Menggunakan timestamp sebagai fallback
        }
    }

    /**
     * Boot method untuk auto-fill created_by dan updated_by
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (auth()->check()) {
                $model->created_by = auth()->id();
                $model->updated_by = auth()->id();
            }
            
            // Skip auto generate nomor during import to avoid transaction issues
            // if (empty($model->nomor) && !app()->runningInConsole()) {
            //     $model->nomor = self::generateNomor();
            // }
            
            // Set default status jika tidak ada
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

    /**
     * Method untuk cek apakah material bisa dihapus
     */
    public function canBeDeleted()
    {
        // Tambahkan logic untuk cek relasi dengan tabel lain
        // Misalnya jika material sudah digunakan di transaksi
        return true;
    }

    /**
     * Method untuk update stock
     */
    public function updateStock($newQty, $reason = null)
    {
        $oldQty = $this->qty;
        $this->update(['qty' => $newQty]);
        
        // Log perubahan stock (bisa dikembangkan kemudian)
        // StockMovement::create([
        //     'material_id' => $this->id,
        //     'old_qty' => $oldQty,
        //     'new_qty' => $newQty,
        //     'reason' => $reason,
        //     'created_by' => auth()->id()
        // ]);
        
        return $this;
    }
}