<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SuratJalan extends Model
{
    use HasFactory;

    protected $table = 'surat_jalan';

    protected $fillable = [
        'nomor_surat',
        'jenis_surat_jalan',
        'tanggal',
        'kepada',
        'berdasarkan',
        'security',
        'keterangan',
        'kendaraan',
        'no_polisi',
        'pengemudi',
        'status',
        'created_by',
        'approved_by',
        'approved_at'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'approved_at' => 'datetime'
    ];

    /**
     * Relasi ke User yang membuat surat jalan
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relasi ke User yang menyetujui surat jalan
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Relasi ke detail surat jalan
     */
    public function details(): HasMany
    {
        return $this->hasMany(SuratJalanDetail::class);
    }

    /**
     * Generate nomor surat static berdasarkan jenis
     */
    public static function generateNomorSurat($jenisSuratJalan = 'Normal')
    {
        $year = date('Y');
        
        // Format static berdasarkan jenis surat jalan
        $formats = [
            'Normal' => "114.SJ/LOG.00.02/F02050000/{$year}",
            'Gangguan' => "114.GGN/LOG.00.02/F02050000/{$year}",
            'Garansi' => "114.GRN/LOG.00.02/F02050000/{$year}",
            'Peminjaman' => "114.PMJ/LOG.00.02/F02050000/{$year}"
        ];
        
        return $formats[$jenisSuratJalan] ?? $formats['Normal'];
    }

    /**
     * Scope untuk status tertentu
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}