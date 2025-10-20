<?php

namespace App\Http\Controllers;

use App\Models\Cargo;
use App\Models\CargoSecretariaSimbologia;
use App\Models\Secretaria;
use App\Models\Servidor;
use App\Models\Simbologia;
use App\Models\VinculoFuncional;
use App\Rules\Cpf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ServidorController extends Controller
{
    //
    public function index(){
        $data = [];
        $servidores = Servidor::with('vinculos')->get();

        // dd($servidores);

        $data = [
            'servidores' => $servidores,
            'mensagem' => session('mensagem', null)
        ];
        // dd($data);

        return view('servidores.index', $data);

    }
    public function create()
    {
        $data = [];
        $secretarias = Secretaria::all();
        $servidores = Servidor::with('vinculos')->get();
        $cargos = Cargo::orderBy('nome')->get();
        $simbologias = Simbologia::all();
        $cargoSecretariaSimbologias = CargoSecretariaSimbologia::all();


        $data = [
            'secretarias' => $secretarias,
            'cargos' => $cargos,
            'simbologias' => $simbologias,
            'cargoSecretariaSimbologias' => $cargoSecretariaSimbologias,
            'servidores' => $servidores,
        ];

        // dd($data);
        return view('servidores.create', $data);
    }

    public function store(Request $request)
    {


        $request->validate([
            'cpf' => ['nullable', 'regex:/^\d{3}\.\d{3}\.\d{3}\-\d{2}$/']
        ]);
        $request['is_diretor'] = $request->is_diretor == 'Sim' ? 1 : 0;
        $request['endereco'] = $request->logradouro ?? null;

        if (!empty($request['data_expedicao'])) {
            $request['data_expedicao'] = Carbon::createFromFormat('d/m/Y', $request['data_expedicao'])->format('Y-m-d');
        }
        if($request->filled('data_nascimento')) {
            $request['data_nascimento'] = Carbon::createFromFormat('d/m/Y', $request['data_nascimento'])->format('Y-m-d');
        }
        $vinculo = CargoSecretariaSimbologia::where('cargo_id', $request['cargo'])
                    ->where('secretaria_id', $request['secretaria'])
                    ->with(['cargo', 'secretaria'])
                    ->first();

        if ($vinculo) {
            $request['cargo'] = $vinculo->cargo->nome;
            $request['secretaria'] = $vinculo->secretaria->sigla;
        }

        // dd($request->all());


        $servidor = Servidor::create($request->only(['nome', 'cpf', 'email', 'matricula', 'telefone']));

        $request->merge(['servidor_id' => $servidor->id]);

        VinculoFuncional::create($request->except(['nome', 'email', 'matricula', 'telefone']));

        flash()->success('Servidor cadastrado com sucesso!');

        return redirect()->route('servidores.index');
    }

    public function getCargosBySecretaria($secretariaSigla)
    {
        // Aqui você implementa a lógica para buscar os cargos baseado na secretaria
        // Exemplo básico:
        $cargos = Cargo::where('secretaria_sigla', $secretariaSigla)
                       ->orWhereHas('secretaria', function($query) use ($secretariaSigla) {
                           $query->where('sigla', $secretariaSigla);
                       })
                       ->get();

        return response()->json([
            'cargos' => $cargos
        ]);
    }
    public function edit(Request $request, $servidorId)
    {
        // dd($servidorId, $request->all());

        $data = [];
        $servidor = Servidor::with(['vinculos'])->findOrFail($servidorId);


        $secretarias = Secretaria::all();
        $cargos = Cargo::orderBy('nome')->get();
        $simbologias = Simbologia::all();
        $cargoSecretariaSimbologias = CargoSecretariaSimbologia::all();

        foreach ($servidor->vinculos as $vinculo) {
            $vinculo->secretaria = Secretaria::where('sigla', $vinculo->secretaria)->first();
            $vinculo->cargo = Cargo::where('nome', $vinculo->cargo)->first();
        }

        // regex telefone vindo do banco de dados que esta no formato 9999999999
        $telefone = $servidor->telefone;

        if ($telefone) {
            $telefone = preg_replace('/\D/', '', $telefone); // Remove tudo que não é dígito
            if (strlen($telefone) == 10) {
                $telefone = preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $telefone);
            } elseif (strlen($telefone) == 11) {
                $telefone = preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $telefone);
            }
            $servidor->telefone = $telefone;
        }

        $data = [
            'servidor' => $servidor,
            'secretarias' => $secretarias,
            'cargos' => $cargos,
            'simbologias' => $simbologias,
            'cargoSecretariaSimbologias' => $cargoSecretariaSimbologias,
            'id' => $servidorId
        ];

        // dd($servidor->vinculos);

        return view('servidores.edit', $data);
    }

    public function update(Request $request, $servidorId)
    {
        // dd($request->all(), $servidorId);
        $request->validate([
            'cpf' => ['nullable', 'regex:/^\d{3}\.\d{3}\.\d{3}\-\d{2}$/']
        ]);
        $request['is_diretor'] = $request->is_diretor == 'Sim' ? 1 : 0;
        $request['logradouro'] = $request->logradouro ?? null;

        // $request['secretaria'] = isset($request['secretaria']) ? Secretaria::find($request['secretaria'])->sigla : null;
        // $request['cargo'] = isset($request['cargo']) ? Cargo::find($request['cargo'])->nome : null;


        if($request->filled('data_nascimento')) {
            $request['data_nascimento'] = Carbon::createFromFormat('d/m/Y', $request['data_nascimento'])->format('Y-m-d');
        }
        $vinculo = CargoSecretariaSimbologia::where('cargo_id', $request['cargo'])
                    ->where('secretaria_id', $request['secretaria'])
                    ->with(['cargo', 'secretaria'])
                    ->first();

        if ($vinculo) {
            $request['cargo'] = $vinculo->cargo->nome;
            $request['secretaria'] = $vinculo->secretaria->sigla;
        }


        if ($request->filled('data_expedicao')) {
            $request['data_expedicao'] = Carbon::createFromFormat('d/m/Y', $request['data_expedicao'])->format('Y-m-d');
        }
        if($request->filled('data_saida')) {
            $request['data_saida'] = Carbon::createFromFormat('d/m/Y', $request['data_saida'])->format('Y-m-d');
        }

        // dd($request->all());

        $servidor = Servidor::findOrFail($servidorId);
        $servidor->update($request->only(['nome', 'cpf', 'email', 'matricula', 'telefone']));

        $vinculo = VinculoFuncional::where('servidor_id', $servidorId)->first();
        if ($vinculo) {
            $vinculo->update($request->except(['nome', 'cpf','email', 'matricula', 'telefone']));
        } else {
            $request->merge(['servidor_id' => $servidor->id]);
            VinculoFuncional::create($request->except(['nome', 'email', 'matricula', 'telefone']));
        }

        flash()->success('Servidor atualizado com sucesso!');

        return redirect()->route('servidores.index');
    }

    // public function store(Request $request)
    // {

    //     // $request->validate([
    //     //     'cpf' => ['required', 'string', 'max:14', 'unique:servidores,cpf', new Cpf],
    //     // ]);
    //     $request->validate([
    //         'nome' => 'required|string|max:255',
    //         'cpf' => 'required|string|max:14|unique:servidores,cpf',
    //         // 'cpf' => ['required', 'string', 'max:14', 'unique:servidores,cpf', new Cpf],
    //         'matricula' => 'required|string|max:20|unique:servidores,matricula',
    //         'cargo' => 'nullable|string|max:100',
    //         'setor' => 'nullable|string|max:100',
    //     ]);

    //     $servidor = Servidor::create($request->all());

    //       $servidor->vinculos()->create([
    //         'cargo' => $request->cargo,
    //         'secretaria' => $request->secretaria,
    //         'lotacao' => $request->lotacao,
    //         'tipo_servidor' => $request->tipo_servidor,
    //         'departamento' => $request->departamento,
    //         'processo_implantacao' => $request->processo_implantacao,
    //         'processo_disposicao' => $request->processo_disposicao,
    //         'numero_memorando' => $request->numero_memorando,
    //         'tipo_movimentacao' => $request->tipo_movimentacao,
    //         'data_movimentacao' => $request->data_movimentacao,
    //         'ato_normativo' => $request->ato_normativo,
    //         'observacao' => $request->observacao
    //     ]);

    //     flash()->success('Servidor cadastrado com sucesso!');

    //     return redirect()->route('servidores.index'); //->with('success', 'Servidor cadastrado com sucesso!');

    // }

    public function destroy($id)
    {
        $servidor = Servidor::findOrFail($id);
        $servidor->delete();

        return response()->json(['success' => true, 'message' => 'Servidor excluído com sucesso!']);
    }

}