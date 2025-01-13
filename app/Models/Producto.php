<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;
    protected $table = 'producto';
    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'precio_venta',
        'fecha_caducidad',
        'id_categoria',
        'tipo',
        'estado',

    ];

    public function scopeActivos($query)
    {
       return $query->whereNotIn('estado', [0, 2]);
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class,'id_categoria');
    }
    public function almacen()
    {
        return $this->hasMany(Almacen::class,'id_producto');
    }
    public function detalleVenta()
    {
        return $this->hasMany(DetalleVenta::class, 'id_producto');
    }

    public function detalleCompra()
    {
        return $this->hasMany(DetalleCompra::class, 'id_producto');
    }

        // App\Models\Producto.php
    public function compras()
    {
        return $this->belongsToMany(Compra::class, 'detalle_compra', 'id_producto', 'id_compra')
                    ->withPivot('cantidad', 'precio');
    }

    //     public function venta()
    // {
    //     return $this->belongsTo(Venta::class, 'id_venta');
    // }

    // public function producto()
    // {
    //     return $this->belongsTo(Producto::class, 'id_producto');
    // }


}
