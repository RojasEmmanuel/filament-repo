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
        Schema::create('banco_fraccionamiento', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bancos_id')->constrained('bancos')->cascadeOnDelete();
            $table->foreignId('fraccionamiento_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banco_fraccionamiento');
    }
};
