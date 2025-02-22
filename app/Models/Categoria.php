<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Categoria extends Model
{
    use HasFactory;
    protected $table = 'categoria';
    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
    ];

    public function scopeActivos($query)
    {
       return $query->whereNotIn('estado', [0, 2]);
    }

    public function producto()
        {
            return $this->hasMany(Producto::class, 'id_categoria');
        }


        
        // Bitacora
        /*
    public static function boot()
    {
        parent::boot();

        static::created(function ($categoria) {
            Bitacora::create([
                'id_usuario' => 1,
                'name_usuario' => 'admin',
                'accion' => 'creacion',
                'tabla_afectada' => 'categoria',
                'detalles' => "Se creó la categoria: {$categoria->nombre}", //detalles especificos
                'fecha_hora' => now(),
            ]);
        });

        
      

        // eliminación
        static::deleted(function ($categoria) {
            Bitacora::create([
                'id_usuario' => 1,
                'name_usuario' => 'admin',
                'accion' => 'eliminacion',
                'tabla_afectada' => 'productos',
                'detalles' => "Se eliminó la categoria: {$categoria->nombre}", 
                'fecha_hora' => now(),
            ]);
        });

    }*/
        
}