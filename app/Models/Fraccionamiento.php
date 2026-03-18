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

    public function lotes()
    {
        return $this->hasMany(Lotes::class);
    }

    public function bancos()
    {
        return $this->belongsToMany(
            Bancos::class,
            'banco_fraccionamiento',
            'fraccionamiento_id',
            'banco_id'
        );
    }

    public function ventas()
    {
        return $this->hasMany(Ventas::class);
    }
}
