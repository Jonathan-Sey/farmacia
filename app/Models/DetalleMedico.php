<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleMedico extends Model
{
    use HasFactory;

    protected $table = 'detalle_medico';
    

    protected $fillable = [
        'id_usuario',
        'especialidad',
        'numero_colegiado',
        'estado',
    ];

    public function scopeActivos($query)
    {
        return $query->whereNotIn('estado', [0, 2]);
    }

    // Relación con Usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    // Relación con Consultas
    public function consultas()
    {
        return $this->hasMany(Consulta::class, 'id_medico');
    }

    protected $with = ['horarios.sucursal'];
    // Relación con Horarios
    public function horarios()
    {
        return $this->hasMany(Horario::class, 'medico_id');
    }
}
