<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComprasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compra', function (Blueprint $table) {
            $table->id();
            $table->string('numero_compra')->uniqid();
            $table->foreignId('id_proveedor')->constrained('proveedor');
            $table->foreignId('id_sucursal')->constrained('sucursal');
            $table->date('fecha_compra');
            $table->foreignId('id_usuario')->constrained('users');
            $table->decimal('impuesto',8,2);
            $table->decimal('total',10,2);
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
        Schema::dropIfExists('compra');
    }
}
