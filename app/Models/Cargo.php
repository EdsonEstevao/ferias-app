<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    //
    protected $table = 'cargos';

    protected $fillable = [
        'nome',
        'ativo',
        'ordem',
    ];

    public function vinculos()
    {
        return $this->hasMany(CargoSecretariaSimbologia::class);
    }


}
