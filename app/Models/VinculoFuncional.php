<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VinculoFuncional extends Model
{
    //
    protected $table = 'vinculo_funcionals';

    protected $fillable = [
        'servidor_id',
        'cargo',
        'secretaria',
        'tipo_movimentacao',
        'status',
        'data_movimentacao',
        'ato_normativo',
        'observacao',
    ];

    public function servidor()
    {
        return $this->belongsTo(Servidor::class);
    }
}
