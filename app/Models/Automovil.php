<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Automovil extends Model
{
    use HasFactory;
    protected $table = "autos";

    protected $fillable = [ 
        'marca',
        'color',
        'placas',
        'descripcion',
        'ingreso_id',
    ];
}
