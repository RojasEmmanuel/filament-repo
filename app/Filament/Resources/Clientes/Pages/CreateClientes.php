<?php

namespace App\Filament\Resources\Clientes\Pages;

use App\Filament\Resources\Clientes\ClientesResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateClientes extends CreateRecord
{
    protected static string $resource = ClientesResource::class;

    protected function afterCreate(): void
    {
        $cliente = $this->record;
        $user = Auth::user();

        Notification::make()
            ->title('Cliente creado')
            ->body("El cliente {$cliente->nombre} fue registrado exitosamente")
            ->success()
            ->sendToDatabase($user);
    }
}
