<?php

namespace App\Livewire;

use App\Models\Servidor;
use Livewire\Component;

class ServidorForm extends Component
{
    public $nome, $cpf, $email, $matricula, $telefone;
    public $secretaria, $lotacao, $departamento;
    public $processo_implantacao, $processo_disposicao, $numero_memorando;

    public function rules()
    {
        return [
            'nome' => 'required|string|max:255',
            'cpf' => 'required|string|size:11|unique:servidores',
            'email' => 'required|email|unique:servidores',
            'matricula' => 'required|string|unique:servidores',
            'telefone' => 'nullable|string|max:20',
            'secretaria' => 'required|string|max:255',
            'lotacao' => 'required|string|max:255',
            'departamento' => 'required|string|max:255',
            'processo_implantacao' => 'nullable|string|max:255',
            'processo_disposicao' => 'nullable|string|max:255',
            'numero_memorando' => 'nullable|string|max:255',
        ];
    }

    public function save()
    {
        $this->validate();

        Servidor::create([
            'nome' => $this->nome,
            'cpf' => $this->cpf,
            'email' => $this->email,
            'matricula' => $this->matricula,
            'telefone' => $this->telefone,
            'secretaria' => $this->secretaria,
            'lotacao' => $this->lotacao,
            'departamento' => $this->departamento,
            'processo_implantacao' => $this->processo_implantacao,
            'processo_disposicao' => $this->processo_disposicao,
            'numero_memorando' => $this->numero_memorando,
        ]);

        session()->flash('success', 'Servidor cadastrado com sucesso!');
        $this->reset();
    }

    public function render()
    {
        return view('livewire.servidor-form');
    }

}
