<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    use HasFactory;
    protected $fillable = [ 
        'marca',
        'modelo',
        'no_serie',
        'ingreso_id',
    ];
}
