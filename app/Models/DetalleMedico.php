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
        'horarios',
    ];
    protected $casts = [
        'horarios' => 'array', // Convierte automáticamente el JSON en un array
    ];
    

    public function scopeActivos($query)
    {
       return $query->whereNotIn('estado', [0, 2]);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }
    public function consultas()
    {
        return $this->hasMany(Consulta::class, 'id_medico');
    }
}
