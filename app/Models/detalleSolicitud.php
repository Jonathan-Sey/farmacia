<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class detalleSolicitud extends Model
{
    use HasFactory;
    protected $table = 'detalles_solisitud';
    protected $fillable = [
        'solicitud_salida',
        'solicitud_entrada',
        'producto_id',
        'id_solicitud',
        'cantidad',
        'Id_usuario',
        'estado'
    ];

    public function scopeActivos($query)
    {
       return $query->whereNotIn('estado', [0, 2]);
    }

    public function sucursal1()
    {
        return $this->belongsTo(Sucursal::class,'solicitud_salida');
    }

    public function sucursal2()
    {
        return $this->belongsTo(Sucursal::class,'solicitud_entrada');
    }
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'Id_usuario');
    }
}
