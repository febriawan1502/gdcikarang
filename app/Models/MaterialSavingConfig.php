<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialSavingConfig extends Model
{
    use HasFactory;

    protected $table = 'material_saving_config';

    protected $fillable = [
        'standby',
        'garansi',
        'perbaikan',
        'usul_hapus',
    ];

    protected $casts = [
        'standby' => 'decimal:2',
        'garansi' => 'decimal:2',
        'perbaikan' => 'decimal:2',
        'usul_hapus' => 'decimal:2',
    ];

    /**
     * Get calculated total inspeksi (standby + garansi + perbaikan + usul_hapus)
     */
    public function getTotalInspeksiAttribute()
    {
        return $this->standby + $this->garansi + $this->perbaikan + $this->usul_hapus;
    }
}
