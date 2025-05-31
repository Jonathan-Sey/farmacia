<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requisicion extends Model
{
    protected $table = 'requisicion';
    use HasFactory;

    protected $fillable = [
        'id_bodega_origen',
        'id_sucursal_destino',
        'id_producto',
        'id_lote',
        'cantidad',
        'fecha_traslado',
        'id_usuario',
    ];

    // public function sucursalOrigen()
    // {
    //     return $this->belongsTo(Sucursal::class, 'id_sucursal_origen');
    // }

    public function bodegaOrigen()
    {
        return $this->belongsTo(Bodega::class, 'id_bodega_origen');
    }

    public function sucursalDestino()
    {
        return $this->belongsTo(Sucursal::class, 'id_sucursal_destino');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }

    public function lote()
    {
        return $this->belongsTo(Lote::class, 'id_lote');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }
}
