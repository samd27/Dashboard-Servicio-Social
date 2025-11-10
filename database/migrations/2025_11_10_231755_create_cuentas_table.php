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
        // Crea la tabla 'cuentas' según el RF04 y el ERD
        Schema::create('cuentas', function (Blueprint $table) {
            $table->id(); // bigint(20) unsigned auto-increment
            $table->string('nombre_cuenta');
            $table->string('rfc')->unique();
            $table->string('telefono');
            $table->string('email_2_opcional')->nullable();
            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cuentas');
    }
};