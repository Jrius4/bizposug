<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Finance extends Model
{
    protected $fillable = [
        'type', 'amount', 'current_balance', 'previous_balance', 'details',
        'previous_main_balance', 'current_main_balance'
    ];
}
