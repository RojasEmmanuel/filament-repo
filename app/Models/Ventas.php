<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ventas extends Model
{
    protected $table = 'ventas';
    
    protected $fillable = [
        'cliente_id',
        'user_id',
        'plan_financiamiento_id',
        'fraccionamiento_id',
        'folio',
        'tipo_venta',
        'fecha_venta',
        'subtotal',
        'descuento',
        'total',
        'enganche_aplicado',
        'saldo_restante',
        'comprobante_pago',
        'metodo_pago',
        'estatus',
        'observaciones'
    ];

    public function cliente()
    {
        return $this->belongsTo(Clientes::class);
    }

    public function planFinanciamiento()
    {
        return $this->belongsTo(PlanFinanciamiento::class);
    }

    public function fraccionamiento()
    {
        return $this->belongsTo(Fraccionamiento::class);
    }

    public function ventaLotes(): HasMany
    {
        return $this->hasMany(
            VentaLotes::class,  // Modelo relacionado
            'venta_id',          // Foreign key en tabla venta_lotes
            'id'                 // Local key en tabla ventas
        );
    }

    /**
     * Getter personalizado para conteo de lotes
     */
    public function getLotesCountAttribute()
    {
        return $this->ventaLotes()->count();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
