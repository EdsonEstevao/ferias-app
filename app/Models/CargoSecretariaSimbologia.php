<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CargoSecretariaSimbologia extends Model
{
    //
    protected $table = 'cargo_secretaria_simbologia';

    protected $fillable = [
        'cargo_id',
        'secretaria_id',
        'simbologia_id'
    ];

    public function cargo()
    {
        return $this->belongsTo(Cargo::class);
    }

    public function simbologia()
    {
        return $this->belongsTo(Simbologia::class);
    }

    public function secretaria()
    {
        return $this->belongsTo(Secretaria::class);
    }
}
