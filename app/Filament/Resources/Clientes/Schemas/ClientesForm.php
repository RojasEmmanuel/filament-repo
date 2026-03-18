<?php

namespace App\Filament\Resources\Clientes\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Carbon\Carbon;
use Filament\Schemas\Components\Tabs;
use Livewire\Features\SupportSlots\PlaceholderSlot;

class ClientesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Registro de Clientes')
                    ->tabs([
                        // TAB 1: INFORMACIÓN PERSONAL BÁSICA
                        Tabs\Tab::make('Información Personal')
                            ->icon('heroicon-o-user')
                            ->schema([
                                TextInput::make('nombre')
                                    ->required()
                                    ->placeholder('Ej: Juan')
                                    ->maxLength(255),
                                    
                                TextInput::make('apellidos')
                                    ->required()
                                    ->placeholder('Ej: Pérez García')
                                    ->maxLength(255),
                                    
                                DatePicker::make('fecha_nacimiento')
                                    ->label('Fecha de Nacimiento')
                                    ->maxDate(Carbon::now())
                                    ->displayFormat('d/m/Y')
                                    ->closeOnDateSelection()
                                    ->native(false)
                                    ->required()
                                    ->helperText('Formato: DD/MM/AAAA'),
                                    
                                Select::make('estado_civil')
                                    ->label('Estado Civil')
                                    ->options([
                                        'soltero' => 'Soltero(a)',
                                        'casado' => 'Casado(a)',
                                        'otro' => 'Otro'
                                    ])
                                    ->default('soltero')
                                    ->native(false)
                                    ->placeholder('Selecciona el estado civil'),
                            ]),

                        // TAB 2: TIPO Y CLASIFICACIÓN
                        Tabs\Tab::make('Clasificación')
                            ->icon('heroicon-o-tag')
                            ->schema([
                                Select::make('tipo')
                                    ->label('Tipo de Registro')
                                    ->options([
                                        'cliente' => 'Cliente',
                                        'prospecto' => 'Prospecto',
                                    ])
                                    ->required()
                                    ->default('prospecto')
                                    ->native(false)
                                    ->helperText('Cliente: ya ha realizado compras | Prospecto: en seguimiento'),
                                    
                                TextInput::make('ocupacion')
                                    ->label('Ocupación / Profesión')
                                    ->placeholder('Ej: Ingeniero, Comerciante, etc.')
                                    ->maxLength(255),
                            ]),

                        // TAB 3: CONTACTO
                        Tabs\Tab::make('Contacto')
                            ->icon('heroicon-o-phone')
                            ->schema([
                                TextInput::make('telefono')
                                    ->label('Número de Teléfono')
                                    ->tel()
                                    ->placeholder('Ej: 55 1234 5678')
                                    ->helperText('Incluir clave de área si es necesario'),
                                    
                                TextInput::make('ciudad')
                                    ->label('Ciudad')
                                    ->required()
                                    ->placeholder('Ej: Ciudad de México, Guadalajara, etc.')
                                    ->maxLength(255),
                            ]),

                        // TAB 4: DOCUMENTACIÓN FISCAL
                        Tabs\Tab::make('Documentación Fiscal')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                TextInput::make('curp')
                                    ->label('CURP')
                                    ->length(18)
                                    ->placeholder('18 caracteres')
                                    ->helperText('Clave Única de Registro de Población (18 caracteres)')
                                    ->rules(['regex:/^[A-Z]{4}\d{6}[H,M][A-Z]{5}[A-Z0-9]{2}$/'])
                                    ->validationMessages([
                                        'regex' => 'El formato de CURP no es válido',
                                    ]),
                                    
                                TextInput::make('rfc')
                                    ->label('RFC')
                                    ->minLength(5)
                                    ->maxLength(13)
                                    ->placeholder('Ej: XXX000101XXX')
                                    ->helperText('Registro Federal de Contribuyentes')
                                    ->rules(['regex:/^[A-Z]{3,4}\d{6}[A-Z0-9]{3}$/'])
                                    ->validationMessages([
                                        'regex' => 'El formato de RFC no es válido',
                                    ]),
                            ]),

                        
                    ])->persistTabInQueryString() // Opcional: guarda la pestaña activa en la URL
                    ->columnSpanFull()
                    
            ]);
    }
}