<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sizeprice extends Model
{
    protected $fillable = ['name', 'cost', 'whole_sale', 'retail_sale', 'product_id'];
    //

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
