<?php

namespace App\Http\Controllers;

use App\Exports\MovimentacoesExport;
use App\Models\Servidor;
use App\Models\VinculoFuncional;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class VinculoFuncionalController extends Controller
{
    //
    public function index(Request $request)
    {
        $query = VinculoFuncional::with('servidor')
            ->when($request->servidor, fn($q) => $q->whereHas('servidor', fn($s) =>
                $s->where('nome', 'like', "%{$request->servidor}%")))
            ->when($request->cargo, fn($q) => $q->where('cargo', 'like', "%{$request->cargo}%"))
            ->when($request->secretaria, fn($q) => $q->where('secretaria', 'like', "%{$request->secretaria}%"))
            ->when($request->tipo, fn($q) => $q->where('tipo_movimentacao', $request->tipo));

        return response()->json($query->orderByDesc('data_movimentacao')->get());
    }
    public function store(Request $request)
    {
        $servidor = Servidor::firstOrCreate(['nome' => $request->servidor]);

        $servidor->vinculos()->create([
            'cargo' => $request->cargo,
            'secretaria' => $request->secretaria,
            'tipo_movimentacao' => $request->tipo_movimentacao,
            'data_movimentacao' => $request->data_movimentacao,
            'ato_normativo' => $request->ato_normativo,
            'observacao' => $request->observacao
        ]);

        return response()->json(['status' => 'ok']);
    }

    public function pdf(Request $request)
    {
        $movimentacoes = $this->index($request)->getData();
        $pdf = Pdf::loadView('relatorios.movimentacoes', compact('movimentacoes'));
        return $pdf->stream('relatorio-movimentacoes.pdf');
    }

    public function excel(Request $request)
    {
        $movimentacoes = $this->index($request)->getData();

        return Excel::download(new MovimentacoesExport($movimentacoes), 'movimentacoes.xlsx');
    }

    // public function excel(Request $request)
    // {
    //     $movimentacoes = $this->index($request)->getData();
    //     return Excel::download(new MovimentacoesExport($movimentacoes), 'movimentacoes.xlsx');
    // }
}
