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
        'status',
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

    protected $casts = [
        'tipo_servidor' => 'array',
    ];

    public function servidor()
    {
        return $this->belongsTo(Servidor::class);
    }

    public function scopeAtivos($query)
    {
        // return $query->where('status', 'Ativo');
        return $query->whereNull('data_saida')
                        ->orWhere('data_saida', '>', now());
    }
}