<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $fillable = [
        'informasi_barang_id',
        'name',
        'harga',
        'jumlah_barang',
        'satuan',
        'total_barang',
        'expired',
        'status',
    ];

    public function informasiBarang()
    {
        return $this->belongsTo(InformasiBarang::class);
    }
}
