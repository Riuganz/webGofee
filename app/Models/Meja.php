<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meja extends Model
{
    protected $table = 'meja';
    protected $primaryKey = 'id_meja';

    protected $fillable = [
        'nomor_meja',
        'kapasitas',
        'status_meja',
    ];

    public function reservasis()
    {
        return $this->hasMany(Reservasi::class, 'id_meja');
    }
}
