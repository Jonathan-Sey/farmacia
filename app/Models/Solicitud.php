<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    use HasFactory;

    protected $table = 'solicitud_producto';

    protected $fillable = [
        'id_sucursal_origen',
        'id_sucursal_destino',
        'id_producto',
        'cantidad',
        'descripcion',
        'estado',
    ];


    public function scopeActivos($query)
    {
       return $query->whereNotIn('estado', [0, 2]);
    }

    public function sucursal1()
    {
        return $this->belongsTo(Sucursal::class,'id_sucursal_origen');
    }

    public function sucursal2()
    {
        return $this->belongsTo(Sucursal::class,'id_sucursal_destino');
    }
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
