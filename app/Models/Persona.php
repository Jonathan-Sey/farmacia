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
        'limite_compras',    // Nuevo campo
        'periodo_control',   // Nuevo campo
        'restriccion_activa' // Nuevo campo


    ];

    protected $casts = [
        'restriccion_activa' => 'boolean',
        'fecha_nacimiento' => 'date',
    ];

    // Métodos adicionales
    public function comprasRecientes()
    {
        if (!$this->periodo_control) {
            return 0; // Si no hay período definido, no hay compras recientes
        }

        return $this->ventas()
            ->where('fecha_venta', '>=', now()->subDays($this->periodo_control))
            ->count();
    }

    public function excedeLimiteCompras()
    {
        // Si no hay límite definido, nunca excede
        if (!$this->limite_compras) {
            return false;
        }

        return $this->comprasRecientes() >= $this->limite_compras;
    }
    public function tieneRestriccion()
    {
        // Solo tiene restricción si está activa manualmente o excede el límite
        return $this->restriccion_activa || $this->excedeLimiteCompras();
    }

    public static function boot()
{
    parent::boot();

    static::saving(function ($persona) {
        // Asegurar que los valores numéricos sean positivos o null
        if ($persona->limite_compras !== null && $persona->limite_compras < 0) {
            $persona->limite_compras = null;
        }

        if ($persona->periodo_control !== null && $persona->periodo_control < 1) {
            $persona->periodo_control = null;
        }
    });
}

    protected $attributes = [
        'estado' => 1, // Valor por defecto para nuevos registros
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
