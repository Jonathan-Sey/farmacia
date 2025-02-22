<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bitacoras', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_usuario');
            $table->string('name_usuario');
            $table->string('accion'); // 'creacion', 'actualizacion', 'eliminacion'
            $table->string('tabla_afectada')->nullable(); // Opcional: tabla donde se realizó la acción
            $table->text('detalles')->nullable(); // Opcional: detalles adicionales de la acción
            $table->timestamp('fecha_hora');
            $table->timestamps();

            $table->foreign('id_usuario')->references('id')->on('users'); // Clave foránea a la tabla de usuarios
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bitacoras');
    }
};