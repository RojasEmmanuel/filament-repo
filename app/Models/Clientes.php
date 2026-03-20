<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Clientes extends Model
{
    protected $fillable = [
        'nombre',
        'apellidos',
        'edad',
        'ciudad',
        'tipo',
        'telefono',
        'fecha_nacimiento',
        'curp',
        'rfc',
        'ocupacion',
        'estado_civil',
        'user_id',        
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($cliente) {
            if ($cliente->fecha_nacimiento) {
                $cliente->edad = Carbon::parse($cliente->fecha_nacimiento)->age;
            }
        });
    }

    public function ventas()
    {
        return $this->hasMany(Ventas::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
