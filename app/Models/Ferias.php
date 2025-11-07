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

    public function periodosAtivos()
    {
        return $this->hasMany(FeriasPeriodos::class, 'ferias_id')->where('ativo', true);
    }

    // Períodos originais (sem pai)
    public function periodosOriginais()
    {
        return $this->hasMany(FeriasPeriodos::class, 'ferias_id')
                    ->whereNull('periodo_origem_id')
                    ->where('ativo', true);
    }

    // ========== NOVOS MÉTODOS ==========

    /**
     * Períodos usufruídos
     */
    public function periodosUsufruidos()
    {
        return $this->hasMany(FeriasPeriodos::class, 'ferias_id')
                    ->where('usufruido', true);
    }

    /**
     * Períodos pendentes (não usufruídos)
     */
    public function periodosPendentes()
    {
        return $this->hasMany(FeriasPeriodos::class, 'ferias_id')
                    ->where('usufruido', false)
                    ->where('ativo', true);
    }

    /**
     * Verificar se tem períodos usufruídos
     */
    public function temPeriodosUsufruidos()
    {
        return $this->periodos()->where('usufruido', true)->exists();
    }

    /**
     * Verificar se tem períodos pendentes
     */
    public function temPeriodosPendentes()
    {
        return $this->periodos()
                    ->where('usufruido', false)
                    ->where('ativo', true)
                    ->whereIn('situacao', ['Planejado', 'Remarcado', 'Interrompido'])
                    ->exists();
    }

    /**
     * Total de dias usufruídos
     */
    public function getTotalDiasUsufruidosAttribute()
    {
        return $this->periodos()
                    ->where('usufruido', true)
                    ->sum('dias');
    }

    /**
     * Total de dias pendentes
     */
    public function getTotalDiasPendentesAttribute()
    {
        return $this->periodos()
                    ->where('usufruido', false)
                    ->where('ativo', true)
                    ->sum('dias');
    }

}
