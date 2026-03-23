<?php

use App\Models\Ventas;
use App\Services\GenerarReciboVentaService;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/ventas/{venta}/recibo', function (
        Ventas $venta,
        GenerarReciboVentaService $service
    ) {
        $venta->load([
            'cliente',
            'fraccionamiento',
            'ventaLotes',
            'planFinanciamiento',
            'user'
        ]);

        $path = $service->generar($venta);

        return response()->download($path);
    })->name('ventas.recibo');
});