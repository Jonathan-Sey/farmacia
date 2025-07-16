<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFichasMedicasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fichas_medicas', function (Blueprint $table) {
            $table->id(); // Campo ID para la tabla 'fichas_medicas'
            $table->unsignedBigInteger('persona_id'); // Clave foránea a la tabla 'personas'
            //campos par el menor de edad
            $table->string('nombreMenor')->nullable();
            $table->string('apellido_paterno_menor')->nullable();
            $table->string('apellido_materno_menor')->nullable();
            $table->string('nombre')->nullable();
            $table->string('apellido_paterno')->nullable();
            $table->string('apellido_materno')->nullable();
            $table->enum('sexo', ['Hombre', 'Mujer'])->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('DPI')->nullable();
            $table->enum('habla_lengua', ['Sí', 'No'])->default('No')->nullable();
            $table->enum('tipo_sangre', ['O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-'])->nullable();
            $table->string('direccion')->nullable();
            $table->string('telefono')->nullable();
            $table->string('foto')->nullable();
            $table->foreignId('departamento_id')->constrained('departamentos');
            $table->foreignId('municipio_id')->constrained('municipios');

            
            // Relación con la tabla 'personas'
            $table->foreign('persona_id')->references('id')->on('persona')->onDelete('cascade');
            
            // Nuevos campos
            $table->text('diagnostico')->nullable(); // Diagnóstico
            $table->date('consulta_programada')->nullable(); // Fecha de consulta programada
            $table->string('receta_foto')->nullable(); // Foto de receta médica
            
            $table->timestamps(); // Campos created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fichas_medicas');
    }
}
