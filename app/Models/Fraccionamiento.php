<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fraccionamiento extends Model
{
    protected $fillable = [
        'nombre',
        'ubicacion',    
        'descripcion',
        'activo',
        'imagen'
    ];
}
