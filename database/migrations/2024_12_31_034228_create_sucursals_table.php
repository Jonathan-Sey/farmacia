<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSucursalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sucursal', function (Blueprint $table) {
            $table->id();
            $table->string('imagen')->nullable();
            $table->string('nombre',35)->uniqued();
            $table->string('codigo_sucursal',50)->unique();
            $table->string('ubicacion', 200);
            // campos para la ubicacion
            $table->decimal('latitud', 10, 7)->nullable(); // aca es x
            $table->decimal('longitud', 10, 7)->nullable();// aca es y
            // desde aca almacenamos la direccion de google
            $table->string('google_maps_link', 500)->nullable();
            $table->string('telefono',10);
            $table->string('email',50);
            $table->string('encargado',100);
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
        Schema::dropIfExists('sucursal');
    }
}
