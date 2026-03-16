<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lotes extends Model
{
        
    protected $table = 'lotes';

    protected $fillable = [
        'fraccionamiento_id',
        'manzana',
        'lote',
        'area',
        'norte',
        'sur',
        'este',
        'oeste',
        'precio',
        'estatus',
        'observaciones'
    ];

    protected $casts = [
        'area' => 'decimal:2',
        'norte' => 'decimal:2',
        'sur' => 'decimal:2',
        'este' => 'decimal:2',
        'oeste' => 'decimal:2',
        'precio' => 'decimal:2',
    ];

    /**
     * Relación con fraccionamiento
     */
    public function fraccionamiento()
    {
        return $this->belongsTo(Fraccionamiento::class);
    }

    /**
     * Nombre completo del lote
     */
    public function getNombreAttribute()
    {
        return 'MZ ' . $this->manzana . ' - LT ' . $this->lote;
    }
}
