<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'name', 'amount', 'prodgroup',
        'size', 'brand', 'barcode', 'company_name', 'cost_price', 'wholesale_price',
        'retailsale_price', 'supplier', 'tax_percentage',
        'qty', 'wholeprice', 'retailprice'
    ];
}
