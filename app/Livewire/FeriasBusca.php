<?php

namespace App\Livewire;

use App\Models\Servidor;
use Livewire\Component;

class FeriasBusca extends Component
{
    // public function render()
    // {
    //     return view('livewire.ferias-busca');
    // }
    public $query = '';

    public function render()
    {
        $servidores = Servidor::where('nome', 'like', "%{$this->query}%")
            ->orWhere('cpf', 'like', "%{$this->query}%")
            ->orWhere('matricula', 'like', "%{$this->query}%")
            ->with('ferias.periodos')
            ->get();

        return view('livewire.ferias-busca', compact('servidores'))->layout('layouts.app');
    }
}
