<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FichaMedica extends Model
{
    use HasFactory;
    protected $table = 'fichas_medicas';
    protected $fillable = [
        'persona_id',
        'detalle_medico_id',
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'sexo',
        'fecha_nacimiento',
        'DPI',
        'habla_lengua',
        'tipo_sangre',
        'direccion',
        'telefono',
        'foto',
        'departamento_id',
        'municipio_id',
        'diagnostico',
        'consulta_programada',
        'receta_foto',
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

}
