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
        'fecha_consulta',
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
        return $this->belongsTo(User::class, 'id_user');
    }
    public function venta()
    {
        return $this->hasMany(Venta::class, 'id_consulta');
    }
}
