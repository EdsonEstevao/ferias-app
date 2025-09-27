<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Secretaria extends Model
{
    //

    protected $table = 'secretarias';

    protected $fillable = [
        'secretaria_origem_id',
        'nome',
        'sigla',
    ];

    public function departamentos()
    {
        return $this->hasMany(Departamento::class);
    }

    public function vinculos()
    {
        return $this->hasMany(CargoSecretariaSimbologia::class);
    }

    public function secretaria_origem()
    {
        return $this->belongsTo(Secretaria::class, 'secretaria_origem_id');
    }

    //  public function origem()
    // {
    //     return $this->belongsTo(FeriasPeriodos::class, 'periodo_origem_id');
    // }

    public function filhos()
    {
        return $this->hasMany(Secretaria::class, 'secretaria_origem_id');
    }

    public function todosFilhosRecursivos()
    {
        return $this->filhos()->with('todosFilhosRecursivos');
    }
}