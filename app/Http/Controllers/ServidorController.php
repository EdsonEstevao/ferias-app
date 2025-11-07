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
    public function index(Request $request) {
        $data = [];
        // $servidores = Servidor::with(['vinculos' => function($query) {
        //     $query->where('status', 'Ativo');
        // }])->get();

         $query = Servidor::with(['vinculos' => function($query) {
            $query->where('status', 'Ativo');
        }]);

        // Busca por nome, CPF ou matrícula
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nome', 'LIKE', "%{$search}%")
                  ->orWhere('cpf', 'LIKE', "%{$search}%")
                  ->orWhere('matricula', 'LIKE', "%{$search}%");
            });
        }

        $servidores = $query->orderBy('nome')->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'servidores' => $servidores->items(),
                'pagination' => [
                    'current_page' => $servidores->currentPage(),
                    'last_page' => $servidores->lastPage(),
                    'per_page' => $servidores->perPage(),
                    'total' => $servidores->total(),
                    'from' => $servidores->firstItem(),
                    'to' => $servidores->lastItem(),
                ]
            ]);
        }

        return view('servidores.index', compact('servidores'));


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
        return view('servidores.cadastro-servidor', $data);
        // return view('servidores.create', $data);
    }
    public function storeServidor(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'cpf' => ['nullable', 'regex:/^\d{3}\.\d{3}\.\d{3}\-\d{2}$/']
        ]);

        $servidor = Servidor::create($request->only(['nome', 'cpf', 'email', 'matricula', 'telefone']));

        flash()->success('Servidor cadastrado com sucesso!');

        return redirect()->route('servidores.index');
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
            })->get();

        return response()->json([
            'cargos' => $cargos
        ]);
    }

     // No ServidorController
    public function porDepartamento()
    {

        // Usar o relacionamento vinculoAtual para pegar apenas o vínculo atual de cada servidor
    $servidoresComVinculoAtual = Servidor::with(['vinculoAtual' => function($query) {
                                    $query->ativos();
                                }])
                                ->whereHas('vinculoAtual') // Apenas servidores com vínculo ativo
                                ->get();

    // Agrupar por departamento através do vínculo atual
    $servidoresPorDepartamento = $servidoresComVinculoAtual
        ->filter(function($servidor) {
            return !is_null($servidor->vinculoAtual);
        })
        ->groupBy(function($servidor) {
            return $servidor->vinculoAtual->departamento;
        })
        ->map(function($servidores) {
            return $servidores->sortBy('nome');
        })
        ->sortBy(function($servidores, $departamento) {
            return $departamento;
        });

        // Restante do código...
        $departamentoMaisPopuloso = $servidoresPorDepartamento->sortByDesc(function ($servidores) {
            return $servidores->count();
        })->first();

        $departamentosComUm = $servidoresPorDepartamento->filter(function ($servidores) {
            return $servidores->count() === 1;
        })->count();

    // return view('servidores.por-departamento', [
    //     'departamentoMaisPopuloso' => $departamentoMaisPopuloso,
    //     'servidoresPorDepartamento' => $servidoresPorDepartamento,
    //     'departamentosComUm' => $departamentosComUm
    // ]);

        $data = [
            'departamentoMaisPopuloso' => $departamentoMaisPopuloso,
            'servidoresPorDepartamento' => $servidoresPorDepartamento,
            'departamentosComUm' => $departamentosComUm
        ];

        return view('servidores.por-departamento', $data);
    }
    public function edit(Request $request, $servidorId)
    {
        // dd($servidorId, $request->all());

        $data = [];
        $servidor = Servidor::with(['vinculos'])->findOrFail($servidorId);

        $vinculo = $servidor->vinculoAtual()->first();


        // dd($servidor);


        $secretarias = Secretaria::whereNull('secretaria_origem_id')->orderBy('sigla')->get();
        $cargos = Cargo::orderBy('nome')->get();
        $simbologias = Simbologia::all();
        $cargoSecretariaSimbologias = CargoSecretariaSimbologia::all();

        // foreach ($servidor->vinculos as $vinculo) {
        //     $vinculo->secretaria = Secretaria::where('sigla', $vinculo->secretaria)->first();
        //     $vinculo->cargo = Cargo::where('nome', $vinculo->cargo)->first();
        // }

        // dd($servidor);

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
            'id' => $servidorId,
            'vinculo' => $vinculo,
        ];

        // dd($servidor, $vinculo);

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

    public function show(Servidor $servidor)
    {
        // servidor for null
        if (!$servidor) {
            flash()->error('Servidor não encontrado.');
            return redirect()->route('servidores.index');
        }


        $servidor->load(['vinculos' => function($query) {
            // $query->where('status', 'Ativo');
            $query->latest();
            // $query->latest()->with('secretaria', 'cargo');
        }]);

        $vinculoAtual = $servidor->vinculos->first();

        // if ($vinculoAtual) {
        //     $vinculoAtual->secretaria = Secretaria::where('sigla', $vinculoAtual->secretaria)->first();
        //     $vinculoAtual->cargo = Cargo::where('nome', $vinculoAtual->cargo)->first();
        // }

        // dd($servidor);



        return view('servidores.show', compact('servidor', 'vinculoAtual'));
    }



    public function destroy($id)
    {
        $servidor = Servidor::findOrFail($id);
        $servidor->delete();

        return response()->json(['success' => true, 'message' => 'Servidor excluído com sucesso!']);
    }

}