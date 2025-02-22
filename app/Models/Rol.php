<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
        return $this->belongsToMany(Pestana::class, 'rol_pestana', 'rol_id', 'pestana_id');
    }
  
}
