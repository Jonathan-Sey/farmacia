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
    ];
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }
}