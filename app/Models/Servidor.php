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

    public function ferias()
    {
        return $this->hasMany(Ferias::class);
    }

    public function vinculos()
    {
        return $this->hasMany(VinculoFuncional::class);
    }


}
