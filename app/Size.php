<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $fillabe = ['slug', 'name'];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
