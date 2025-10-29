<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderAddon extends Model
{
    protected $table = 'order_addons';

    protected $fillable = [
        'order_id',
        'name',
        'price',
        'price_type',
        'quantity',
        'total',
    ];

    protected $casts = [
        'price'    => 'integer',
        'quantity' => 'integer',
        'total'    => 'integer',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}