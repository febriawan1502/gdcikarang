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
    ];

    /**
     * Atribut yang harus di-cast ke tipe data tertentu
     */
    protected $casts = [
        'tanggal_terima' => 'date',
        'harga_satuan' => 'decimal:2',
        'total_harga' => 'decimal:2',
        'qty' => 'integer',
        'unrestricted_use_stock' => 'decimal:3',
        'quality_inspection_stock' => 'decimal:3',
        'blocked_stock' => 'decimal:3',
        'in_transit_stock' => 'decimal:3',
        'project_stock' => 'decimal:3',
        'is_active' => 'boolean'
    ];

    /**
     * Status constants
     */
    const STATUS_SELESAI = 'SELESAI';
    const STATUS_PROSES = 'PROSES';
    const STATUS_PENDING = 'PENDING';
    const STATUS_RUSAK = 'RUSAK';

    /**
     * Mendapatkan semua status yang tersedia
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_SELESAI => 'Selesai',
            self::STATUS_PROSES => 'Proses',
            self::STATUS_PENDING => 'Pending',
            self::STATUS_RUSAK => 'Rusak'
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
            case self::STATUS_SELESAI:
                return 'success';
            case self::STATUS_PROSES:
                return 'warning';
            case self::STATUS_PENDING:
                return 'info';
            case self::STATUS_RUSAK:
                return 'danger';
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
        $lastMaterial = self::orderBy('id', 'desc')->first();
        $lastNumber = $lastMaterial ? (int) $lastMaterial->nomor : 0;
        return $lastNumber + 1;
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
            
            // Auto generate nomor jika tidak ada
            if (empty($model->nomor)) {
                $model->nomor = self::generateNomor();
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