<?php

namespace App\Http\Controllers;

use App\Models\Servidor;
use App\Rules\Cpf;
use Illuminate\Http\Request;

class ServidorController extends Controller
{
    //
    public function index(){
        $data = [];
        $servidores = Servidor::all();

        $data = [
            'servidores' => $servidores
        ];

        return view('servidores.index', $data);

    }
    public function create()
    {
        return view('servidores.create');
    }
    public function store(Request $request)
    {

        // $request->validate([
        //     'cpf' => ['required', 'string', 'max:14', 'unique:servidores,cpf', new Cpf],
        // ]);
        $request->validate([
            'nome' => 'required|string|max:255',
            'cpf' => 'required|string|max:14|unique:servidores,cpf',
            // 'cpf' => ['required', 'string', 'max:14', 'unique:servidores,cpf', new Cpf],
            'matricula' => 'required|string|max:20|unique:servidores,matricula',
            'cargo' => 'nullable|string|max:100',
            'setor' => 'nullable|string|max:100',
        ]);

        Servidor::create($request->all());

        flash()->success('Servidor cadastrado com sucesso!');

        return redirect()->route('servidores.index'); //->with('success', 'Servidor cadastrado com sucesso!');

    }

}
