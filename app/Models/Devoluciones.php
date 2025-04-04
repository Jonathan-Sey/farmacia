<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Devoluciones extends Model
{
    use HasFactory;
    protected $table = 'devoluciones';
    protected $fillable = [
        'venta_id',
        'producto_id',
        'cantidad',
        'monto',
        'motivo',
        'estado',
        'observaciones',
        'usuario_id'
    ];
}
