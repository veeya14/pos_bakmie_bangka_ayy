<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menus';
    protected $primaryKey = 'id_menu'; 
    public $timestamps = true;

    protected $fillable = [
        'id_category',   
        'menu_name',
        'menu_description',
        'menu_price',
        'menu_status',
        'menu_active',
        'menu_image',
        'display_status',
    ];

    public function category()
    {

        return $this->belongsTo(Category::class, 'id_category', 'id_category');
    }

public function orderDetails()
{

    return $this->hasMany(OrderDetail::class, 'menu_id', 'id_menu');
}
}
