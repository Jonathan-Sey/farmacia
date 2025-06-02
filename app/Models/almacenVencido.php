<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class almacenVencido extends Model
{
    use HasFactory;
    protected $table = 'almacen_vencidos';
    protected $fillable = [
        'id_sucursal',
        'id_producto',
        'cantidad',
        'fecha_vencimiento',
        'alerta_stock',
        'id_user',
        'estado',
    ];


    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'id_sucursal');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }


}
