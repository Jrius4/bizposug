<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['name', 'contact', 'address', 'balance', 'gender', 'd_o_b', 'gift', 'biograpy'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function businservices()
    {
        return $this->hasMany(Businservice::class);
    }
}
