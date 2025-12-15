<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $table = 'order_details';
    protected $primaryKey = 'order_detail_id';
    public $timestamps = true;

    protected $fillable = [
        'order_id',
        'menu_id',
        'quantity',
        'subtotal',
        'notes',
    ];

public function order()
{
    return $this->belongsTo(Order::class, 'order_id', 'order_id');
}

public function menu()
{
    return $this->belongsTo(Menu::class, 'menu_id', 'id_menu');
}

}
