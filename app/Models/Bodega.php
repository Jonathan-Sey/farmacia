<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bodega extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
        'ubicacion',
        'es_principal',
        'estado'
    ];

    public function scopeActivas($query)
    {
        return $query->where('estado', true);
    }

    public function scopePrincipal($query)
    {
        return $query->where('es_principal', true);
    }

    public function inventarios()
    {
        return $this->hasMany(Inventario::class, 'id_bodega');
    }
}
