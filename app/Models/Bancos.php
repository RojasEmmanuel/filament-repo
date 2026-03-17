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
}
