<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEncuestasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('encuestas', function (Blueprint $table) {
                $table->id();
                $table->foreignId('medico_id')->constrained('detalle_medico');
                $table->string('titulo');
                $table->text('descripcion')->nullable();
                $table->boolean('activa')->default(true);
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
        Schema::dropIfExists('encuestas');
    }
}
