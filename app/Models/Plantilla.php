<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\TipoPlantilla;

class Plantilla extends Model
{

    protected $table = 'plantillas';

    protected $fillable = [
        'nombre',
        'descripcion',
        'clave',
        'ruta',
        'tipo',
        'fraccionamiento_id',
    ];

    public function fraccionamiento()
    {
        return $this->belongsTo(Fraccionamiento::class);
    }

    protected $casts = [
        'tipo' => TipoPlantilla::class,
    ];

}


