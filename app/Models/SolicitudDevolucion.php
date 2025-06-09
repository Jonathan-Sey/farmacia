<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudDevolucion extends Model
{
    use HasFactory;
      protected $table = 'solicitudes_devolucion';

    protected $fillable = [
        'venta_id',
        'usuario_id',
        'persona_id',
        'sucursal_id',
        'motivo',
        'total',
        'observaciones',
        'detalles', // JSON con los detalles de los productos
        'estado', // pendiente, autorizado, rechazado
    ];

    protected $casts = [
        'detalles' => 'array', // convierte automáticamente de JSON a array
    ];
}
