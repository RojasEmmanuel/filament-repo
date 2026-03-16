<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fraccionamiento extends Model
{
    protected $fillable = [
        'nombre',
        'ubicacion',    
        'descripcion',
        'codigo_postal',
        'perimetro',
        'area_total',
        'total_manzanas',
        'total_lotes',
        'activo',
        'imagen'
    ];
}
