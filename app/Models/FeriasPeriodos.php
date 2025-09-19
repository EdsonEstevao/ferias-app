<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeriasPeriodos extends Model
{
    protected $table = 'ferias_periodos';

    protected $fillable = [
        'ferias_id',
        'ordem',
        'tipo',
        'dias',
        'inicio',
        'fim',
        'situacao',
        'justificativa',
    ];
}