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
        'limite_compras', // Número máximo de compras permitidas en periodo
        'periodo_control', // Días del periodo de control (ej. 30 días)
        'restriccion_activa', // Si está bajo restricción
        'fecha_ultima_alerta' // Para no saturar con alertas


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
}
