<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    protected $fillable = ['name', 'contact', 'address', 'balance', 'salary', 'gender', 'd_o_b', 'role', 'biograpy'];

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
