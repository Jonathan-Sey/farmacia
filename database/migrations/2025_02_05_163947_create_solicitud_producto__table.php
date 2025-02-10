<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolicitudProductoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitud_producto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_sucursal_origen')->constrained('sucursal');
            $table->foreignId('id_sucursal_destino')->constrained('sucursal');
            $table->foreignId('id_producto')->constrained('producto');
            $table->string('cantidad');
            $table->string('descripcion');
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
        Schema::dropIfExists('solicitud_producto');
    }
}
