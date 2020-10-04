<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Suppgroup extends Model
{
    public $fillable = ['name', 'slug'];


    public function suppliers()
    {
        return $this->hasMany(Supplier::class);
    }
}
