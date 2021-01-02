<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['serial_no', 'worker_id', 'supplier_id', 'reciever', 'received_by', 'items', 'type_payment', 'paid', 'description', 'received_by', 'balance', 'issued_by', 'sys_u'];
    protected $casts = [
        'items' => 'json',
        'reciever' => 'json',
    ];

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
