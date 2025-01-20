<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePestañasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('pestanas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Nombre visible de la pestaña
            $table->string('slug')->unique(); // Identificador único para la lógica
            $table->string('ruta')->default('default_value'); // Ruta asociada a la pestaña
            $table->string('icono')->nullable(); // Clases CSS del ícono (opcional)
            $table->text('descripcion')->nullable(); // Descripción adicional (opcional)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pestanas');
    }
}

