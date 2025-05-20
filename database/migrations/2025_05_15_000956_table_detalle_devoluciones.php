<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TableDetalleDevoluciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devoluciones_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('producto')->onDelete('cascade');
            $table->integer("cantidad");
            $table->decimal('precio', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->boolean('estado')->default(false);
            $table->foreignId('devolucion_id')->constrained('devoluciones')->onDelete('cascade');
            $table->date('fecha_caducidad')->nullable();
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
        //
    }
}
