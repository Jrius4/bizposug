<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [

        'name', 'barcode', 'category', 'company_name', 'cost_price', 'supplier_id',
        'wholesale_price', 'retailsale_price', 'quantity', 'tax_percentage', 'avatar', 'prodgroup_id'
    ];

    public function prodgroup()
    {
        return $this->belongsTo(Prodgroup::class);
    }

    public function brands()
    {
        return $this->belongsToMany(Brand::class);
    }

    public function sizes()
    {
        return $this->belongsToMany(Size::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
