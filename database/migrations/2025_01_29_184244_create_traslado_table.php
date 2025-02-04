<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrasladoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('traslado', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_sucursal_origen')->constrained('sucursal');
            $table->foreignId('id_sucursal_destino')->constrained('sucursal');
            $table->foreignId('id_producto')->constrained('producto');
            $table->integer('cantidad');
            $table->foreignId('id_user')->constrained('users');
            $table->string('estado');
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
        Schema::dropIfExists('traslado');
    }
}
