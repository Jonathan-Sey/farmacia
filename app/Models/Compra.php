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
        'estado',

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

        // App\Models\Compra.php
    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'detalle_compra', 'id_compra', 'id_producto')
                    ->withPivot('cantidad', 'precio'); // Incluye los campos adicionales de la tabla intermedia
    }



}
