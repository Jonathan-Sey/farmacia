<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class traslado extends Model
{
    use HasFactory;

    protected $table = 'traslado';

    protected $fillable = [
        'id_sucursal_origen',
        'id_sucursal_destino',
        'id_producto',
        'cantidad',
        'id_user',
        'estado',
    ];

    public function scopeActivos($query)
    {
       return $query->whereNotIn('estado', [0, 2]);
    }

    public function sucursal1()
    {
        return $this->belongsTo(Sucursal::class,'id_sucursal_origen');
    }

    public function sucursal2()
    {
        return $this->belongsTo(Sucursal::class,'id_sucursal_destino');
    }
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
<<<<<<< HEAD
/*
    public static function boot()
    {
        parent::boot();
    
        static::created(function ($traslado) {
            Bitacora::create([
                
                'id_usuario' => Auth::id(),
                'name_usuario' => Auth::user()->name,
                'accion' => 'Creación',
                'tabla_afectada' => 'Traslado',
                'detalles' => "Traslado Origen: {$traslado->id_sucursal_origen} Traslado Destino:{$traslado->id_sucursal_destino} ",
    
                'fecha_hora' => now(),
            ]);
        });
    
        // evento para registrar la actualización
        static::updated(function ($traslado) {
            Bitacora::create([
                'id_usuario' => Auth::id(),
                'name_usuario' => Auth::user()->name,
                'accion' => 'Actualización',
                'tabla_afectada' => 'Traslado',
                'detalles' => "Se actualizó traslado:{$traslado->id_sucursal_origen} Traslado Destino:{$traslado->id_sucursal_destino} ", 
                'fecha_hora' => now(),
            ]);
        });
    
        // evento para registrar la eliminación
        static::deleted(function ($traslado) {
            Bitacora::create([
                'id_usuario' => Auth::id(),
                'name_usuario' => Auth::user()->name,
                'accion' => 'Eliminación',
                'tabla_afectada' => 'Traslado',
                'detalles' => "Se eliminó traslado: {$traslado->id_sucursal_origen} Traslado Destino:{$traslado->id_sucursal_destino} ", 
                'fecha_hora' => now(),
            ]);
        });
    
    }
    */
=======



>>>>>>> 5ead12452b8f187d24d25f8c4a9b3741c2793571
}
