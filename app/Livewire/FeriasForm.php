<?php

namespace App\Livewire;

use App\Models\Ferias;
use App\Models\FeriasPeriodos;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;


class FeriasForm extends Component
{


    public $ano_exercicio;
    public $periodos = [];
    public $servidorId;
    public $anoExercicio;
    public $tipo = 'Integral';
    public $inicio;
    public $fim;
    public function render()
    {
        return view('livewire.ferias-form')->layout('layouts.app');
    }

   public function mount($servidorId)
    {
        $this->ano_exercicio = now()->year;
        $this->servidorId = $servidorId;
        $this->periodos = [
            ['tipo' => 'Férias', 'inicio' => '', 'fim' => '']
        ];
    }

    public function addPeriodo()
    {
        if (count($this->periodos) < 3) {
            $this->periodos[] = ['tipo' => 'Férias', 'inicio' => '', 'fim' => ''];
        }
    }

    public function removePeriodo($index)
    {
        unset($this->periodos[$index]);
        $this->periodos = array_values($this->periodos);
    }

    public function save()
    {
        $this->validate([
            'ano_exercicio' => 'required|integer',
            'periodos.*.tipo' => 'required|in:Férias,Abono',
            'periodos.*.inicio' => 'required|date',
            'periodos.*.fim' => 'required|date|after_or_equal:periodos.*.inicio',
        ]);

        $ferias = Ferias::create([
            'servidor_id' => $this->servidorId,//auth()->user()->servidor_id,
            'ano_exercicio' => $this->ano_exercicio,
        ]);

        foreach ($this->periodos as $i => $p) {
            FeriasPeriodos::create([
                'ferias_id' => $ferias->id,
                'tipo' => $p['tipo'],
                'ordem' => $i + 1,
                'dias' => Carbon::parse($p['inicio'])->diffInDays(Carbon::parse($p['fim'])) + 1,
                'inicio' => $p['inicio'],
                'fim' => $p['fim'],
            ]);
        }

        session()->flash('success', 'Férias lançadas com sucesso!');
        $this->reset();
        $this->mount($this->servidorId);

    }


    public function salvar()
    {
        $this->validate([
            'anoExercicio' => 'required|digits:4',
            'tipo' => 'required|in:Integral,Parcelado',
            'inicio' => 'required|date',
            'fim' => 'required|date|after_or_equal:inicio',
        ]);

        $dias = \Carbon\Carbon::parse($this->inicio)->diffInDays($this->fim) + 1;

        $ferias = Ferias::firstOrCreate([
            'servidor_id' => $this->servidorId,
            'ano_exercicio' => $this->anoExercicio,
        ]);

        $ferias->periodos()->create([
            'tipo' => $this->tipo,
            'inicio' => $this->inicio,
            'fim' => $this->fim,
            'dias' => $dias,
        ]);

        session()->flash('success', 'Férias lançadas com sucesso!');
        $this->reset(['inicio', 'fim']);
    }








}
