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
    Schema::create('site_configs', function (Blueprint $table) {
        $table->id();
        $table->string('titulo_sitio')->nullable();
        $table->string('color_principal')->default('#000000');
        
        // --- AGREGA ESTAS DOS LÍNEAS ---
        $table->string('logo_path')->nullable(); // Para la ruta de la imagen
        $table->text('footer_html')->nullable(); // Para el texto largo del pie de página
        // -------------------------------
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_configs');
    }
};
