<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Actions\SelectAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Grid as ComponentsGrid;
use Filament\Schemas\Components\Section as ComponentsSection;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Spatie\Permission\Models\Role;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Avatar Section
                ComponentsSection::make('Foto de Perfil')
                    ->description('La imagen se mostrará como avatar del usuario')
                    ->icon('heroicon-m-camera')
                    ->schema([
                        FileUpload::make('avatar')
                            ->label('')
                            ->image()
                            ->avatar()
                            ->imageEditor()
                            ->imageEditorAspectRatios(['1:1'])
                            ->circleCropper()
                            ->directory('avatars')
                            ->disk('public')  // ← AÑADE ESTA LÍNEA
                            ->visibility('public')  // ← Y ESTA
                            ->maxSize(2048)
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('1:1')
                            ->imageResizeTargetWidth(200)
                            ->imageResizeTargetHeight(200)
                            ->helperText('Sube una imagen cuadrada para mejor resultado. Máx 2MB.'),
                    ])
                    ->collapsible(false)
                    ->compact(),

                // Información Personal Section
                ComponentsSection::make('Información Personal')
                    ->description('Datos básicos del usuario')
                    ->icon('heroicon-m-user')
                    ->schema([
                        ComponentsGrid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nombre completo')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Ej: Juan Pérez López')
                                    ->prefixIcon('heroicon-m-user')
                                    ->autocomplete('name')
                                    ->autofocus(),

                                TextInput::make('email')
                                    ->label('Correo electrónico')
                                    ->email()
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->placeholder('ejemplo@correo.com')
                                    ->prefixIcon('heroicon-m-envelope')
                                    ->helperText('Este será su usuario de acceso'),

                                TextInput::make('telefono')
                                    ->label('Teléfono')
                                    ->tel()
                                    ->required()
                                    ->maxLength(20)
                                    ->placeholder('Ej: 55 1234 5678')
                                    ->prefixIcon('heroicon-m-phone')
                                    ->mask('9999 9999 9999')
                                    ->helperText('Número de contacto principal'),
                            ]),
                    ])
                    ->collapsible(false),

                // Acceso y Seguridad Section - CORREGIDO
                ComponentsSection::make('Acceso y Seguridad')
                    ->description('Configuración de acceso al sistema')
                    ->icon('heroicon-m-lock-closed')
                    ->schema([
                        ComponentsGrid::make(2)
                            ->schema([
                                TextInput::make('password')
                                    ->label('Nueva contraseña')
                                    ->password()
                                    ->revealable()
                                    ->minLength(8)
                                    ->same('password_confirmation')
                                    ->helperText(fn (string $operation) => 
                                        $operation === 'create' 
                                            ? 'Mínimo 8 caracteres' 
                                            : 'Dejar en blanco para mantener la contraseña actual'
                                    )
                                    ->prefixIcon('heroicon-m-key')
                                    ->confirmed()
                                    ->dehydrated(fn ($state) => filled($state)) // Solo se envía si tiene valor
                                    ->required(fn (string $operation) => $operation === 'create'), // Requerido solo en creación

                                TextInput::make('password_confirmation')
                                    ->label('Confirmar nueva contraseña')
                                    ->password()
                                    ->revealable()
                                    ->prefixIcon('heroicon-m-key')
                                    ->dehydrated(false) // Nunca se envía a la BD
                                    ->required(fn ($get) => filled($get('password'))), // Requerido solo si password tiene valor
                            ]),

                        
                    ])
                    ->collapsible(true)
                    ->collapsed(fn (string $operation) => $operation === 'edit'),

                // Roles y Permisos Section
                ComponentsSection::make('Roles y Permisos')
                    ->description('Asignación de roles en el sistema')
                    ->icon('heroicon-m-shield-check')
                    ->schema([
                        ComponentsGrid::make(2)
                            ->schema([
                                Select::make('roles')
                                    ->label('Roles del usuario')
                                    ->relationship('roles', 'name')
                                    ->multiple()
                                    ->preload()
                                    ->searchable()
                                    ->required()
                                    ->native(false)
                                    ->options(Role::all()->pluck('name', 'id'))
                                    ->helperText('Selecciona uno o más roles para el usuario')
                                    ->prefixIcon(Heroicon::OutlinedShieldCheck)
                                    ->placeholder('Seleccionar roles')
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->collapsible(true),

                // Metadatos Section (solo visible en edición)
                ComponentsSection::make('Metadatos')
                    ->description('Información del sistema')
                    ->icon('heroicon-m-information-circle')
                    ->schema([
                        ComponentsGrid::make(2)
                            ->schema([
                                Placeholder::make('created_at')
                                    ->label('Creado')
                                    ->content(fn ($record) => $record?->created_at?->format('d/m/Y H:i') ?? '-')
                                    ->visible(fn ($record) => $record !== null),

                                Placeholder::make('updated_at')
                                    ->label('Última actualización')
                                    ->content(fn ($record) => $record?->updated_at?->format('d/m/Y H:i') ?? '-')
                                    ->visible(fn ($record) => $record !== null),
                            ]),
                    ])
                    ->collapsible()
                    ->collapsed()
                    ->visible(fn (string $operation) => $operation === 'edit'),
            ])
            ->columns(1);
    }
}