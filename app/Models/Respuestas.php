<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Respuestas extends Model
{
    use HasFactory;

    protected $fillable = ['pregunta_id', 'paciente_id', 'respuesta'];

    public function pregunta()
    {
        return $this->belongsTo(Preguntas::class, 'pregunta_id');
    }

    public function paciente()
    {
        return $this->belongsTo(User::class, 'paciente_id');
    }


}
