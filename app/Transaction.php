<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['code', 'products', 'discount', 'total', 'subtotal', 'type_of_transaction', 'customer_id'];
    protected $casts = [
        'products' => 'json',
        'created_at' => 'datetime',
    ];
}
