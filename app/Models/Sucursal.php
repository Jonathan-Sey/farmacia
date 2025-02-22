<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Sucursal extends Model
{
    use HasFactory;
    protected $table = 'sucursal';
    protected $fillable = [
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
<<<<<<< HEAD
  
=======

     // Relación con la tabla inventario
     public function inventarios()
     {
         return $this->hasMany(Inventario::class, 'id_sucursal');
     }
>>>>>>> 5ead12452b8f187d24d25f8c4a9b3741c2793571
}
