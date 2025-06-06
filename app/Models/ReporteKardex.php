<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReporteKardex extends Model
{
    use HasFactory;
    protected $table = 'reporte_kardex';
    protected $fillable = [
        'producto_id',
        'nombre_sucursal',
        'tipo_movimiento',
        'cantidad',
        'Cantidad_anterior',
        'Cantidad_nueva',
        'usuario_id',

        'fecha_movimiento',
    ];
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
  
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
    public function scopeActivos($query)
    {
        return $query->whereNotIn('estado', [0, 2]);
    }
   
}
