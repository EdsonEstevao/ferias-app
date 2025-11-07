<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

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
        'ativo',
        'situacao',
        'usufruido', // ← NOVO
        'data_usufruto', // ← NOVO
        'justificativa',
        'title',
        'url',
    ];
     protected $casts = [
        'usufruido' => 'boolean',
        'inicio' => 'date:Y-m-d',
        'fim' => 'date:Y-m-d',
        'data_usufruto' => 'datetime',
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
    /**
     * Retorna todos os filhos recursivamente como uma Collection
     */
    public function getTodosFilhosRecursivosAttribute()
    {
        $todosFilhos = new Collection();

        foreach ($this->filhos as $filho) {
            $todosFilhos->push($filho);
            $todosFilhos = $todosFilhos->merge($filho->todosFilhosRecursivos);
        }

        return $todosFilhos;
    }
    // ========== NOVOS MÉTODOS ==========

    /**
     * Scope para períodos usufruídos
     */
    public function scopeUsufruidos($query)
    {
        return $query->where('usufruido', true);
    }

    /**
     * Scope para períodos não usufruídos
     */
    public function scopeNaoUsufruidos($query)
    {
        return $query->where('usufruido', false);
    }

    /**
     * Scope para períodos pendentes (não usufruídos e ativos)
     */
    public function scopePendentes($query)
    {
        return $query->where('usufruido', false)
                    ->where('ativo', true);
    }

    /**
     * Marcar período como usufruído
     */
    public function marcarComoUsufruido()
    {
        $situacao = $this->periodo_origem_id > 0 ? 'Remarcado' : ($this->situacao === 'Planejado' ? 'Usufruido' : $this->situacao);
        return $this->update([
            'usufruido' => true,
            'data_usufruto' => now(),
            'situacao' => $situacao,
        ]);
    }

    /**
     * Desmarcar período como usufruído
     */
    public function desmarcarUsufruto()
    {
        $situacao = $this->periodo_origem_id == null ? 'Planejado' : ($this->situacao !== null ? 'Remarcado' : $this->situacao);
        return $this->update([
            'usufruido' => false,
            'data_usufruto' => null,
            'situacao' => $situacao, // ou outra situação apropriada
        ]);
    }

    /**
     * Verificar se o período pode ser usufruído
     */
    public function podeSerUsufruido()
    {
        return !$this->usufruido &&
               $this->ativo &&
               in_array($this->situacao, ['Planejado', 'Remarcado']);
    }

     public function getInicioFormatadoAttribute()
    {
        return $this->inicio ? Carbon::parse($this->inicio)->format('Y-m-d') : null;
    }

    public function getFimFormatadoAttribute()
    {
        return $this->fim ? Carbon::parse($this->fim)->format('Y-m-d') : null;
    }


}