<?php

namespace App\Http\Controllers;

use App\Models\Cargo;
use App\Models\Secretaria;
use App\Models\Servidor;
use App\Models\VinculoFuncional;
use Illuminate\Http\Request;

class NomeacaoController extends Controller
{

     public function index(Request $request)
    {
        // dd('aqu...nomeados');


        $query = Servidor::with(['vinculoAtual']);

        // Filtro de busca
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                  ->orWhere('matricula', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filtro por situação
        if ($request->filled('status')) {
            if ($request->status === 'ativo') {
                $query->whereHas('vinculos', function($q) {
                    $q->ativos();
                });
            } elseif ($request->status === 'inativo') {
                $query->whereDoesntHave('vinculos', function($q) {
                    $q->ativos();
                });
            }
        }

        // Filtro por departamento
        if ($request->filled('departamento')) {
            $query->whereHas('vinculos', function($q) use ($request) {
                $q->where('departamento', $request->departamento);
            });
        }

        // Ordenação
        $sort = $request->get('sort', 'nome');
        switch ($sort) {
            case 'nome_desc':
                $query->orderBy('nome', 'desc');
                break;
            case 'matricula':
                $query->orderBy('matricula');
                break;
            case 'departamento':
                $query->orderBy(
                    VinculoFuncional::select('departamento')
                        ->whereColumn('servidor_id', 'servidores.id')
                        ->latest()
                        ->limit(1)
                );
                break;
            default:
                $query->orderBy('nome');
        }

        $servidores = $query->paginate(15);

        // Estatísticas
        $totalServidores = Servidor::count();
        $ativos = Servidor::whereHas('vinculos', function($q) {
            $q->ativos();
        })->count();
        $inativos = $totalServidores - $ativos;
        $semVinculo = Servidor::whereDoesntHave('vinculos')->count();

        // Departamentos para filtro
        $departamentos = VinculoFuncional::distinct()
            ->whereNotNull('departamento')
            ->pluck('departamento')
            ->sort();

        return view('servidores.nomeacao.index', compact(
            'servidores',
            'totalServidores',
            'ativos',
            'inativos',
            'semVinculo',
            'departamentos'
        ));
    }


    //
    /**
     * Formulário de nomeação de servidor
     */
    public function create(Servidor $servidor)
    {
        //verificar se o servidor já possui vínculo ativo
        if ($servidor->vinculos()->whereNull('data_saida')->count() > 0) {
            flash()->error('Servidor possui vínculo funcional ativo.');
            return redirect()->route('servidores.show', $servidor);
        }




        // Carregar secretarias e cargos
        $secretarias = Secretaria::orderBy('sigla')->get();
        $cargos = Cargo::orderBy('nome')->get();

        $data = [
            'servidor' => $servidor,
            'secretarias' => $secretarias,
            'cargos' => $cargos,
        ];

        return view('servidores.nomeacao.create', $data);
        // return view('servidores.create', $data);
    }

    public function store(Request $request, Servidor $servidor)
    {
        // dd($request->all());

        $validatedData = $request->validate([
            'secretaria_id' => 'required|exists:secretarias,sigla',
            'cargo_id' => 'required|exists:cargos,id',
            'lotacao' => 'required|string|max:255',
            'departamento' => 'nullable|string|max:255',
            'local_trabalho' => 'nullable|string|max:255',
            'tipo_servidor' => 'required|array',
            'tipo_servidor.*' => 'in:federal,cedido,interno,disponibilizado,regional',
            'sexo' => 'required|in:Masculino,Feminino,Outro',
            'is_diretor' => 'required|boolean',
            'data_movimentacao' => 'required|date',
            'processo_implantacao' => 'nullable|string|max:100',
            'numero_memorando' => 'nullable|string|max:100',
            'ato_normativo' => 'nullable|string|max:200',
            'observacao' => 'nullable|string|max:1000',
        ]);
        $validatedData['cargo'] = Cargo::find($validatedData['cargo_id'])->nome;
        $validatedData['secretaria'] = Secretaria::where('sigla', $validatedData['secretaria_id'])->first()->sigla;

        // Adicionar tipo de movimentação com 'Nomeação'

        $validatedData['tipo_movimentacao'] = 'Nomeação';
        $validatedData['servidor_id'] = $servidor->id;

        // dd($validatedData);

        VinculoFuncional::create($validatedData);

        flash()->success('Nomeação realizada com sucesso!');

        return redirect()->route('servidores.show', $servidor);
    }
}
