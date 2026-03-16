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
        Schema::table('fraccionamientos', function (Blueprint $table) {
            $table->decimal('perimetro',10,2)->nullable();
            $table->decimal('area_total',10,2)->nullable();
            $table->integer('total_manzanas')->nullable();
            $table->integer('total_lotes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fraccionamientos', function (Blueprint $table) {
            //
        });
    }
};
