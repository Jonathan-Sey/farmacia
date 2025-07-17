<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receta_producto extends Model
{
    use HasFactory;
    protected $table = 'receta_producto';
    protected $fillable = [
        'fecha_medicina_id',
        'producto_id',
        'cantidad',
        'instrucciones',

    ];


    public function fichasMedicas()
    {
        return $this->belongsTo(fichasMedicas::class, 'fichas_medica_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
