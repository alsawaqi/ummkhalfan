<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $guarded = [];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function categories()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }

    public function ProductImages()
    {
        return $this->hasMany(ProductImages::class, 'product_id');
    }

    public function firstImage()
    {
        return $this->hasOne(ProductImages::class, 'product_id')->oldest();
    }
}
