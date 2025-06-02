<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlmacenVencidos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('almacen_vencidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_sucursal')->constrained('sucursal');
            $table->foreignId('id_producto')->constrained('producto');
            $table->foreignId('id_user')->constrained('users');
            $table->integer('cantidad');
            $table->date('fecha_vencimiento')->nullable();
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
        Schema::dropIfExists('almacen_vencidos');
    }
}
