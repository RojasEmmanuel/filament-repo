<?php

namespace App\Filament\Resources\Fraccionamientos\Pages;

use App\Filament\Resources\Fraccionamientos\FraccionamientoResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateFraccionamiento extends CreateRecord
{
    protected static string $resource = FraccionamientoResource::class;

    protected function afterCreate(): void
    {
        $fraccionamiento = $this->record;

        $user = Auth::user();

        Notification::make()
            ->title('Fraccionamiento creado')
            ->body("El fraccionamiento {$fraccionamiento->nombre} fue registrado exitosamente")
            ->success()
            ->sendToDatabase($user);
    }
}
