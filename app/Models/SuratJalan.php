<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\MaterialHistory;
use App\Models\Concerns\HasUnitScope;


/**
 * @mixin IdeHelperSuratJalan
 */
class SuratJalan extends Model
{
    use HasFactory;
    use HasUnitScope;
    protected static function booted()
    {
        static::deleting(function ($suratJalan) {
            MaterialHistory::where('source_type', 'surat_jalan')
                ->where('source_id', $suratJalan->id)
                ->delete();
        });
    }


    protected $table = 'surat_jalan';

    protected $fillable = [
        'unit_id',
        'nomor_surat',
        'jenis_surat_jalan',
        'tanggal',
        'kepada',
        'berdasarkan',
        'security',
        'keterangan',
        'nama_penerima',
        'nomor_slip',
        'kendaraan',
        'no_polisi',
        'pengemudi',
        'foto_penerima',
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
     * Relasi ke material histories
     */
    public function histories()
    {
        return $this->hasMany(MaterialHistory::class, 'source_id')
            ->where('source_type', 'surat_jalan');
    }

    public function getStatusSapAttribute(): string
    {
        if (self::isStockAffectingJenis($this->jenis_surat_jalan) && $this->jenis_surat_jalan !== 'Normal') {
            return 'Selesai SAP'; // MRWI types are considered done regardless of slip number
        }
        return empty($this->nomor_slip) ? 'Belum Selesai SAP' : 'Selesai SAP';
    }

    /**
     * Generate nomor surat dengan sequence berdasarkan jenis, bulan, dan tahun
     */
    public static function generateNomorSurat($jenisSuratJalan = 'Normal')
    {
        $year = date('Y');

        // Tentukan kode prefix berdasarkan jenis surat
        $jenisKode = [
            'Normal'     => 'SJ',
            'Garansi'    => 'GRN',
            'Peminjaman' => 'PMJ',
            'Perbaikan'  => 'PBK',
            'Manual'     => 'MNL',
            'Rusak'      => 'RSK',
            'Standby'    => 'STB',
        ];

        $kode = $jenisKode[$jenisSuratJalan] ?? 'SJ';
        $kodeLog = 'LOG.00.02';
        $kodeFungsi = Auth::user()->unit->kode_surat ?? 'F02050000';

        // 🔹 Ambil surat terakhir dari semua jenis (global numbering)
        $lastSurat = self::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        // Tentukan urutan berikutnya
        if ($lastSurat) {
            preg_match('/^(\d{3})/', $lastSurat->nomor_surat, $matches);
            $sequence = isset($matches[1]) ? intval($matches[1]) + 1 : 1;
        } else {
            $sequence = 1;
        }

        // 🔹 Format hasil akhir dengan prefix sesuai jenis
        return sprintf("%03d.%s/%s/%s/%s", $sequence, $kode, $kodeLog, $kodeFungsi, $year);
    }


    private static function getNextSequence()
    {
        $lastSurat = self::orderByRaw("CAST(split_part(nomor_surat, '.', 1) AS INTEGER) DESC")
            ->whereRaw("split_part(nomor_surat, '.', 1) ~ '^[0-9]+$'")
            ->first();

        if (!$lastSurat) {
            return 1;
        }

        $nomorParts = explode('.', $lastSurat->nomor_surat);
        $lastSequence = isset($nomorParts[0]) ? intval($nomorParts[0]) : 0;

        return $lastSequence + 1;
    }



    /**
     * Konversi angka bulan ke romawi
     */
    private static function getMonthRoman($month)
    {
        $romans = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII'
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
    public function isManualLike(): bool
    {
        return in_array($this->jenis_surat_jalan, ['Manual', 'Peminjaman']);
    }
    public static function isManualLikeJenis(string $jenis): bool
    {
        return in_array($jenis, ['Manual', 'Peminjaman']);
    }
    public static function isStockAffectingJenis($jenis)
    {
        return in_array($jenis, ['Normal', 'Garansi', 'Perbaikan', 'Rusak', 'Standby'], true);
    }
}
