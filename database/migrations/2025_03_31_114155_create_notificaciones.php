<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificaciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();
            $table->string('tipo'); // Tipo de notificación (ej. "vencido", "alerta_stock")
            $table->string('mensaje'); // Mensaje de la notificación
            $table->string('accion'); // Acción a realizar (ej. "ver_producto", "ver_requisicion")
            $table->string('url'); // URL a la que redirige la notificación
            $table->boolean('leido')->default(false); // Estado de lectura de la notificación
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
        Schema::dropIfExists('notificaciones');
    }
}
