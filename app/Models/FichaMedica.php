<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FichaMedica extends Model {
    use HasFactory;

    protected $fillable = [
        'id_persona', 'id_medico', 'edad', 'peso', 'altura', 'presion_arterial', 
        'sintomas', 'diagnostico', 'tratamiento'
    ];

    public function paciente() {
        return $this->belongsTo(Persona::class, 'id_persona');
    }

    public function medico() {
        return $this->belongsTo(DetalleMedico::class, 'id_medico');
    }
}

