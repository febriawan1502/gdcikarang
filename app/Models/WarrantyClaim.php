<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarrantyClaim extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $dates = [
        'submission_date',
        'pickup_date',
        'return_date'
    ];

    protected $casts = [
        'submission_date' => 'datetime',
        'pickup_date' => 'datetime',
        'return_date' => 'datetime',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function pickupSuratJalan()
    {
        return $this->belongsTo(SuratJalan::class, 'pickup_surat_jalan_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function generateTicketNumber()
    {
        // Format: WC-YYYYMMDD-XXXX
        $prefix = 'WC-' . date('Ymd') . '-';
        $last = self::where('ticket_number', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if (!$last) {
            return $prefix . '0001';
        }

        $lastNumber = intval(substr($last->ticket_number, -4));
        return $prefix . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
    }
}
