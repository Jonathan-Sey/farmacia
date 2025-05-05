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
        'id_sucursal',
        'fecha_compra',
        'id_usuario',
        'impuesto',
        'total',
        'estado',
        'imagen_comprobante',
        'observaciones_comprobante'


    ];

    public function scopeActivos($query)
    {
       return $query->whereNotIn('estado', [0, 2]);
    }

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
    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'id_sucursal');
    }

        // App\Models\Compra.php
    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'detalle_compra', 'id_compra', 'id_producto')
                    ->withPivot('cantidad', 'precio'); // Incluye los campos adicionales de la tabla intermedia
    }



}
