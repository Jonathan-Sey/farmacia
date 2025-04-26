<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    use HasFactory;
    protected $table = 'rol';
    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
    ];

    public function usuario()
    {
        return $this->hasMany(User::class,'id_rol');
    }
    public function pestanas()
    {
        return $this->belongsToMany(Pestana::class, 'rol_pestana', 'rol_id', 'pestana_id')
            ->withPivot('orden', 'es_inicio');
    }
     // Metodo para obtener la pagina de inicio
     public function paginaInicio()
     {
         return $this->pestanas()
             ->wherePivot('es_inicio', 1)
             ->first();
     }
}
