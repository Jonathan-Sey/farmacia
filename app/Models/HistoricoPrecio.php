<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoricoPrecio extends Model
{
    use HasFactory;

    protected $table = 'historico_precios';
    protected $fillable = [
        'id_producto', 
        'precio_anterior',
        'precio_nuevo',
        'fecha_cambio',
        'created_at',
        'updated_at'
    ];
    //relacion con la tabla producto.
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto')->withDefault([
            'nombre' => 'Producto Eliminado'
        ]);
    }
}

