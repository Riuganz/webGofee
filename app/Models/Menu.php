<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menu';
    protected $primaryKey = 'id_menu';

    protected $fillable = [
        'nama_menu',
        'deskripsi',
        'harga',
        'id_kategori',
        'stok_status',
        'foto',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    public function detailPesanans()
    {
        return $this->hasMany(DetailPesanan::class, 'id_menu');
    }
}
