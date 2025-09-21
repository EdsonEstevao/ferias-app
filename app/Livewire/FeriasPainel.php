<?php

namespace App\Livewire;

use App\Models\Ferias;
use App\Models\FeriasPeriodos;
use Livewire\Component;

class FeriasPainel extends Component
{
    public $ano;
    public $servidorId;

    public function mount($servidorId)
    {
        $this->ano = now()->year;
        $this->servidorId = $servidorId;
    }

    public function atualizarSituacao($periodoId, $novaSituacao)
    {
        $periodo = FeriasPeriodos::find($periodoId);


        $periodo->ativo = 0;
        $periodo->situacao = $novaSituacao;
        $periodo->justificativa = 'Atualizado pelo gestor';
        $periodo->save();


        // $novo = FeriasPeriodos::create([
        //     'ferias_id' => $periodo->ferias_id,
        //     'tipo' => $periodo->tipo,
        //     'dias' => $periodo->dias,
        //     'inicio' => $data['data'],
        //     'fim' => $periodo->fim,
        //     'situacao' => 'Interrompido',
        //     'justificativa' => $data['motivo'],
        //     'periodo_origem_id' => $periodo->id
        // ]);

        // // Registra evento
        // $periodo->eventos()->create([
        //     'acao' => 'Interrupção',
        //     'data_acao' => now(),
        //     'descricao' => $data['motivo'],
        //     'user_id' => Auth::id(),
        // ]);


        session()->flash('success', 'Situação atualizada com sucesso!');
    }

    public function render()
    {
        $ferias = Ferias::where('servidor_id', $this->servidorId)
            ->where('ano_exercicio', $this->ano)
            ->with('periodos')
            ->first();

        return view('livewire.ferias-painel', [
            'ferias' => $ferias,
            'servidorId' => $this->servidorId
        ])->layout('layouts.app');
    }

}
