<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsultasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consulta', function (Blueprint $table) {
            $table->id();
            $table->string('asunto',35);
            $table->date('fecha_consulta');
            $table->date('proxima_cita')->nullable();
            $table->text('detalle',255)->nullable();
            $table->foreignId('id_persona')->constrained('persona');
            $table->foreignId('id_medico')->constrained('detalle_medico');
            $table->tinyInteger('estado')->default(1);
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
        Schema::dropIfExists('consulta');
    }
}
