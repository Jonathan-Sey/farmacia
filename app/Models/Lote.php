<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lote extends Model
{
    use HasFactory;

    protected $table = 'lote';// correccion tabla a table
    protected $fillable = [
        'id_producto',
        'numero_lote',
        'fecha_vencimiento',
        'cantidad',
        'id_compra',
    ];

      // Relación con la tabla producto
      public function producto()
      {
          return $this->belongsTo(Producto::class, 'id_producto');
      }

      // Relación con la tabla compra
      public function compra()
      {
          return $this->belongsTo(Compra::class, 'id_compra');
      }

      // Relación con la tabla inventario
      public function inventarios()
      {
          return $this->hasMany(Inventario::class, 'id_lote');
      }

      // Relación con la tabla lote
    public function lotes()
    {
        return $this->hasMany(Lote::class, 'id_compra');
    }
}
