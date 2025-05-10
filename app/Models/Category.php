<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'code_category',
        'name',
    ];

    public function subCategory()
    {
        return $this->hasMany(SubCategory::class);
    }
    public function informasiBarang()
    {
        return $this->hasMany(InformasiBarang::class);
    }
}
