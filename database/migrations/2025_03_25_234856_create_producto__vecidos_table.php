<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductoVecidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('producto__vecidos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_vencimiento');
            $table->integer('cantidad');
            $table->unsignedBigInteger('id_compra');
            $table->unsignedBigInteger('id_producto');
            $table->foreign('id_producto')->references('id')->on('producto');  // Corregido para referenciar `id` en `producto`
            $table->foreign('id_compra')->references('id')->on('compra');

            
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
        Schema::dropIfExists('producto__vecidos');
    }
}
