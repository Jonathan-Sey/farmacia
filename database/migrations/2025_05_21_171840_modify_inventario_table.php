<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyInventarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventario', function (Blueprint $table) {
        // eliminamos la relación existente con sucursal
        $table->dropForeign(['id_sucursal']);
        // cambiamos el nombre a id_bodega
        $table->renameColumn('id_sucursal', 'id_bodega');
        // completamos una nueva relacion con bodega
        $table->foreign('id_bodega')->references('id')->on('bodegas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventario', function (Blueprint $table) {
            $table->dropForeign(['id_bodega']);
            $table->renameColumn('id_bodega', 'id_sucursal');
            $table->foreign('id_sucursal')->references('id')->on('sucursal');
        });
    }
}
