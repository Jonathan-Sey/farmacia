<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Almacen extends Model
{
    use HasFactory;
    protected $table = 'almacen';
    protected $fillable = [
        'id_sucursal',
        'id_producto',
        'cantidad',
        'fecha_vencimiento',
        'alerta_stock',
        'id_user',
        'estado',
    ];

    public function scopeActivos($query)
    {
       return $query->whereNotIn('estado', [0, 2]);
    }


    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class,'id_sucursal');
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
