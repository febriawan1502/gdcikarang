<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialMrwi extends Model
{
    use HasFactory;

    protected $table = 'material_mrwi';

    protected $fillable = [
        'unit_id',
        'nomor_mrwi',
        'tanggal_masuk',
        'sumber',
        'ulp_pengirim',
        'ex_gardu',
        'vendor_pengirim',
        'nama_pengirim',
        'kategori_material',
        'kategori_kerusakan',
        'berdasarkan',
        'lokasi',
        'status',
        'catatan',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
    ];

    public function details(): HasMany
    {
        return $this->hasMany(MaterialMrwiDetail::class, 'material_mrwi_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function generateNomorMrwi(): string
    {
        $year = date('Y');
        $last = self::whereYear('created_at', $year)->orderBy('id', 'desc')->first();
        $sequence = 1;
        if ($last && preg_match('/^MRWI-(\d{4})-(\d{4})$/', $last->nomor_mrwi, $matches)) {
            $sequence = intval($matches[2]) + 1;
        }

        return sprintf('MRWI-%s-%04d', $year, $sequence);
    }
}
