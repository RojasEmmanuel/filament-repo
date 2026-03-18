<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;


class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function afterCreate(): void
    {
        $user = $this->record;
        $userActive = Auth::user();

        Notification::make()
            ->title('Usuario creado')
            ->body("El usuario {$user->name} fue registrado exitosamente")
            ->success()
            ->sendToDatabase($userActive);
        
    }
}
