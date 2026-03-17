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
        Schema::create('bancos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_banco', 100);
            $table->enum('tipo_cuenta', ['ahorros', 'corriente','recaudadora'])->default('corriente');
            $table->string('moneda', 100);
            $table->string('numero_cuenta', 100);
            $table->string('codigo_interbancario', 100);
            $table->string('representante', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bancos');
    }
};
