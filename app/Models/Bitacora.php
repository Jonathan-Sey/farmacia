<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bitacora extends Model
{
    use HasFactory;

    
    protected $table = 'bitacoras';
    protected $fillable = [
        'id',
        'id_usuario',
        'name_usuario',
        'accion',
        'tabla_afectada',
        'detalles',
        'fecha_hora',
    ];
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
