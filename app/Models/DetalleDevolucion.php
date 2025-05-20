<?php

namespace App\Models;

use App\Http\Controllers\devoluciones\devolucionesController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleDevolucion extends Model
{
    use HasFactory;
     protected $table = 'devoluciones_detalles';
    protected $fillable = [
        'producto_id',
        'cantidad',
        'precio',
        'subtotal',
        'estado',
        'devolucion_id ',
        'fecha_caducidad',

    ];


    
    public function producto(){
        return $this->belongsTo(Producto::class,  'producto_id');
    }

   
}
