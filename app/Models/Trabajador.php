<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trabajador extends Model
{
    use HasFactory;

    protected $table = 'prueba.trabajador';

    protected $fillable = [
        'tra_cod',
        'tra_nom',
        'tra_pat',
        'tra_mat',
        'est_ado',
    ];

    // Los atributos que deberían ser ocultos para los arreglos.
    protected $hidden = [];

    // Los atributos que deberían ser cast a tipos específicos.
    protected $casts = [];
}
