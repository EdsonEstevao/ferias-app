<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeriasPeriodos extends Model
{
    protected $table = 'ferias_periodos';

    protected $fillable = [
        'ferias_id',
        'periodo_origem_id',
        'ordem',
        'tipo',
        'dias',
        'inicio',
        'fim',
        'situacao',
        'justificativa',
        'title',
        'url',
    ];

    public function ferias()
    {
        return $this->belongsTo(Ferias::class);
    }

    public function eventos()
    {
        return $this->hasMany(FeriasEvento::class, 'ferias_periodo_id');
    }
    public function origem()
    {
        return $this->belongsTo(FeriasPeriodos::class, 'periodo_origem_id');
    }

    public function filhos()
    {
        return $this->hasMany(FeriasPeriodos::class, 'periodo_origem_id');
    }
    public function todosFilhosRecursivos()
    {
        return $this->filhos()->with('todosFilhosRecursivos');
    }


}
