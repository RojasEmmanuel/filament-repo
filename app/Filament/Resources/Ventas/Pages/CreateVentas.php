<?php

namespace App\Filament\Resources\Ventas\Pages;

use App\Filament\Resources\Ventas\VentasResource;
use App\Models\Lotes;
use App\Models\PlanFinanciamiento;
use App\Models\VentaLotes;
use Filament\Resources\Pages\CreateRecord;

class CreateVentas extends CreateRecord
{
    protected static string $resource = VentasResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $lotes = Lotes::whereIn('id', $this->data['lotes'] ?? [])->get();

        $subtotal = $lotes->sum('precio');

        $descuento = $data['descuento'] ?? 0;

        $total = $subtotal - $descuento;

        $plan = PlanFinanciamiento::find($data['plan_financiamiento_id']);

        $enganche = 0;

        if ($plan) {
            $enganche = $plan->modo_enganche === 'porcentaje'
                ? ($total * $plan->enganche) / 100
                : $plan->enganche;
        }

        $data['subtotal'] = $subtotal;
        $data['total'] = $total;
        $data['enganche_aplicado'] = $enganche;
        $data['saldo_restante'] = $total - $enganche;

        // 🔹 Otros campos
        $data['folio'] = 'VTA-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        $data['tipo_venta'] = 'financiamiento';
        $data['estatus'] = 'pendiente';

        return $data;
    }

    protected function afterCreate(): void
    {
        $venta = $this->record;

        $lotes = $this->data['lotes'] ?? [];

        foreach ($lotes as $loteId) {
            $lote = Lotes::find($loteId);

            VentaLotes::create([
                'venta_id' => $venta->id,
                'lote_id' => $lote->id,
                'precio_lote' => $lote->precio,
                'descuento_lote' => 0,
                'total_lote' => $lote->precio,
            ]);

            $lote->update([
                'estatus' => 'vendido'
            ]);
        }
    }
}


