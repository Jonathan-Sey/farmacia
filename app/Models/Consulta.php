<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consulta extends Model
{
    use HasFactory;
    
    protected $table = 'consulta';

    protected $fillable = [
        'detalle',
        'asunto',
        'fecha_consulta',
        'proxima_cita',
        'id_medico',
        'id_persona',
        'estado',
    ];

    /**
     * Relación con el paciente (persona).
     */
    public function paciente()
    {
        return $this->belongsTo(Persona::class, 'id_persona')->withDefault();
    }

    /**
     * Relación con el médico.
     */
    public function medico()
    {
        return $this->belongsTo(DetalleMedico::class, 'id_medico')->withDefault();
    }

    /**
     * Relación con la ficha médica del paciente.
     */
    public function fichaMedica()
    {
        return $this->hasOne(FichaMedica::class, 'id_persona', 'id_persona');
    }

    /**
     * Relación con las ventas (si aplica).
     */
    public function ventas()
    {
        return $this->hasMany(Venta::class, 'id_consulta');
    }
}
