<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequisicionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requisicion', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_sucursal_origen');
            $table->unsignedBigInteger('id_sucursal_destino');
            $table->unsignedBigInteger('id_producto');
            $table->unsignedBigInteger('id_lote');
            $table->integer('cantidad');
            $table->dateTime('fecha_traslado');
            $table->unsignedBigInteger('id_usuario');
            $table->timestamps();

            $table->foreign('id_sucursal_origen')->references('id')->on('sucursal');
            $table->foreign('id_sucursal_destino')->references('id')->on('sucursal');
            $table->foreign('id_producto')->references('id')->on('producto');
            $table->foreign('id_lote')->references('id')->on('lote');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requisicion');
    }
}
