<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meja extends Model
{
    use HasFactory;

    protected $table = 'meja';
    protected $primaryKey = 'meja_id';
    public $timestamps = true;

    protected $fillable = [
        'nomor_meja',
        'kapasitas',
        'qr_code',
    ];

}
