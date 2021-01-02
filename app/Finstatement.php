<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Finstatement extends Model
{
    protected $fillable = ['type', 'sub_type', 'amount', 'balance', 'items', 'profits', 'losses'];

    protected $casts = [
        'items' => 'json',
    ];
}
