<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Almacen extends Model
{
    use HasFactory;
    protected $table = 'almacen';
    protected $fillable = [
        'id_sucursal',
        'id_producto',
        'cantidad',
        'id_user',
        'estado',
    ];

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class,'id_sucursal');
    }
    public function poducto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
