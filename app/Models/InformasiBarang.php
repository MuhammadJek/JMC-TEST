<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InformasiBarang extends Model
{
    protected $fillable = [
        'operator_id',
        'category_id',
        'sub_category_id',
        'max_price',
        'asal_barang',
        'no_surat',
        'file',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }
    public function users()
    {
        return $this->belongsTo(User::class, 'operator_id');
    }
    public function barang()
    {
        return $this->hasMany(Barang::class);
    }
}
