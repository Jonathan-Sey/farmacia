<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Encuestas extends Model
{
    use HasFactory;
    protected $fillable = ['medico_id', 'titulo', 'descripcion', 'activa'];

    public function medico()
    {
        return $this->belongsTo(DetalleMedico::class, 'medico_id');
    }

    public function preguntas()
    {
        return $this->hasMany(Preguntas::class ,'encuesta_id') ;
    }
}
