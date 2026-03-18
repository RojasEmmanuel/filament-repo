<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VentaLotes extends Model
{
    protected $table = 'venta_lotes';
    
    protected $fillable = [
        'venta_id',
        'lote_id',
        'precio_lote',
        'descuento_lote',
        'total_lote'
    ];

    public function venta()
    {
        return $this->belongsTo(Ventas::class);
    }

    public function lote()
    {
        return $this->belongsTo(Lotes::class);
    }
}
