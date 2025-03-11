<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('fichas_medicas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_persona')->constrained('persona')->onDelete('cascade'); // Paciente
            $table->foreignId('id_medico')->constrained('detalle_medico')->onDelete('cascade'); // Médico
            $table->integer('edad');
            $table->float('peso'); // kg
            $table->float('altura'); // metros
            $table->string('presion_arterial')->nullable();
            $table->text('sintomas')->nullable();
            $table->text('diagnostico')->nullable();
            $table->text('tratamiento')->nullable();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('fichas_medicas');
    }
};

