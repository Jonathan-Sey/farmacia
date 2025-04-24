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
        'imagen',
        'descripcion',
        'ultimo_precio_compra',
        'precio_venta',
        'precio_porcentaje',
        'fecha_caducidad',
        'id_categoria',
        'tipo',
        'estado',
    ];

    public function scopeActivos($query)
    {
       return $query->whereNotIn('estado', [0, 2]);
    }
    //relacion a la tabla del historico de precios.
    public function historicoPrecios()
    {
        return $this->hasMany(HistoricoPrecio::class, 'id_producto');
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

    public function ventas()
{
    return $this->belongsToMany(Venta::class, 'detalle_venta', 'id_producto', 'id_venta')
                ->withPivot('cantidad', 'precio')
                ->withTimestamps();
}

/*
 //Funcion que se ejecutara cada vez para hacer el redondeo en el precio
public function setPrecioVentaAttribute($value)
{
        $this->attributes['precio_venta'] = round($value * 10) / 10;
}

//Funcion que se ejecutara cada vez para hacer el redondeo en el precio
 public function setPrecioPorcentajeAttribute($value)
{
        $this->attributes['precio_porcentaje'] = round($value * 10) / 10;
} */
  // Relación con la tabla lote
  public function lotes()
  {
      return $this->hasMany(Lote::class, 'id_producto');
  }

  // Relación con la tabla inventario
  public function inventarios()
  {
      return $this->hasMany(Inventario::class, 'id_producto');
  }


}
