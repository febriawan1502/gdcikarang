<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasUnitScope;

/**
 * @mixin IdeHelperBeritaAcara
 */
class BeritaAcara extends Model
{
    use HasUnitScope;

    protected $fillable = [
        'unit_id',
        'hari',
        'tanggal',
        'tanggal_teks',
        'mengetahui',
        'jabatan_mengetahui',
        'pembuat',
        'jabatan_pembuat',
        'file_pdf',
    ];
}
