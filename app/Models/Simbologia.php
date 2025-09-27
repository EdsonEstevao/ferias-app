<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Simbologia extends Model
{
    //
    protected $table = 'simbologias';

    protected $fillable = [
        'nome',
        'ativo',
        'ordem',
        'valor',
    ];


    public function vinculos()
    {
        return $this->hasMany(CargoSecretariaSimbologia::class);
    }
}
