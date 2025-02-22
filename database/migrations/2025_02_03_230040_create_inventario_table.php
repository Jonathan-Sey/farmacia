<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventario', function (Blueprint $table) {
            $table->bigIncrements('id_inventario');
            $table->foreignId('id_producto')->constrained('producto')->onDelete('cascade');
            $table->foreignId('id_sucursal')->constrained('sucursal')->onDelete('cascade');
            $table->foreignId('id_lote')->constrained('lote')->onDelete('cascade');
            $table->integer('cantidad');
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
        Schema::dropIfExists('inventario');
    }
}
