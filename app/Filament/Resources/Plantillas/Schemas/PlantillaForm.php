<?php

namespace App\Filament\Resources\Plantillas\Schemas;

use App\Enums\TipoPlantilla;
use App\Models\Fraccionamiento;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Grid as ComponentsGrid;
use Filament\Schemas\Components\Section as ComponentsSection;
use Filament\Schemas\Schema;

class PlantillaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                ComponentsSection::make('Información General')
                    ->description('Datos básicos de la plantilla')
                    ->icon('heroicon-m-document-text')
                    ->schema([
                        ComponentsGrid::make(2)
                            ->schema([
                                TextInput::make('nombre')
                                    ->label('Nombre de la plantilla')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Ej: Contrato de venta, Carta compromiso, etc.')
                                    ->prefixIcon('heroicon-m-document')
                                    ->autofocus()
                                    ->helperText('Nombre descriptivo de la plantilla'),

                                TextInput::make('clave')
                                    ->label('Clave')
                                    ->required()
                                    ->maxLength(100)
                                    ->placeholder('Ej: CON-VENTA-001')
                                    ->prefixIcon('heroicon-m-key')
                                    ->helperText('Identificador único para la plantilla')
                                    ->unique(ignoreRecord: true),
                            ]),

                            // SELECTOR DE ARCHIVO - CORREGIDO
                        FileUpload::make('ruta')
                            ->label('Archivo de la plantilla')
                            ->required()
                            ->directory('plantillas')
                            ->disk('public')
                            ->visibility('public')
                            ->acceptedFileTypes([
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // .docx
                                'application/msword', // .doc
                                'application/pdf', // .pdf
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // .xlsx
                                'application/vnd.ms-excel', // .xls
                            ])
                            ->maxSize(10240) // 10MB
                            ->helperText('Formatos aceptados: .docx, .doc, .pdf, .xlsx, .xls. Máx. 10MB')
                            ->preserveFilenames()
                            ->openable()
                            ->downloadable()
                            ->previewable(false)
                            ->imagePreviewHeight('100')
                            ->columnSpanFull(),

                        
                    ])
                    ->collapsible(false),

                ComponentsSection::make('Configuración de la Plantilla')
                    ->description('Tipo y configuración específica')
                    ->icon('heroicon-m-cog')
                    ->schema([
                        ComponentsGrid::make(2)
                            ->schema([
                                Select::make('tipo')
                                    ->label('Tipo de plantilla')
                                    ->options(TipoPlantilla::class)
                                    ->default('docx')
                                    ->required()
                                    ->native(false)
                                    ->searchable()
                                    ->prefixIcon('heroicon-m-tag')
                                    ->helperText('Define el formato y propósito de la plantilla'),

                                Select::make('fraccionamiento_id')
                                    ->label('Fraccionamiento asociado')
                                    ->relationship('fraccionamiento', 'nombre')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->native(false)
                                    ->prefixIcon('heroicon-m-building-office')
                                    ->helperText('Selecciona el fraccionamiento al que pertenece esta plantilla')
                                    ->createOptionForm([
                                        ComponentsSection::make('Crear nuevo fraccionamiento')
                                            ->schema([
                                                ComponentsGrid::make(2)
                                                    ->schema([
                                                        TextInput::make('nombre')
                                                            ->required()
                                                            ->maxLength(255)
                                                            ->label('Nombre'),
                                                        TextInput::make('ubicacion')
                                                            ->maxLength(255)
                                                            ->label('Ubicación'),
                                                        TextInput::make('codigo_postal')
                                                            ->maxLength(10)
                                                            ->label('Código Postal'),
                                                    ]),
                                            ]),
                                    ]),
                            ]),
                    ])
                    ->collapsible(false),

                ComponentsSection::make('Descripción')
                    ->description('Detalles adicionales de la plantilla')
                    ->icon('heroicon-m-document-text')
                    ->schema([
                        Textarea::make('descripcion')
                            ->label('Descripción')
                            ->placeholder('Describe el propósito y contenido de esta plantilla...')
                            ->rows(4)
                            ->helperText('Información adicional sobre cuándo y cómo usar esta plantilla')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(true),

                // Información del fraccionamiento seleccionado
                ComponentsSection::make('Información del Fraccionamiento')
                    ->description('Datos del fraccionamiento asociado')
                    ->icon('heroicon-m-building-office')
                    ->schema([
                        ComponentsGrid::make(2)
                            ->schema([
                                Placeholder::make('fracc_nombre')
                                    ->label('Nombre')
                                    ->content(function ($get) {
                                        $id = $get('fraccionamiento_id');
                                        if (!$id) return 'No seleccionado';
                                        $f = Fraccionamiento::find($id);
                                        return $f?->nombre ?? '-';
                                    }),

                                Placeholder::make('fracc_ubicacion')
                                    ->label('Ubicación')
                                    ->content(function ($get) {
                                        $id = $get('fraccionamiento_id');
                                        if (!$id) return 'No seleccionado';
                                        $f = Fraccionamiento::find($id);
                                        return $f?->ubicacion ?? '-';
                                    }),

                                Placeholder::make('fracc_cp')
                                    ->label('Código Postal')
                                    ->content(function ($get) {
                                        $id = $get('fraccionamiento_id');
                                        if (!$id) return 'No seleccionado';
                                        $f = Fraccionamiento::find($id);
                                        return $f?->codigo_postal ?? '-';
                                    }),
                            ]),
                    ])
                    ->visible(fn ($get) => $get('fraccionamiento_id'))
                    ->collapsible(true)
                    ->collapsed(),

                // Metadatos (solo en edición)
                ComponentsSection::make('Metadatos')
                    ->description('Información del sistema')
                    ->icon('heroicon-m-information-circle')
                    ->schema([
                        ComponentsGrid::make(2)
                            ->schema([
                                Placeholder::make('created_at')
                                    ->label('Creado')
                                    ->content(fn ($record) => $record?->created_at?->format('d/m/Y H:i') ?? '-'),

                                Placeholder::make('updated_at')
                                    ->label('Última actualización')
                                    ->content(fn ($record) => $record?->updated_at?->format('d/m/Y H:i') ?? '-'),
                            ]),
                    ])
                    ->collapsible()
                    ->collapsed()
                    ->visible(fn (string $operation) => $operation === 'edit'),
            ])
            ->columns(1);
    }
}