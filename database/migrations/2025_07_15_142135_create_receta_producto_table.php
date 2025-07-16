<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecetaProductoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receta_producto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ficha_medica_id')->constrained('fichas_medicas')->onDelete('cascade');
            $table->foreignId('producto_id')->constrained('producto')->onDelete('cascade');
            $table->integer('cantidad');
            $table->text('instrucciones');
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
        Schema::dropIfExists('receta_producto');
    }
}
