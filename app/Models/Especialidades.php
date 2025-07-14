<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Especialidades extends Model
{
    use HasFactory;
    protected $table = 'especialidades';
    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
    ];


        public function scopeActivos($query)
    {
       return $query->whereNotIn('estado', [0, 2]);
    }
}
