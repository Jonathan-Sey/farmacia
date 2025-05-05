<?php

namespace App\Models;

use Database\Seeders\productos;
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
        'usuario_id',
        'persona_id',
        'sucursal_id',
        'fecha_devolucion',
    ];


    public function productos(){
        return $this->belongsTo(Producto::class,  'producto_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_id');
    }
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }
}
