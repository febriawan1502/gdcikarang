<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialMrwiSerialMove extends Model
{
    use HasFactory;

    protected $table = 'material_mrwi_serial_moves';

    protected $fillable = [
        'serial_number',
        'material_id',
        'unit_id',
        'jenis',
        'status_bucket',
        'surat_jalan_id',
        'surat_jalan_detail_id',
        'reference_type',
        'reference_number',
        'tanggal',
        'keterangan',
    ];
}
