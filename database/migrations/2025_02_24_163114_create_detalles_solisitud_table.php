<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetallesSolisitudTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalles_solisitud', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solicitud_salida')->constrained('sucursal');
            $table->foreignId('solicitud_entrada')->constrained('sucursal');
            $table->foreignId('producto_id')->constrained('producto');
            $table->foreignId('id_solicitud')->constrained('solicitud_producto');
            $table->integer('cantidad');
            $table->foreignId('Id_usuario')->constrained('users');
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
        Schema::dropIfExists('detalles_solisitud');
    }
}
