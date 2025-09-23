<?php

namespace App\Http\Controllers;

use App\Models\Servidor;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class RelatorioController extends Controller
{
    //
    public function feriasAtivasPdf(Request $request)
    {
        // $servidores = Servidor::with(['ferias.periodos' => function ($q) {
        //     $q->where('ativo', 1)->orderBy('ordem');
        // }])->whereHas('ferias.periodos', function ($q) {
        //     $q->where('ativo', 1);
        // })->orderBy('nome')->get();

        // $pdf = Pdf::loadView('relatorios.ferias_ativas', compact('servidores'));

        // return $pdf->download('relatorio_ferias_ativas.pdf');

        $anoExercicio = $request->input('ano_exercicio');
        $anoInicio = $request->input('ano');
        $mesInicio = $request->input('mes');

        $servidores = Servidor::with(['ferias' => function ($q) use ($anoExercicio) {
            $q->whereHas('periodos', fn($p) => $p->where('ativo', 1));
            if ($anoExercicio) {
                $q->where('ano_exercicio', $anoExercicio);
            }
        }, 'ferias.periodos' => function ($q) use ($anoInicio, $mesInicio) {
            $q->where('ativo', 1)
            ->when($anoInicio, fn($query) => $query->whereYear('inicio', $anoInicio))
            ->when($mesInicio, fn($query) => $query->whereMonth('inicio', $mesInicio))
            ->orderBy('ordem');
        }])->whereHas('ferias.periodos', function ($q) use ($anoInicio, $mesInicio) {
            $q->where('ativo', 1)
            ->when($anoInicio, fn($query) => $query->whereYear('inicio', $anoInicio))
            ->when($mesInicio, fn($query) => $query->whereMonth('inicio', $mesInicio));
        })->orderBy('nome')->get();



        // dd($servidores, $anoExercicio, $anoInicio, $mesInicio);

        $pdf = Pdf::loadView('relatorios.ferias_ativas', compact('servidores', 'anoExercicio', 'anoInicio', 'mesInicio'));

        return $pdf->download("ferias_ativas_{$anoExercicio}_{$anoInicio}_{$mesInicio}.pdf");

    }
}