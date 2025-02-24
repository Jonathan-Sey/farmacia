<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    use HasFactory;

    protected $casts = [
        'horarios' => 'array', // Convierte automáticamente el JSON en un array PHP
    ];

    protected $table = 'horarios';

    protected $fillable = ['medico_id', 'sucursal_id', 'estado', 'horarios'];


    public function medico()
    {
        return $this->belongsTo(DetalleMedico::class, 'medico_id');
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_id');
    }
}
