<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prodgroup extends Model
{
    public $fillable = ['name', 'slug'];
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
