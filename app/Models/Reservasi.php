<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservasi extends Model
{
    protected $table = 'reservasi';
    protected $primaryKey = 'id_reservasi';

    protected $fillable = [
        'id_user',
        'id_meja',
        'tanggal',
        'jam',
        'jumlah_orang',
        'tipe',
        'catatan',
        'metode_bayar',
        'total_harga',
        'status_bayar',
        'status_reservasi',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function meja()
    {
        return $this->belongsTo(Meja::class, 'id_meja');
    }

    public function detailPesanans()
    {
        return $this->hasMany(DetailPesanan::class, 'id_reservasi');
    }

    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class, 'id_reservasi');
    }

    public function pembayaranTerakhir()
    {
        return $this->hasOne(Pembayaran::class, 'id_reservasi')->latestOfMany('id_pembayaran');
    }
}
