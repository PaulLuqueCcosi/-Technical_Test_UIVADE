<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VentaDetalle extends Model
{
    use HasFactory;

    // La tabla asociada con el modelo.
    protected $table = 'prueba.venta_detalle';
    protected $primaryKey = 'v_d_ide';

    // Los atributos que se pueden asignar en masa.
    protected $fillable = [
        'ven_ide',
        'v_d_pro',
        'v_d_uni',
        'v_d_can',
        'v_d_tot',
        'est_ado',
    ];

    protected $hidden = ['est_ado'];

    // Definición de la relación con el modelo Venta.
    public function venta()
    {
        return $this->belongsTo(Venta::class, 'ven_ide', 'ven_ide');
    }

    public function scopeActivo($query)
    {
        return $query->where('est_ado', 1);
    }
    // Alcance global para filtrar por venta activa
    public function scopeVentaActiva($query)
    {
        return $query->whereHas('venta', function ($q) {
            $q->where('est_ado', 1);
        });
    }
}
