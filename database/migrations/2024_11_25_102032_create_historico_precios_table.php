<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('historico_precios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_producto')->constrained('producto');
            $table->decimal('precio_anterior', 10, 2);
            $table->decimal('precio_nuevo', 10, 2);
            $table->timestamp('fecha_cambio')->useCurrent();
            $table->timestamps();
            
        });
    }

    public function down()
    {
        Schema::dropIfExists('historico_precios');
    }
};
