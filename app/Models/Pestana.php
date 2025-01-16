<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pestana extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'slug', 'ruta', 'estado'];

    // Relación muchos a muchos con roles
    public function roles()
    {
        return $this->belongsToMany(Rol::class, 'rol_pestana', 'pestana_id', 'rol_id');
    }
}
