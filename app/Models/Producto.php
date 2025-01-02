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
        'estado',
        'id_categoria',

    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class,'id_categoria');
    }
}
