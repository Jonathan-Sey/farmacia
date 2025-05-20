<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;
    protected $table = 'persona';
    protected $fillable = [
        'nombre',
        'nit',
        'rol',
        'telefono',
        'fecha_nacimiento',
        'estado',

    ];

    public function scopeActivos($query)
    {
       return $query->whereNotIn('estado', [0, 2]);
    }


    public function consultas()
    {
        return $this->hasMany(Consulta::class, 'id_persona');
    }

    public function ventas()
    {
        return $this->hasMany(Venta::class, 'id_persona');
    }

    public function fichasMedicas()
{
    return $this->hasMany(FichaMedica::class, 'persona_id');
}

}
