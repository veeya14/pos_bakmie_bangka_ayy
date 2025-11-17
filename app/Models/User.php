<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_user';

    protected $fillable = [
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    // Relasi: user punya banyak menu
    public function menus(): HasMany
    {
        return $this->hasMany(Menu::class, 'id_user');
    }

    // Relasi: user punya banyak order
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'id_user');
    }
}
