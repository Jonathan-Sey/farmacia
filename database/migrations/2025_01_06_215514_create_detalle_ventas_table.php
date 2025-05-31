<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_venta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_venta')->constrained('venta');
            $table->foreignId('id_producto')->constrained('producto');
            $table->integer('cantidad');
            $table->decimal('precio', 10, 2);
            // nuevos campos dado para la justificacion y despueto
            $table->decimal('precio_original', 10, 2); // Nuevo campo
            $table->text('justificacion_descuento')->nullable(); // Nuevo campo
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
        Schema::dropIfExists('detalle_venta');
    }
}
