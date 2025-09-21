<?php

namespace App\Livewire;

use App\Models\Ferias;
use App\Models\FeriasEvento;
use App\Models\FeriasPeriodos;
use App\Models\Servidor;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class GestorFeriasPainel extends Component
{
    public $servidor;
    public $remarcarId;
    public $novaDataInicio;
    public $novaDataFim;

    // public $ordemDesc;



    public function mount(Servidor $servidor)
    {
        $this->servidor = $servidor;
    }

    public function interromper($periodoId)
    {
        $periodo = FeriasPeriodos::findOrFail($periodoId);
        $periodo->situacao = 'Interrompido';
        $periodo->ativo = false;
        $periodo->save();

        FeriasEvento::create([
            'ferias_periodo_id' => $periodo->id,
            'acao' => 'Interrupção',
            'descricao' => 'Interrupção realizada pelo gestor',
            'data_acao' => now(),
            'user_id' => Auth::id(), //auth()->id(),
        ]);

        session()->flash('success', 'Período interrompido com sucesso!');
    }

    public function remarcar($periodoId)
    {
        $original = FeriasPeriodos::findOrFail($periodoId);

        $novo = FeriasPeriodos::create([
            'ferias_id' => $original->ferias_id,
            'tipo' => $original->tipo,
            'dias' => $original->dias,
            'ordem' => $original->ordem,
            'inicio' => now()->addDays(7)->toDateString(), // exemplo: remarcar para daqui 7 dias
            'fim' => now()->addDays(7 + $original->dias - 1)->toDateString(),
            'situacao' => 'Remarcado',
            'justificativa' => 'Remarcação após interrupção',
        ]);

        FeriasEvento::create([
            'ferias_periodo_id' => $novo->id,
            'acao' => 'Remarcação',
            'descricao' => 'Remarcado pelo gestor após interrupção',
            'data_acao' => now(),
            'user_id' => Auth::id(), //auth()->id(),
        ]);

        session()->flash('success', 'Período remarcado com sucesso!');
    }

    public function iniciarRemarcacao($periodoId)
    {
        $this->remarcarId = $periodoId;
        $this->novaDataInicio = null;
        $this->novaDataFim = null;

    }

    public function salvarRemarcacao()
    {
        $original = FeriasPeriodos::findOrFail($this->remarcarId);

        $original->ativo = false;
        $original->save();

        $novo = FeriasPeriodos::create([
            'ferias_id' => $original->ferias_id,
            'tipo' => $original->tipo,
            'ordem' => $original->ordem,
            'dias' => Carbon::parse($this->novaDataInicio)->diffInDays($this->novaDataFim) + 1,
            'inicio' => $this->novaDataInicio,
            'fim' => $this->novaDataFim,
            'situacao' => 'Remarcado',
            'justificativa' => 'Remarcação após interrupção',
        ]);

        FeriasEvento::create([
            'ferias_periodo_id' => $novo->id,
            'acao' => 'Remarcação',
            'descricao' => "Remarcado para {$this->novaDataInicio} a {$this->novaDataFim}",
            'data_acao' => now(),
            'user_id' => Auth::id(), //auth()->id(),
        ]);

        $this->remarcarId = null;
        $this->novaDataInicio = null;
        $this->novaDataFim = null;

        // session()->flash('success', 'Período remarcado com sucesso!');
        flash('success', 'Período remarcado com sucesso!');
    }



    public function render()
    {
        $ferias = Ferias::where('servidor_id', $this->servidor->id)
            ->with(['periodos.eventos', 'periodos' => function ($q) {
                $q->orderBy('inicio');
            }])
            ->get();
        $ordemDesc = ['Primeiro', 'Segundo', 'Terceiro'];

        return view('livewire.gestor-ferias-painel', compact('ferias', 'ordemDesc'))->layout('layouts.app');
    }

}
