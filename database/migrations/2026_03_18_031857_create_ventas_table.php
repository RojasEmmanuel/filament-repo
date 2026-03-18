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
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->foreignId('plan_financiamiento_id')->constrained('planes_financiamiento')->restrictOnDelete();
            $table->foreignId('fraccionamiento_id')->constrained()->restrictOnDelete();


            $table->string('folio')->unique();
            
            $table->enum('tipo_venta', ['contado', 'financiamiento']);
            $table->date('fecha_venta');

            $table->decimal('subtotal', 12, 2);
            $table->decimal('descuento', 12, 2)->default(0);
            $table->decimal('total', 12, 2);

            $table->decimal('enganche_aplicado',12,2)->default(0);
            $table->decimal('saldo_restante',12,2)->default(0);

            $table->string('comprobante_pago')->nullable();
            $table->enum('metodo_pago', ['efectivo', 'tarjeta', 'transferencia','otro'])->nullable();

            $table->enum('estatus', ['pendiente', 'aprobada', 'cancelada'])->default('pendiente');
            
            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
