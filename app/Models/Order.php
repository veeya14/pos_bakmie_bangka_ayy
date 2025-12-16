<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Meja;

class Order extends Model
{
    use HasFactory;

    protected $primaryKey = 'order_id'; 

 protected $fillable = [
    'customer_name',
    'status_order',
    'status_bayar',
    'order_datetime',
    'notes',
    'meja_id',
];

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'order_id');
    }

public function details()
{
    return $this->hasMany(\App\Models\OrderDetail::class, 'order_id', 'order_id');
}

public function payment()
{
    return $this->hasOne(\App\Models\Payment::class, 'order_id', 'order_id');
}

public function meja()
{
    return $this->belongsTo(Meja::class, 'meja_id', 'meja_id');
}

}
