<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vencidos extends Model
{
    use HasFactory;
    protected $table = 'producto_vencidos';

    protected $fillable = [
        'id_producto',
        'id_compra',
        'cantidad',
        'fecha_vencimiento',
    ];
}
