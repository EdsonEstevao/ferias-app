<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ferias extends Model
{
    //
    protected $table = 'ferias';

    protected $fillable = [
        'servidor_id',
        'ano_exercicio',
        'situacao',
        'tipo',
    ];

}