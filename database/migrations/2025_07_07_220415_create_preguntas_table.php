<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreguntasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('preguntas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('encuesta_id')->constrained()->onDelete('cascade');
            $table->text('texto_pregunta');
            $table->enum('tipo', ['escala', 'cerrado', 'texto']);
            //$table->json('opciones')->nullable(); // Para preguntas de opción múltiple
            $table->integer('orden')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('preguntas');
    }
}
