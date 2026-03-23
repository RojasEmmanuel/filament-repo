<?php

namespace App\Services;

use PhpOffice\PhpWord\TemplateProcessor;
use App\Models\Ventas;
use App\Models\Plantilla;

class GenerarReciboVentaService
{
    public function generar(Ventas $venta): string
    {
        $plantilla = Plantilla::where('fraccionamiento_id', $venta->fraccionamiento_id)->firstOrFail();

        $templatePath = storage_path('app/public/' . $plantilla->ruta);

        $template = new TemplateProcessor($templatePath);

        $cliente = $venta->cliente;
        $fracc = $venta->fraccionamiento;


        $lotesTexto = $venta->ventaLotes
            ->map(function ($ventaLote) {
                $lote = $ventaLote->lote; // relación real

                return "Manzana {$lote->manzana} - Lote {$lote->lote} con las siguientes medidas: norte: {$lote->norte}, al sur: {$lote->sur}, al este:  {$lote->este} y al oeste: {$lote->oeste} y con un área de {$lote->area}";
            })
            ->implode("\n");

        if ($fracc->imagen) {

            $logoPath = storage_path('app/public/' . $fracc->imagen);

            if (file_exists($logoPath)) {
                $template->setImageValue('logo', [
                    'path' => $logoPath,
                    'width' => 120,
                    'height' => 120,
                ]);
            }
        }
        
        $template->setValue('titulo', $plantilla->nombre);

        $template->setValue('fraccionamiento', $fracc->nombre);
        $template->setValue('ubicacion', $fracc->ubicacion);
        $template->setValue('folio', $venta->folio ?? $venta->id);
        $template->setValue('fecha', $venta->fecha_venta);

        $template->setValue('cliente', $cliente->nombre . ' ' . $cliente->apellidos);
        $template->setValue('telefono', $cliente->telefono);
        $template->setValue('rfc', $cliente->rfc ?? 'N/A');

        $template->setValue('tipo_venta', $venta->tipo_venta);
        $template->setValue('plan', $venta->planFinanciamiento->nombre ?? 'N/A');

        $template->setValue('lotes', $lotesTexto);

        $template->setValue('subtotal', number_format($venta->subtotal, 2));
        $template->setValue('descuento', number_format($venta->descuento, 2));
        $template->setValue('total', number_format($venta->total, 2));

        $template->setValue('enganche', number_format($venta->enganche_aplicado, 2));
        $template->setValue('saldo', number_format($venta->saldo_restante, 2));

        $template->setValue('metodo_pago', $venta->metodo_pago);
        $template->setValue('observaciones', $venta->observaciones ?? 'Ninguna');

        $template->setValue('vendedor', $venta->user->name ?? 'Sistema');

        $fileName = "recibo_venta_{$venta->id}.docx";
        $path = storage_path("app/public/$fileName");

        $template->saveAs($path);

        return $path;
    }
}