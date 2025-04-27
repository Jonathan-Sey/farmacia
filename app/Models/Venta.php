<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;
    protected $table = 'venta';
    protected $fillable = [
        'id_sucursal',
        'fecha_venta',
        'impuesto',
        'total',
        'id_usuario',
        'id_consulta',
        'id_persona',
        'estado',
        'es_prescrito',
        'imagen_receta',
        'numero_reserva',
        'descripcion',
    ];

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'id_sucursal');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function consulta()
    {
        return $this->belongsTo(Consulta::class, 'id_consulta');
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_persona');
    }

    public function detalles()
{
    return $this->hasMany(DetalleVenta::class, 'id_venta', 'id');
}

     public function productos()
 {
     return $this->belongsToMany(Producto::class, 'detalle_venta', 'id_venta', 'id_producto')
                 ->withPivot('cantidad', 'precio')
                 ->withTimestamps();
 }

}
