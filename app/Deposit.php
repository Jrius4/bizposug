<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    protected $fillable = ['name', 'balance', 'amount', 'description', 'deposited_by', 'issued_by', 'sys_acc'];
}
