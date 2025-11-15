<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeriasEvento extends Model
{
    //
    protected $table = 'ferias_eventos';

    protected $fillable = [
        'ferias_periodo_id',
        'acao',
        'descricao',
        'data_acao',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function periodo()
    {
        return $this->belongsTo(FeriasPeriodos::class, 'ferias_periodo_id');
    }
}
