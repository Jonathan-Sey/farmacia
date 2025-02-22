<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_persona');
    }

     public function medico()
        {
         return $this->belongsTo(DetalleMedico::class, 'id_medico');
        }

    public function venta()
    {
        return $this->hasMany(Venta::class, 'id_consulta');
    }
    
}
