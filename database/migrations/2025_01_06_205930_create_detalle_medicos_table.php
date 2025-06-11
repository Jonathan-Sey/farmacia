<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleMedicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_medico', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_usuario')->constrained('users');
               $table->unsignedBigInteger('id_especialidad');
            $table->string('numero_colegiado', 10);
            $table->tinyInteger('estado')->default(1);
            $table->timestamps();

             $table->foreign('id_especialidad')->references('id')->on('especialidades');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_medico');
    }
}
