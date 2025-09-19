<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servidor extends Model
{
    //
    protected $table = 'servidores';

    protected $fillable = [
        'nome',
        'cpf',
        'email',
        'matricula',
        'telefone',
        'secretaria',
        'lotacao',
        'departamento',
        'processo_implantacao',
        'processo_disposicao',
        'numero_memorando',
    ];


}
