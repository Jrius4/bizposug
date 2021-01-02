<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['code', 'products', 'discount', 'total', 'subtotal', 'type_of_transaction', 'customer_id','profit','loss'];
    protected $casts = [
        'products' => 'json',
        'created_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
