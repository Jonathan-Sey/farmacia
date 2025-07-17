<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FichaMedica extends Model
{
    use HasFactory;
    protected $table = 'fichas_medicas';
    protected $fillable = [
        // datos de la persona menor 
        'nombreMenor',
        'apellido_paterno_menor',
        'apellido_materno_menor',
        'persona_id',
        'detalle_medico_id',
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'sexo',
        'fecha_nacimiento',
        'DPI',
        'habla_lengua',
        'antigueno',
        'tipo_sangre',
        'direccion',
        'telefono',
        'foto',
        'departamento_id',
        'municipio_id',
        'diagnostico',
        'consulta_programada',
        'receta_foto',
        'sucursal_id',
    ];
    
    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'departamento_id');
    }

    public function municipio()
    {
        return $this->belongsTo(Municipio::class, 'municipio_id');
    }
    // Relación con el modelo Persona
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id'); // Relación de clave foránea con 'personas'
    }
    public function detalleMedico()
    {
        return $this->belongsTo(DetalleMedico::class, 'detalle_medico_id');
    }

    //   // Accesor para la URL completa de la receta
    //   public function getRecetaUrlAttribute()
    //   {
    //       return $this->receta_foto ? asset('storage/' . $this->receta_foto) : null;
    //   }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_id');
    }

    // Agrega esta relación al modelo FichaMedica
    public function productosRecetados()
    {
        return $this->belongsToMany(Producto::class, 'receta_producto')
                    ->withPivot('cantidad', 'instrucciones')
                    ->withTimestamps();
    }


}
