<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperUnit
 */
class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'plant',
        'storage_location',
        'kode_surat',
        'is_induk',
        'is_active',
    ];
}
