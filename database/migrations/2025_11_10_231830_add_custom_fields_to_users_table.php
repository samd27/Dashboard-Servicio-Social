<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            
            // 1. Llave foránea para conectar con 'cuentas' 
            // La ponemos después del 'id' para orden
            $table->foreignId('cuenta_id')
                  ->after('id')
                  ->constrained('cuentas') 
                  ->onDelete('cascade');

            // 2. Campos personalizados
            // Los ponemos después del 'remember_token' que ya existe
            $table->string('api_key', 32)->unique()->nullable()->after('remember_token');
            $table->tinyInteger('nivel_usuario')->default(1)->after('api_key');
            $table->boolean('vigente')->default(true)->after('nivel_usuario');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('cuenta_id');
            $table->dropColumn(['api_key', 'nivel_usuario', 'vigente']);
        });
    }
};