<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingreso extends Model
{
    use HasFactory;
    protected $fillable = [ 
        'fecha',
        'hora_agendada',
        'hora_entrada',
        'hora_salida',
        'edo_cita',
        'codigo',
        'codigo_qr',
        'hora_salida',
        'visitante_id',
        'personal_id',
    ];
}
