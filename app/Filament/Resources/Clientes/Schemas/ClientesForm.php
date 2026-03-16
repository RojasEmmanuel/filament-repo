<?php

namespace App\Filament\Resources\Clientes\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Carbon\Carbon;

use function Symfony\Component\Clock\now;

class ClientesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->required(),
                TextInput::make('apellidos')
                    ->required(),
                TextInput::make('ciudad')
                    ->required(),
                Select::make('tipo')
                    ->label("tipo de registro")
                    ->options( ['cliente' => 'Cliente','prospecto' => 'Prospecto',])
                    ->required()
                    ->default('prospecto')
                    ->native(false),
                
                TextInput::make('telefono')
                    ->label('Número de teléfono')
                    ->tel(),

                DatePicker::make('fecha_nacimiento')
                    ->label('fecha de nacimiento')
                    ->maxDate(Carbon::now())
                    ->displayFormat('d/m/Y')
                    ->closeOnDateSelection()
                    ->native(false)
                    ->required(),


                TextInput::make('curp')
                    ->length(18),               
                TextInput::make('rfc')
                    ->minLength(5),
                
                TextInput::make('ocupacion'),
        
                Select::make('estado_civil')
                    ->label('estado civil')
                    ->options(['casado'=>'Casado(a)','soltero'=>'Soltero(a)','otro'=>'Otro'])
                    ->default('soltero')
                    ->native(false)
            ]);
    }
}
