<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('venta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_sucursal')->constrained('sucursal');
            $table->date('fecha_venta');
            $table->decimal('impuesto', 10, 2);
            $table->decimal('total', 10, 2);
            $table->foreignId('id_usuario')->constrained('users');
            $table->foreignId('id_consulta')->nullable()->constrained('consulta');
            $table->foreignId('id_persona')->constrained('persona');
            $table->tinyInteger('estado')->default(1);
            $table->boolean('es_prescrito')->default(false);
            $table->string('imagen_receta')->nullable();
            $table->string('numero_reserva')->nullable();

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
        Schema::dropIfExists('venta');
    }
}
