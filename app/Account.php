<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = ['slug', 'name', 'balance', 'min_balance', 'description'];
}
