<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    //

    protected $table = 'departamentos';

    protected $fillable = [
        'secretaria_id',
        'nome',
        'sigla',
        'ativo',
        'responsavel',
    ];

    public function secretaria()
    {
        return $this->belongsTo(Secretaria::class);
    }

    public function vinculos()
    {
        return $this->hasMany(CargoSecretariaSimbologia::class);
    }
}