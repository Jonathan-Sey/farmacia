<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    use HasFactory;
    protected $table = 'sucursal';
    protected $fillable = [
        'imagen',
        'nombre',
        'ubicacion',
        'estado',
    ];

    public function scopeActivos($query)
    {
       return $query->whereNotIn('estado', [0, 2]);
    }

    public function almacen()
    {
        return $this->hasMany(Almacen::class, 'id_sucursal');
    }

    public function ventas()
    {
        return $this->hasMany(Venta::class, 'id_sucursal');
    }

     // Relación con la tabla inventario
     public function inventarios()
     {
         return $this->hasMany(Inventario::class, 'id_sucursal');
     }
}
