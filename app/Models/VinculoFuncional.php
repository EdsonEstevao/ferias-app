<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VinculoFuncional extends Model
{
    //
    protected $table = 'vinculo_funcionals';

    protected $fillable = [
        'servidor_id',
        'secretaria',
        'cargo',
        'lotacao',
        'departamento',
        'processo_implantacao',
        'processo_disposicao',
        'numero_memorando',
        'tipo_movimentacao',
        'data_movimentacao',
        'ato_normativo',
        'observacao',
        'data_saida',
        'tipo_servidor',
        'escolaridade',
        'curso_superior',
        'is_diretor',
        'rg',
        'orgao_expedidor',
        'data_expedicao',
        'data_nascimento',
        'naturalidade',
        'nacionalidade',
        'nome_mae',
        'nome_pai',
        'estado_civil',
        'sexo',
        'cep',
        'endereco',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
        'local_trabalho',
        'email',

    ];

    public function servidor()
    {
        return $this->belongsTo(Servidor::class);
    }
}