<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('persona', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',45)->unique();
            $table->string('nit',10)->nullable()->unique();
            $table->tinyInteger('rol')->default(1);
            $table->string('telefono',20)->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->tinyInteger('estado')->default(1);
            // vampos para validar el limite de ventas
            $table->integer('limite_compras')->nullable();
            $table->integer('periodo_control')->nullable();
            $table->boolean('restriccion_activa')->default(false);
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
        Schema::dropIfExists('persona');
    }
}
