<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';
    protected $primaryKey = 'id_pembayaran';

    protected $fillable = [
        'id_reservasi',
        'order_id',
        'snap_token',
        'transaction_id',
        'payment_type',
        'status',
        'metode_pembayaran',
        'va_numbers',
        'qr_code_url',
        'pdf_url',
        'waktu_dibayar',
    ];

    protected $casts = [
        'va_numbers' => 'array',
        'waktu_dibayar' => 'datetime',
    ];

    public function reservasi()
    {
        return $this->belongsTo(Reservasi::class, 'id_reservasi');
    }
}