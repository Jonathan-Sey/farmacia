<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToDetalleMedicoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Verificar si la columna 'horarios' no existe
        if (!Schema::hasColumn('detalle_medico', 'horarios')) {
            Schema::table('detalle_medico', function (Blueprint $table) {
                $table->json('horarios')->nullable()->after('estado');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Eliminar la columna 'horarios' si existe
        if (Schema::hasColumn('detalle_medico', 'horarios')) {
            Schema::table('detalle_medico', function (Blueprint $table) {
                $table->dropColumn('horarios');
            });
        }
    }
}

    

