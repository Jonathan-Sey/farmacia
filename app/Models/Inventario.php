<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    use HasFactory;
    protected $table = 'inventario';
    protected $primaryKey = 'id_inventario'; // Indica la clave primaria

    protected $fillable = [
        'id_producto',
        'id_bodega',
        'id_lote',
        'cantidad',
    ];

     // Relación con la tabla producto
     public function producto()
     {
         return $this->belongsTo(Producto::class, 'id_producto');
     }

     // Relación con la tabla sucursal
     public function bodega()
     {
         return $this->belongsTo(Bodega::class, 'id_bodega');
     }

     // Relación con la tabla lote
     public function lote()
     {
         return $this->belongsTo(Lote::class, 'id_lote');
     }

}
