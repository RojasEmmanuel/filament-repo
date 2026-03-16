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
        Schema::create('lotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fraccionamiento_id')->constrained()->cascadeOnDelete();
            $table->string('manzana');
            $table->string('lote');

            $table->decimal('area',10,2);
            $table->decimal('norte',10,2)->nullable();
            $table->decimal('sur',10,2)->nullable();
            $table->decimal('este',10,2)->nullable();
            $table->decimal('oeste',10,2)->nullable();

            $table->decimal('precio',10,2);
            $table->enum('estatus',[
                'disponible',
                'vendido',
                'liquidado'
            ]);

            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lotes');
    }
};
