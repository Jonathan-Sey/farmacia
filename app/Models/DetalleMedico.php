<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class DetalleMedico extends Model
{
    use HasFactory;

    protected $table = 'detalle_medico';


    protected $fillable = [
        'id_usuario',
        'id_especialidad',
        'numero_colegiado',
        'estado',
        'horarios',
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

    public function medico()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function especialidad()
    {
        return $this->belongsTo(Especialidades::class, 'id_especialidad');
    }

    //protected $with = ['horarios'];

    public function getHorariosAttribute()
    {
        if (!array_key_exists('horarios', $this->relations)) {
            $this->load('horarios');
        }

        return $this->getRelation('horarios');
    }
    // Relación con Horarios
    public function horarios()
    {
        return $this->hasMany(Horario::class, 'medico_id', 'id');
    }
}
