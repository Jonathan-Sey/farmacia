<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    use HasFactory;
    protected $table = 'sucursal';
    protected $fillable = [
        'nombre',
        'ubicacion',
        'estado',
    ];

    public function almacen()
    {
        return $this->hasMany(Almacen::class, 'id_sucursal');
    }

    public function ventas()
    {
        return $this->hasMany(Venta::class, 'id_sucursal');
    }
}
