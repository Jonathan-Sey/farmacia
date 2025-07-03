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
        'codigo_sucursal',
        'ubicacion',
        'telefono',
        'email',
        'encargado',
        'latitud',
        'longitud',
        'google_maps_link',
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

     public function users()
     {
        return $this->hasMany(User::class);
     }

     public function fichasMedicas()
     {
        return $this->hasMany(FichaMedica::class);
     }



}
