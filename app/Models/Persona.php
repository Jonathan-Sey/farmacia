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

    protected $attributes = [
        'estado' => 1, // Valor por defecto para nuevos registros
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];

    // Scope para obtener solo personas activas (estado 1)
    public function scopeActivos($query)
    {
        return $query->where('estado', 1);
    }

    // Relación con consultas (si aplica)
    public function consultas()
    {
        return $this->hasMany(Consulta::class, 'id_persona');
    }

    // Relación con ventas (si aplica)
    public function ventas()
    {
        return $this->hasMany(Venta::class, 'id_persona');
    }

    // Relación con fichas médicas
    public function fichasMedicas()
    {
        return $this->hasMany(FichaMedica::class, 'persona_id');
    }
}
