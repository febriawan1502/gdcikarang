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
     * Generate nomor surat dengan sequence berdasarkan jenis, bulan, dan tahun
     */
    public static function generateNomorSurat($jenisSuratJalan = 'Normal')
    {
        $year = date('Y');
        $month = date('n'); // 1-12
        $monthRoman = self::getMonthRoman($month);
        
        // Kode jenis surat jalan
        $jenisKode = [
            'Normal' => 'SJ',
            'Gangguan' => 'GGN',
            'Garansi' => 'GRN',
            'Peminjaman' => 'PMJ'
        ];
        
        $kode = $jenisKode[$jenisSuratJalan] ?? $jenisKode['Normal'];
        
        // Hitung sequence berdasarkan jenis, bulan, dan tahun
        $sequence = self::getNextSequence($jenisSuratJalan, $month, $year);
        
        // Format: 001.SJ/LOG.00.02/F02050000/XI/2025
        return sprintf("%03d.%s/LOG.00.02/F02050000/%s/%s", $sequence, $kode, $monthRoman, $year);
    }
    
    /**
     * Mendapatkan sequence berikutnya untuk jenis surat jalan, bulan, dan tahun tertentu
     */
    private static function getNextSequence($jenisSuratJalan, $month, $year)
    {
        $lastSurat = self::where('jenis_surat_jalan', $jenisSuratJalan)
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->orderBy('id', 'desc')
            ->first();
            
        if (!$lastSurat) {
            return 1;
        }
        
        // Extract sequence dari nomor surat terakhir
        // Format: 001.SJ/LOG.00.02/F02050000/XI/2025
        $nomorParts = explode('.', $lastSurat->nomor_surat);
        $lastSequence = intval($nomorParts[0]);
        
        return $lastSequence + 1;
    }
    
    /**
     * Konversi angka bulan ke romawi
     */
    private static function getMonthRoman($month)
    {
        $romans = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
            7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];
        
        return $romans[$month] ?? 'I';
    }

    /**
     * Scope untuk status tertentu
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}