<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lote', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('id_producto');
            $table->string('numero_lote', 50);
            $table->date('fecha_vencimiento');
            $table->integer('cantidad');
            $table->decimal('precio_compra', 10, 2); // Nuevo campo
            $table->unsignedBigInteger('id_compra');
            //$table->tinyInteger('estado')->default(1);
            $table->timestamps();
            $table->foreign('id_producto')->references('id')->on('producto');  // Corregido para referenciar `id` en `producto`
            $table->foreign('id_compra')->references('id')->on('compra');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lote');
    }
}
