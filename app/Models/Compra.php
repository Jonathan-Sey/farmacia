<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;

    protected $table = 'compra';
    protected $fillable = [
        'numero_compra',
        'id_proveedor',
        'fecha_compra',
        'id_usuario',
        'comprobante',
        'impuesto',
        'total',

    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'id_proveedor');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function detalleCompras()
    {
        return $this->hasMany(DetalleCompra::class, 'id_compra');
    }


}
