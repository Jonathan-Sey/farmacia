<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
    
    /*
    public static function boot()
    {
        parent::boot();

        static::created(function ($medico) {
            $usuario = User::find($medico->id_user);
            Bitacora::create([
                'id_usuario' => $usuario->id_usuario,
                'name_usuario' =>$usuario->name,
                'accion' => 'creacion',
                'tabla_afectada' => 'Medicos',
                'detalles' => "Se creó medico: {$medico->nombre} (Usuario: " . ($usuario ? $usuario->name : 'Desconocido') . ")",
                'fecha_hora' => now(),
            ]);
        });

        // evento para registrar la actualización
        static::updated(function ($medico) {
            $usuario = User::find($medico->id_usuario);
            Bitacora::create([
                'id_usuario' => Auth::id(),
                'name_usuario' => Auth::user()->name,
                'accion' => 'actualizacion',
                'tabla_afectada' => 'Medicos',
                'detalles' => "Se actualizó medico: {$medico->nombre} (Usuario: " . ($usuario ? $usuario->name : 'Desconocido') . ")",
                'fecha_hora' => now(),
            ]);
        });

        // evento para registrar la eliminación
        static::deleted(function ($medico) {
            $usuario = User::find($medico->id_usuario);
            Bitacora::create([
                'id_usuario' => Auth::id(),
                'name_usuario' => Auth::user()->name,
                'accion' => 'eliminacion',
                'tabla_afectada' => 'Compra',
                'detalles' => "Se elimino medico: {$medico->nombre} (Usuario: " . ($usuario ? $usuario->name : 'Desconocido') . ")",
                'fecha_hora' => now(),
            ]);
        });

    }
        */
}
