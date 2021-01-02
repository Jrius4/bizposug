<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    public $fillable = ['name', 'slug', 'suppgroup_id', 'company', 'address', 'balance', 'contact', 'biograpy'];
    public function Suppgroup()
    {
        return $this->belongsTo(Suppgroup::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
