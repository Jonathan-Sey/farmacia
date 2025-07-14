<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolicitudDevolucionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitudes_devolucion', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('venta_id');
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('persona_id');
            $table->unsignedBigInteger('sucursal_id');
            $table->decimal('total', 10, 2);
            $table->string('motivo');
            $table->text('observaciones')->nullable();
            $table->json('detalles'); // guarda los productos como JSON
            $table->date('fecha_solicitud')->default(now());
            $table->date('fecha_vencimiento')->nullable();
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
        Schema::dropIfExists('solicitud_devolucions');
    }
}
