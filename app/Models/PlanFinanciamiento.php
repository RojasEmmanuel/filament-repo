<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanFinanciamiento extends Model
{
    protected $table = 'planes_financiamiento';

    protected $fillable = [
        'nombre',
        'descripcion',
        'frecuencia_pago',
        'tipo_enganche',
        'modo_enganche',
        'enganche',
        'plazo_pagos',
        'tipo_interes',
        'valor_interes',
        'periodo_interes',
        'tipo_penalizacion',
        'aplicacion_penalizacion',
        'penalizacion',
        'dias_gracia',
        'activo'
    ];

    public function ventas()
    {
        return $this->hasMany(Ventas::class);
    }
}
