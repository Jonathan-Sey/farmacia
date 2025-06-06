<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TableReporteKardex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reporte_kardex', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('producto')->onDelete('cascade');
            $table->string('nombre_sucursal');
            $table->string('tipo_movimiento');
            $table->integer('cantidad');
            $table->string('Cantidad_anterior');
            $table->string('Cantidad_nueva');
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->dateTime('fecha_movimiento');
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
        Schema::dropIfExists('reporte_kardex');
    }
}
