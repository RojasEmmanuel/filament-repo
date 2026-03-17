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
        Schema::create('planes_financiamiento', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->enum('frecuencia_pago', ['semanal','quincenal','mensual','bimestral', 'trimestral', 'semestral', 'anual'])->default('mensual');
            $table->enum('tipo_enganche', ['fijo', 'minimo'])->default('fijo');
            $table->enum('modo_enganche', ['porcentaje', 'monto'])->default('porcentaje');
            $table->decimal('enganche', 10, 2)->default(0)->comment('Si el tipo es fijo, se toma el valor como monto. Si el tipo es mínimo, se toma como porcentaje del total a financiar');

            $table->integer('plazo_pagos')->comment('Número de períodos de pago segun la frecuencia de pago');

            $table->enum('tipo_interes', ['porcentaje', 'fijo'])->default('porcentaje');
            $table->decimal('valor_interes', 10, 2)->default(0)->comment('Si es porcentaje: % | Si es fijo: monto');
            $table->enum('periodo_interes',['mensual','anual'])->default('mensual')->comment('Periodo al que se aplica el interés, solo si el tipo de interés es porcentaje');
            
            $table->enum('tipo_penalizacion', ['porcentaje','fijo'])->default('porcentaje');
            $table->enum('aplicacion_penalizacion', ['unica', 'diaria'])->default('unica');
            $table->decimal('penalizacion', 10, 2)->default(0)->comment('Si es porcentaje: % | Si es fijo: monto');

            $table->integer('dias_gracia')->default(0)->comment('Número de días después de la fecha de pago en los que no se aplican penalizaciones por retraso');
            $table->boolean('activo')->default(true)->index();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_financiamientos');
    }
};
