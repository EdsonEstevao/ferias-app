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

    public function servidor()
    {
        return $this->belongsTo(Servidor::class);
    }

    public function periodos()
    {
        return $this->hasMany(FeriasPeriodos::class);
    }

}