<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        'convertido_abono', // ← NOVO: controle por período
        'data_conversao_abono', // ← NOVO
        'justificativa_abono', // ← NOVO
        'url_abono', // ← NOVO
        'title_abono', // ← NOVO


    ];
     protected $casts = [
        'usufruido' => 'boolean',
        'convertido_abono' => 'boolean',
        'inicio' => 'date:Y-m-d',
        'fim' => 'date:Y-m-d',
        'data_usufruto' => 'datetime',
        'data_conversao_abono' => 'datetime',
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
    public function marcarComoUsufruido(): bool
    {

        $this->update([
            'usufruido' => true,
            'data_usufruto' => now(),
        ]);

        // Se este período tem um pai, verificar se todos os irmãos estão usufruídos
        if ($this->periodo_origem_id) {
            $this->atualizarStatusPai();
        }

         // Se este período é um pai, verificar se todos os filhos estão usufruídos
        if ($this->filhos()->exists()) {
            $this->atualizarStatusBaseadoNosFilhos();
        }

        // Registrar evento
        $this->registrarEventoUsufruto();


        return true;



    }

    /**
     * Atualizar status do período pai baseado nos filhos
     */
    private function atualizarStatusPai()
    {
        $pai = $this->origem;
        if (!$pai) return;

        // Verificar se TODOS os filhos estão usufruídos
        $todosFilhosUsufruidos = $pai->filhos()
            ->where('ativo', true)
            ->where('usufruido', false)
            ->doesntExist();

        if ($todosFilhosUsufruidos) {
            $pai->update([
                'usufruido' => true,
                'data_usufruto' => now(),
            ]);

            // Recursivamente atualizar o pai do pai, se existir
            if ($pai->periodo_origem_id) {
                $pai->atualizarStatusPai();
            }
        }
    }

    /**
     * Atualizar status baseado nos filhos (para períodos pais)
     */
    private function atualizarStatusBaseadoNosFilhos()
    {
        $todosFilhosUsufruidos = $this->filhos()
            ->where('ativo', true)
            ->where('usufruido', false)
            ->doesntExist();

        if ($todosFilhosUsufruidos) {
            $this->update([
                'usufruido' => true,
                'data_usufruto' => now(),
            ]);

            // Se este período também tem um pai, atualizá-lo
            if ($this->periodo_origem_id) {
                $this->atualizarStatusPai();
            }
        }
    }

    /**
     * Registrar evento de usufruto
     */
    private function registrarEventoUsufruto()
    {
        FeriasEvento::create([
            'ferias_periodo_id' => $this->id,
            'acao' => 'Usufruto',
            'descricao' => "Período marcado como usufruído ({$this->dias} dias)",
            'data_acao' => now(),
            'user_id' => Auth::id(),
        ]);
    }

    /**
     * Marcar como usufruído o pai e propagar para ancestrais
     */
    public function marcarComoUsufruidoPai($id)
    {
        $pai = $this->find($id);
        if (!$pai) return;

        // Marcar o pai
        $pai->update([
            'usufruido' => true,
            'data_usufruto' => now(),
        ]);

        // Propagar recursivamente para o avô, se existir
        if ($pai->periodo_origem_id) {
            $this->marcarComoUsufruidoPai($pai->periodo_origem_id);
        }
    }

    /**
     * Desmarcar usufruto do pai e propagar para ancestrais
     */
    public function desmarcarUsufrutoPai($id)
    {
        $pai = $this->find($id);
        if (!$pai) return;

        // Desmarcar o pai
        $pai->update([
            'usufruido' => false,
            'data_usufruto' => null,
        ]);

        // Propagar recursivamente para o avô, se existir
        if ($pai->periodo_origem_id) {
            $this->desmarcarUsufrutoPai($pai->periodo_origem_id);
        }
    }

    /**
     * Verificar se todos os descendentes diretos estão usufruídos (para qualquer nível)
     */
    public function todosDescendentesUsufruidos($periodo_origem_id)
    {
        if (!$periodo_origem_id) {
            return false;
        }

        // Buscar todos os filhos diretos
        $filhosDiretos = $this->where('periodo_origem_id', $periodo_origem_id)
                            ->where('ativo', true)
                            ->get();

        // Se não há filhos, não é um nó pai
        if ($filhosDiretos->isEmpty()) {
            return false;
        }

        // Verificar se TODOS os filhos diretos estão usufruídos
        $todosFilhosUsufruidos = $filhosDiretos->every(function ($filho) {
            return $filho->usufruido == 1;
        });

        // Se todos os filhos diretos estão usufruídos, verificar os netos recursivamente
        if ($todosFilhosUsufruidos) {
            foreach ($filhosDiretos as $filho) {
                // Verificar se este filho tem filhos e se todos estão usufruídos
                $filhoTemDescendentesUsufruidos = $this->todosDescendentesUsufruidos($filho->id);
                if (!$filhoTemDescendentesUsufruidos) {
                    return false;
                }
            }
            return true;
        }

        return false;
    }
    /**
     * Registrar evento de desusufruto
     */
    private function registrarEventoDesusufruto()
    {
        FeriasEvento::create([
            'ferias_periodo_id' => $this->id,
            'acao' => 'Desmarcar Usufruto',
            'descricao' => "Usufruto desmarcado do período ({$this->dias} dias)",
            'data_acao' => now(),
            'user_id' => Auth::id(),
        ]);
    }

    /**
     * Desmarcar período como usufruído
     */
    public function desmarcarUsufruto()
    {


        $this->update([
            'usufruido' => false,
            'data_usufruto' => null,
            // 'situacao' => $situacao,

        ]);

        // Registrar o Evento
        $this->registrarEventoDesusufruto();


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

    /**
     * Verificar se todos os filhos estão usufruídos
     */
    public function todosFilhosUsufruidos()
    {
        return $this->filhos()
            ->where('ativo', true)
            ->where('usufruido', false)
            ->doesntExist();
    }

    /**
     * Obter a árvore completa de períodos (pai + filhos)
     */
    public function arvoreCompleta()
    {
        $arvore = collect([$this]);

        if ($this->filhos()->exists()) {
            $filhos = $this->filhos()->with('arvoreCompleta')->get();
            $arvore = $arvore->merge($filhos->flatMap->arvoreCompleta);
        }

        return $arvore;
    }


    /**
     * Verificar se o periodo é fracionado (tem irmãos com o mesmo pai)
     */
    public function isPeriodoFracionado($periodo_origem_id)
    {
        if (!$periodo_origem_id) {
            return false;
        }

        $irmaosCount = $this->where('periodo_origem_id', $periodo_origem_id)
                            ->where('ativo', true)
                            ->count();

        return $irmaosCount > 1;
    }

    /**
     * Verificar se todos os irmãos estão usufruídos
     */
    public function todosIrmaosUsufruidos()
    {
        if (!$this->periodo_origem_id) {
            return false;
        }

        return $this->where('periodo_origem_id', $this->periodo_origem_id)
                    ->where('ativo', true)
                    ->where('usufruido', false)
                    ->doesntExist();
    }

    /**
     * Verificar se algum filho está usufruído
     */
    public function algumFilhoUsufruido()
    {
        return $this->filhos()
            ->where('ativo', true)
            ->where('usufruido', true)
            ->exists();
    }

     public function getInicioFormatadoAttribute()
    {
        return $this->inicio ? Carbon::parse($this->inicio)->format('Y-m-d') : null;
    }

    public function getFimFormatadoAttribute()
    {
        return $this->fim ? Carbon::parse($this->fim)->format('Y-m-d') : null;
    }

     // ========== NOVOS MÉTODOS PARA ABONO PECUNIÁRIO ==========
     /**
      * Converter período para abono pecuniário
      */
     public function converterParaAbonoPecuniario($justificativa = null, $situacao = 'Interrompido', $url_abono = null, $title_abono = null)
     {
        if(!$this->podeSerConvertidoAbono()){
            return false;
        }

        // dd('passou...', $this->id, $justificativa, $situacao);

        DB::transaction(function () use ($justificativa, $situacao, $url_abono, $title_abono) {

            // dd('chegou aqu....', $this->id, $justificativa, $situacao);
            $this->update([
                'convertido_abono' => true,
                'data_conversao_abono' => now(),
                'justificativa_abono' => $justificativa,
                'situacao' =>  $situacao,//,in_array($situacao, ['Interrompido', 'Remarcado']) ? $this->situacao : 'Interrompido',
                'usufruido' => true,
                'data_usufruto' => now(),
                'url_abono' => $url_abono,
                'title_abono' => $title_abono
            ]);

            // dd('chegou aqui...passou do update...');


            //Registrar evento

            $this->registrarEventoConversaoAbono($justificativa);
        });


        return true;

     }

     /**
      * Reverter Conversão para abono pecuniário
      */

     public function reverterAbonoPecuniario($justificativa = null)
     {
        if(!$this->convertido_abono){
            return false;
        }

        $situacao_anterior = FeriasEvento::where('ferias_periodo_id', $this->periodo_origem_id)->first()->acao;

       $situacao_anterior = match ($situacao_anterior) {
                                'Interrupção' => 'Interrompido',
                                'Remarcação' => 'Remarcado',
                                default => $situacao_anterior // mantém o valor original se não match
                            };

        // dd('chegou aq...', $situacao_anterior);

        DB::transaction(function () use ($justificativa, $situacao_anterior) {
            $this->update([
                'convertido_abono' => false,
                'data_conversao_abono' => null,
                'justificativa_abono' => null,
                'url_abono' => null,
                'title_abono' => null,
                'situacao' => $situacao_anterior, //  situação anterior
                'usufruido' => false,
                'data_usufruto' => null,
            ]);

            // dd('chegou aqui...');


            // Registrar evento

            $this->registrarEventoReversaoAbono($justificativa);
        });

        return true;
     }

     /**
      * Verificar se o período pode ser convertido para abono pecuniário
      */

     public function podeSerConvertidoAbono()
     {
        // Não pode converter se:
        // - Já foi convertido
        // - Já foi usufruído
        // - Não está ativo
        // - É um período filho (remarcado)

        return !$this->convertido_abono &&
               !$this->usufruido &&
               $this->ativo &&
               $this->periodo_origem_id == null || $this->periodo_origem_id > 0 &&
               in_array($this->situacao, ['Planejado','Interrompido', 'Remarcado']);



     }


     /**
      * Converter apenas parte do período para abono pecuniário
      */

     public function converterParcialmenteAbono($diasConverter, $justificativa = null)
     {
        // Implementar a lógica de converter apenas parte do período para abono pecuniário
        if(!$this->podeSerConvertidoAbono() || $diasConverter >= $this->dias){
            return false;
        }

        DB::transaction(function () use ($diasConverter, $justificativa) {

            //criar novo período para a parte convertida
            $periodoConvertido = $this->replicate();
            $periodoConvertido->dias = $diasConverter;
            $periodoConvertido->fim = Carbon::parse($this->inicio)->addDays($diasConverter - 1)->format('Y-m-d');
            $periodoConvertido->convertido_abono = true;
            $periodoConvertido->data_conversao_abono = now();
            $periodoConvertido->justificativa_abono = $justificativa;
            $periodoConvertido->situacao = $this->situacao;
            $periodoConvertido->usufruido = true;
            $periodoConvertido->data_usufruto = now();
            $periodoConvertido->periodo_origem_id = $this->id;
            $periodoConvertido->save();


            //Atualizar período original com dias restantes

            $diasRestantes = $this->dias - $diasConverter;
            $this->update([
                'dias' => $diasRestantes,
                'inicio' => Carbon::parse($periodoConvertido->fim)->addDay()->format('Y-m-d'),
            ]);

            // registrar evento
            $this->registrarEventoConversaoParcialAbono($justificativa);

        });

        return true;
     }

     /**
      * Verificar se é um período de abono pecuniário
      */
     public function isAbonoPecuniario()
     {
        return $this->convertido_abono;
     }

     /**
      * Scope para períodos convertidos em abono pecuniário
      */
     public function scopeConvertidosAbono($query)
     {
        return $query->where('convertido_abono', true);
     }

     /**
      * Scope para períodos não convertidos em abono pecuniário
      */
     public function scopeNaoConvertidosAbono($query)
     {
        return $query->where('convertido_abono', false);
     }

     /**
      * Registrar evento de conversão para abono pecuniário
      */

     private function registrarEventoConversaoAbono($justificativa)
     {
        FeriasEvento::create([
            'ferias_periodo_id' => $this->id,
            'acao' => 'Conversão Abono Pecuniário',
            'descricao' => "Período convertido para abono pecuniário ({$this->dias} dias)",
            'data_acao' => now(),
            'user_id' => Auth::id(),
        ]);
     }

     private function registrarEventoReversaoAbono($justificativa)
     {
        FeriasEvento::create([
            'ferias_periodo_id' => $this->id,
            'acao' => 'Reversão Abono Pecuniário',
            'descricao' => "Reversão de abono pecuniário ({$this->dias} dias)",
            'data_acao' => now(),
            'user_id' => Auth::id(),
        ]);
     }
     private function registrarEventoConversaoParcialAbono($justificativa)
     {
        FeriasEvento::create([
            'ferias_periodo_id' => $this->id,
            'acao' => 'Conversão Parcial Abono Pecuniário',
            'descricao' => "Conversão parcial de abono pecuniário ({$this->dias} dias)",
            'data_acao' => now(),
            'user_id' => Auth::id(),
        ]);
     }


}
