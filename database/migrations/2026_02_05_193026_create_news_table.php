<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('news', function (Blueprint $table) {
        $table->id();
        
        // CORRECCIÓN: Usamos nombres en Inglés para coincidir con tu código
        $table->string('title');       // Antes era 'titulo'
        $table->text('content');       // Antes era 'contenido'
        
        // NUEVO: Agregamos la columna para el video que faltaba
        $table->string('video_url')->nullable(); 
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
