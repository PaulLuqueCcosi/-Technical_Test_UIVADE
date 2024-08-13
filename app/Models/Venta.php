<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    // La tabla asociada con el modelo.
    protected $table = 'prueba.venta';
    protected $primaryKey = 'ven_ide';


    // Los atributos que se pueden asignar en masa.
    protected $fillable = [
        'ven_ser',
        'ven_num',
        'ven_cli',
        'ven_mon',
        'est_ado',
    ];

    protected $hidden = ['est_ado'];


    // Las relaciones que el modelo puede tener.
    public function detalles()
    {
        return $this->hasMany(VentaDetalle::class, 'ven_ide', 'ven_ide');
    }

    public function scopeActivo($query)
    {
        return $query->where('est_ado', 1);
    }
}
