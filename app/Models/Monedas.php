<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Monedas extends Model
{
    protected $table = 'monedas';

    protected $fillable = [
        'nombre',
        'codigo_iso',
        'Simbolo',
    ];

}   
