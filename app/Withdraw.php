<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    protected $fillable = ['name', 'balance', 'amount', 'description', 'withdrawn_by', 'issued_by', 'sys_acc'];
}
