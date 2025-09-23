<?php

namespace App\Http\Controllers;

use App\Models\Ferias;
use App\Models\FeriasEvento;
use App\Models\FeriasPeriodos;
use App\Models\Servidor;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class FeriasController extends Controller
{
    public function index(Request $request)
    {

        $ano = $request->input('ano_exercicio');
        $query = $request->input('busca');

        $query = Ferias::query()->with('servidor', 'periodos.todosFilhosRecursivos');

        if ($request->filled('ano_exercicio')) {
            $query->where('ano_exercicio', $request->ano_exercicio);
        }

        if ($request->filled('busca')) {
            $query->whereHas('servidor', function ($q) use ($request) {
                $q->where('nome', 'like', "%{$request->busca}%")
                ->orWhere('cpf', 'like', "%{$request->busca}%")
                ->orWhere('matricula', 'like', "%{$request->busca}%");
            });
        }

        if ($request->filled('mes')) {
            $query->whereHas('periodos', function ($q) use ($request) {
                $q->whereMonth('inicio', $request->mes);
                $q->where('ativo', 1);
            });
        }

        $ferias = $query->paginate(10);

        return view('ferias.index', compact('ferias'));

        // $data = [

        //     'ferias' => $ferias,
        // ];

        // // dd($ferias);

        // return view('ferias.index', $data);

    }

    public function create(Request $request)
    {
        // dd('create');
        if (Auth::user()->hasRole('servidor')) {
            return redirect()->route('ferias.index');
        }
        $servidorId = $request->servidorId;
        $data = [
            'servidorId' => $servidorId,
            'servidores'=> Servidor::all()
        ];
        return view('ferias.create', $data);

    }
    //
    public function store(Request $request)
    {
        // verificar se os periodos somam o total de ferias de 30 dias

        $request->validate([
            'ano_exercicio' => 'required|integer',
            'periodos.*.tipo' => 'required|in:Férias,Abono',
            'periodos.*.inicio' => 'required|date',
            'periodos.*.fim' => 'required|date|after_or_equal:periodos.*.inicio',
        ]);
        $diasFerias = 0;
        foreach ($request->periodos as $periodo) {
            if ($periodo['tipo'] == 'Férias') {
                $diasFerias += Carbon::parse($periodo['fim'])->diffInDays(Carbon::parse($periodo['inicio'])) + 1;
            }
        }
        if ($diasFerias != 30) {
            return redirect()->back()->with('error', 'A soma dos dias de férias deve ser 30 dias!');
        }



        $ferias = Ferias::create([
            'servidor_id' => Auth::user()->servidor_id,
            'ano_exercicio' => $request->ano_exercicio,
        ]);

        foreach ($request->periodos as $periodo) {
            if (!empty($periodo['dias'])) {
                FeriasPeriodos::create([
                    'ferias_id' => $ferias->id,
                    'tipo' => $periodo['tipo'],
                    'dias' => $periodo['dias'],
                    'inicio' => $periodo['inicio'],
                    'fim' => $periodo['fim'],
                ]);
            }
        }

        return redirect()->back()->with('success', 'Férias lançadas com sucesso!');

        //
    }


    // public function interromperPeriodo($periodoId)
    // {
    //     $periodo = FeriasPeriodos::findOrFail($periodoId);
    //     $periodo->situacao = 'Interrompido';
    //     $periodo->ativo = false;
    //     $periodo->save();

    //     FeriasEvento::create([
    //         'ferias_periodo_id' => $periodo->id,
    //         'acao' => 'Interrupção',
    //         'descricao' => 'Período interrompido por necessidade do serviço',
    //         'data_acao' => now(),
    //         'user_id' => Auth::id(), //auth()->id(),
    //     ]);
    // }

    public function interromper(Request $request)
    {
        $data = $request->validate([
            'periodo_id' => 'required|exists:ferias_periodos,id',
            'data' => 'required|date',
            'motivo' => 'required|string|max:255',
        ]);

        $periodo = FeriasPeriodos::find($data['periodo_id']);

        // Atualiza o período original
        $periodo->ativo = 0;
        $periodo->save();

        $novo = FeriasPeriodos::create([
            'ferias_id' => $periodo->ferias_id,
            'tipo' => $periodo->tipo,
            'dias' => $periodo->dias,
            'ordem' => $periodo->ordem,
            'inicio' => $data['data'],
            'fim' => $periodo->fim,
            'situacao' => 'Interrompido',
            'justificativa' => $data['motivo'],
            'periodo_origem_id' => $periodo->id
        ]);

        // Registra evento
        $periodo->eventos()->create([
            'acao' => 'Interrupção',
            'data_acao' => now(),
            'descricao' => $data['motivo'],
            'user_id' => Auth::id(),
        ]);

        return response()->json(['success' => true, 'message' => 'Férias interrompidas com sucesso!']);
    }

    public function interromperFerias(Request $request)
    {
        // dd($request->all());
        $data = [];
        // $ferias = Ferias::findOrFail($request->ferias_id);
        $servidor_id = $request->servidorId;

        $ferias = Ferias::with([
                'servidor',
                'periodos.eventos' // carrega períodos e eventos juntos
            ])
            ->when($servidor_id, function ($query) use ($servidor_id) {
                $query->where('servidor_id', $servidor_id);
            })
            ->orderBy('ano_exercicio', 'desc')->get();
            //->paginate(10)->withQueryString();

        $data['feriass'] = $ferias;

        // dd($ferias);
        return view('ferias.interromper', $data);
    }

    public function remarcarPeriodo($periodoId, $novaDataInicio, $novaDataFim)
    {
        $original = FeriasPeriodos::findOrFail($periodoId);

        $novo = FeriasPeriodos::create([
            'ferias_id' => $original->ferias_id,
            'tipo' => $original->tipo,
            'dias' => Carbon::parse($novaDataInicio)->diffInDays($novaDataFim) + 1,
            'inicio' => $novaDataInicio,
            'fim' => $novaDataFim,
            'situacao' => 'Remarcado',
            'justificativa' => 'Remarcação após interrupção',
            'periodo_origem_id' => $original->id
        ]);

        FeriasEvento::create([
            'ferias_periodo_id' => $novo->id,
            'acao' => 'Remarcação',
            'descricao' => 'Novo período remarcado após interrupção',
            'data_acao' => now(),
            'user_id' => Auth::id(), //auth()->id(),
        ]);
    }

    public function remarcar(Request $request)
    {
        $data = $request->validate([
            'periodo_id' => 'required|exists:ferias_periodos,id',
            'nova_inicio' => 'required|date',
            'nova_fim' => 'required|date|after_or_equal:nova_inicio',
            'justificativa' => 'required|string|max:255',
        ]);

        $original = FeriasPeriodos::find($data['periodo_id']);

         $original->ativo = 0;
         $original->save();

        $dias = Carbon::parse($data['nova_inicio'])->diffInDays($data['nova_fim']) + 1;

        $remarcado = FeriasPeriodos::create([
            'ferias_id' => $original->ferias_id,
            'tipo' => $original->tipo,
            'ordem' => $original->ordem,
            'inicio' => $data['nova_inicio'],
            'fim' => $data['nova_fim'],
            'dias' => $dias,
            'situacao' => 'Remarcado',
            'justificativa' => $data['justificativa'],
            'periodo_origem_id' => $original->id,
        ]);

        $remarcado->eventos()->create([
            'acao' => 'Remarcação',
            'data_acao' => now(),
            'descricao' => $data['justificativa'],
            'user_id' => Auth::id(),
        ]);

        return response()->json(['success' => true, 'message' => 'Férias remarcadas com sucesso!']);
    }

    // public function lancar()
    // {
    //     $servidores = Servidor::orderBy('nome')->get();
    //     return view('ferias.lancar', compact('servidores'));
    // }

    public function salvarTodos(Request $request)
    {

         $data = $request->validate([
            'servidorId' => 'required|exists:servidores,id',
            'ano_exercicio' => 'required|digits:4',
            'periodos' => 'required|array|min:1',
            'periodos.*.inicio' => 'required|date',
            'periodos.*.fim' => 'required|date|after_or_equal:periodos.*.inicio',
            'periodos.*.tipo' => 'required|in:Férias,Abono',
        ]);

        $servidor = Servidor::findOrFail($request->servidorId);

        // dd($servidor);



        $ferias = Ferias::create([
            'servidor_id' => $servidor->id,
            'ano_exercicio' => $request->ano_exercicio,
        ]);

        $periodoFerias = [];

        foreach ($request->periodos as $i => $periodo) {
            $dias = Carbon::parse($periodo['inicio'])->diffInDays($periodo['fim']) + 1;
            if (!empty($periodo['dias'])) {
               $periodoFerias = FeriasPeriodos::create([
                    'ferias_id' => $ferias->id,
                    'ordem' => $i + 1,
                    'tipo' => $periodo['tipo'],
                    'dias' => $periodo['dias'],
                    'inicio' => $periodo['inicio'],
                    'fim' => $periodo['fim'],
                ]);
            }
        }

        // if(!$periodoFerias){
        //     // return redirect()->back()->with('error', 'Nenhum período de férias foi cadastrado!');
        //     return response()->json(['success' => false, 'message' => 'Nenhum período de férias foi cadastrado!']);
        // }


        return response()->json(['success' => true, 'message' => 'Férias lançadas com sucesso!']);
        // return response()->json(['success' => true]);
        // lógica para salvar os períodos
    }

    public function fracionar(Request $request)
    {
        $request->validate([
            'periodo_id' => 'required|exists:ferias_periodos,id',
            'periodos' => 'required|array|min:2|max:3',
            'periodos.*.inicio' => 'required|date',
            'periodos.*.fim' => 'required|date|after_or_equal:periodos.*.inicio',
        ]);

        $original = FeriasPeriodos::find($request->periodo_id);
        $ferias = $original->ferias;

        $original->justificativa = 'Fracionamento de férias';
        $original->ativo = 0;
        $original->save();


        foreach ($request->periodos as $index => $p) {
            $inicio = Carbon::parse($p['inicio']);
            $fim = Carbon::parse($p['fim']);
            $dias = $inicio->diffInDays($fim) + 1;

            if ($dias < 5) {
                return response()->json(['message' => 'Cada período deve ter no mínimo 5 dias.'], 422);
            }

            FeriasPeriodos::create([
                'ferias_id' => $ferias->id,
                'inicio' => $inicio,
                'fim' => $fim,
                'dias' => $dias,
                'ordem' => $index + 1,
                'tipo' => 'Férias',
                'periodo_origem_id' => $original->id,
                'situacao' => 'Remarcado',
                'justificativa' => 'Fracionamento de férias',
            ]);

            $original->eventos()->create([
                'acao' => 'Remarcação',
                'data_acao' => now(),
                'descricao' => 'Férias fracionadas',
                'user_id' => Auth::id(),
            ]);
        }
        flash()->success('Férias fracionadas com sucesso!');
        return response()->json(['message' => 'Férias fracionadas com sucesso!']);
    }

    public function gerarPdf(Servidor $servidor)
    {

        // ferias do servidor com periodos ativos e ordem crescente
        $servidor = Servidor::findOrFail($servidor->id);
        $ferias = $servidor->ferias()->with(['periodos' => function ($q) {
            $q->where('ativo', 1)->orderBy('ordem');
        }])->get();

        $ferias->each(function ($registro) {
            $registro->periodosAgrupados = $registro->periodos->groupBy('periodo_origem_id');
        });

        $pdf = Pdf::loadView('ferias.pdf', compact('servidor', 'ferias'));

        return $pdf->download("ferias_{$servidor->matricula}.pdf");
    }


}
