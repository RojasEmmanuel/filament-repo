<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bancos extends Model
{
    protected $table = 'bancos';

    protected $fillable = [
        'nombre_banco',
        'tipo_cuenta',
        'moneda',
        'numero_cuenta',
        'codigo_interbancario',
        'representante',
    ];

    public function moneda()
    {
        return $this->belongsTo(Monedas::class, 'moneda');
    }

    public function fraccionamientos()
    {
        return $this->belongsToMany(
            Fraccionamiento::class,
            'banco_fraccionamiento',
            'banco_id',
            'fraccionamiento_id'
        );
    }
}
