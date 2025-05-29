<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDetalleMedicoIdToFichaMedica extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fichas_medicas', function (Blueprint $table) {
            // Agregar la columna detalle_medico_id como clave foránea
            $table->foreignId('detalle_medico_id')->nullable()->constrained('detalle_medico')->onDelete('cascade');
        });
    }
    
    public function down()
    {
        Schema::table('fichas_medicas', function (Blueprint $table) {
            // Eliminar la clave foránea en caso de revertir la migración
            $table->dropForeign(['detalle_medico_id']);
            $table->dropColumn('detalle_medico_id');
        });
    }
    
}
