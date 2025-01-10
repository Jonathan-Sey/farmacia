<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
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
        'rol',
        'password',
        'id_rol',
       
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

    public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims() {
        return [
            'rol' => $this->rol,
            'name' => $this->name,
            'email' => $this->email,
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

}
