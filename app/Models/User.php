<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Contracts\JWTSubject;



class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'id_rol',
        'activo',

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected static function booted()
    {
        static::addGlobalScope('activo', function ($query) {
            $query->where('activo', true);
        });
    }

    public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims() {
    $rol = $this->rol()->with('pestanas')->first();  // Cargar rol y pestanas

    // Verificar si el rol existe y tiene pestañas
    if ($rol && $rol->pestanas) {
        $pestanas = $rol->pestanas->pluck('nombre');
    } else {
        $pestanas = collect();  // Si no tiene pestañas, asignar un array vacío
    }

    return [
        'id' => $this->id,
        'rol' => $rol ? $rol->id : null,  // Devolver el nombre del rol si existe
        'name' => $this->name,
        'email' => $this->email,
        'pestanas' => $pestanas,  // Asignar las pestañas

    ];
    }


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];




    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol');
    }
    public function almacen()
    {
        return $this->hasMany(Almacen::class, 'id_usuario');
    }
    public function consulta()
    {
        return $this->hasMany(Consulta::class, 'id_medico');
    }

    public function compra()
    {


        return $this->hasMany(Compra::class, 'id_usuario');
    }

    public function venta()
    {
        return $this->hasMany(Venta::class, 'id_usuario');

    }

    public function detalleMedico()
    {
        return $this->hasOne(DetalleMedico::class, 'id_usuario');
    }
        // En el modelo User
    public function scopeActivo($query)
    {
        return $query->where('activo', true);
    }

/*Bitacora
public static function boot()
    {
        parent::boot();

        static::created(function ($usuario) {
            Bitacora::create([
               
                'id_usuario' => Auth::id(),
                'name_usuario' => Auth::user()->name,
                'accion' => 'Creación',
                'tabla_afectada' => 'Usuarios',
                'detalles' => "Se creó usuario: {$usuario->nombre} ",

                'fecha_hora' => now(),
            ]);
        });

        // evento para registrar la actualización
        static::updated(function ($usuario) {
            Bitacora::create([
                'id_usuario' => Auth::id(),
                'name_usuario' => Auth::user()->name,
                'accion' => 'Actualización',
                'tabla_afectada' => 'Usuarios',
                'detalles' => "Se actualizó usuario: {$usuario->nombre}", 
                'fecha_hora' => now(),
            ]);
        });

        // evento para registrar la eliminación
        static::deleted(function ($usuario) {
            Bitacora::create([
                'id_usuario' => Auth::id(),
                'name_usuario' => Auth::user()->name,
                'accion' => 'Eliminación',
                'tabla_afectada' => 'Usuarios',
                'detalles' => "Se eliminó usuario: {$usuario->nombre}", 
                'fecha_hora' => now(),
            ]);
        });

    }**/

}
