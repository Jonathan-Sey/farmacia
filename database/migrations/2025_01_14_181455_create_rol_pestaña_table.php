<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolPestañaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('rol_pestana', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rol_id') // Llave foránea a la tabla roles
                  ->constrained('rol') // Asume que tu tabla de roles se llama 'rol'
                  ->onDelete('cascade');
            $table->foreignId('pestana_id') // Llave foránea a la tabla pestañas
                  ->constrained('pestanas') 
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rol_pestana');
    }
}
