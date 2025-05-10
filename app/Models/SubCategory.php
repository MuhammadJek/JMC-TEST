<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'max_price'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function informasiBarang()
    {
        return $this->hasMany(InformasiBarang::class);
    }

}
