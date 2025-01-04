<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('producto', function (Blueprint $table) {
            $table->id();
            $table->string('codigo',12)->uniqued();
            $table->string('nombre',50);
            $table->text('descripcion')->nullable();
            $table->decimal('precio_venta',10,2);
            $table->date('fecha_caducidad');
            $table->tinyInteger('estado')->default(1);
            $table->foreignId('id_categoria')->constrained('categoria');

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
        Schema::dropIfExists('producto');
    }
}
