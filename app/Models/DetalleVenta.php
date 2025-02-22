<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DetalleVenta extends Model
{
    use HasFactory;
    protected $table = 'detalle_venta';
    protected $fillable = [
        'id_venta',
        'id_producto',
        'cantidad',
        'precio',
        'estado',
    ];

    public function venta()
    {
        return $this->belongsTo(Venta::class, 'id_venta');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }
    /*
    public static function boot()
    {
        parent::boot();

        static::created(function ($venta) {
            $producto = Persona::find($venta->id_persona);
            Bitacora::create([
                
                'id_usuario' => Auth::id(),
                'name_usuario' => Auth::user()->name,
                'accion' => 'creacion',
                'tabla_afectada' => 'Venta',
                'detalles' => "Se creó venta: {$venta->nombre} (" . ($producto ? $producto->nombre : 'Desconocido') . ")",

                'fecha_hora' => now(),
            ]);
        });

        // evento para registrar la actualización
        static::updated(function ($venta) {
            Bitacora::create([
                'id_usuario' => Auth::id(),
                'name_usuario' => Auth::user()->name,
                'accion' => 'actualizacion',
                'tabla_afectada' => 'Venta',
                'detalles' => "Se actualizó la compra: {$venta->numero_compra}", 
                'fecha_hora' => now(),
            ]);
        });

        // evento para registrar la eliminación
        static::deleted(function ($venta) {
            Bitacora::create([
                'id_usuario' => Auth::id(),
                'name_usuario' => Auth::user()->name,
                'accion' => 'eliminacion',
                'tabla_afectada' => 'Venta',
                'detalles' => "Se eliminó la categoria: {$venta->numero_compra}", 
                'fecha_hora' => now(),
            ]);
        });

    }
*/

}
