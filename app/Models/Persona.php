<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;
    protected $talbe = 'persona';
    protected $fillable = [
        'nombre',
        'nit',
        'paciente',
        'cliente',
        'telefono',
        'fecha_nacimiento',
        'estado',

    ];
    public function consultas()
    {
        return $this->hasMany(Consulta::class, 'id_persona');
    }

    public function ventas()
    {
        return $this->hasMany(Venta::class, 'id_persona');
    }
}
