<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preguntas extends Model
{
    use HasFactory;
    protected $fillable = ['encuesta_id', 'texto_pregunta', 'tipo', 'opciones', 'orden'];

    protected $casts = [
        'opciones' => 'array'
    ];

    public function encuesta()
    {
        return $this->belongsTo(Encuestas::class, 'encuesta_id');
    }

    public function respuestas()
    {
        return $this->hasMany(Respuestas::class, 'pregunta_id');
    }

}
