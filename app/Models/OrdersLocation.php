<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdersLocation extends Model
{
    use HasFactory;

    protected $table = 'orders_locations';
    protected $guarded = [];
}
