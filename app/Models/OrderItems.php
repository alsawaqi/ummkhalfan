<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    use HasFactory;

    protected $table = 'order_items';
    protected $guarded = [];

    public function order()
    {
        return $this->belongsTo(Orders::class, 'orders_id');
    }

    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }
}
