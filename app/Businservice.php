<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Businservice extends Model
{
    protected $fillable = [
        'customer_id', 'served', 'service_name', 'service_type', 'serial_no', 'amount_paid', 'amount_agreed',
        'balance_due', 'comment', 'whatz_left', 'served_by', 'sys_user', 'items', 'prev_balance', 'client_details', 'item_amount',
        'cost', 'profit'
    ];
    protected $casts = [
        'served' => 'json',
        'items' => 'json',
        'client_details' => 'json',
    ];
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
