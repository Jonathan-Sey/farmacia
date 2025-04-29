<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevoluciones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devoluciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venta_id')->constrained('venta')->onDelete('cascade');
            $table->foreignId('producto_id')->constrained('producto')->onDelete('cascade');
            $table->integer('cantidad');
            $table->decimal('monto', 10, 2);
            $table->string('motivo')->nullable();
            $table->boolean('estado')->default(false);
            $table->string('observaciones')->nullable();
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('sucursal_id')->constrained('sucursal')->onDelete('cascade');
            $table->date('fecha_caducidad')->nullable();
            $table->date('fecha_devolucion');
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
        Schema::dropIfExists('devoluciones');
    }
}
