<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Proveedor extends Model
{

    use HasFactory;
    protected $table = 'proveedor';
    protected $fillable = [
        'nombre',
        'telefono',
        'empresa',
        'correo',
        'direccion',
        'estado',
    ];

    public function scopeActivos($query)
    {
       return $query->whereNotIn('estado', [0, 2]);
    }

    public function compra()
    {
        return $this->hasMany(Compra::class, 'id_proveedor');
    }

}
