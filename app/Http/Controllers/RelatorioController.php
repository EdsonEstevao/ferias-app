<?php

namespace App\Http\Controllers;

use App\Models\Servidor;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use Dompdf\Options;
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

        $anoExercicio = $request->input('ano_exercicio') ?? now()->year;
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


        $ferias = [];

        foreach ($servidores as $servidor) {
            $ferias[] = $servidor->ferias;
        }

        if (empty($ferias) || collect($ferias)->every(fn($c) => $c->isEmpty())) {
            // Nada para mostrar
            // dd('Nada para mostrar');
            flash()->warning('Nenhum servidor com ferias ativas');
            return redirect()->back();
        }

        $data = [
            'servidores' => $servidores,
            'anoExercicio' => $anoExercicio,
            'anoInicio' => $anoInicio,
            'mesInicio' => $mesInicio
        ];


        $pdf = Pdf::loadView('relatorios.ferias_ativas', $data);

        return $pdf->stream("ferias_ativas_{$anoExercicio}_{$anoInicio}_{$mesInicio}.pdf");
        // return $pdf->download("ferias_ativas_{$anoExercicio}_{$anoInicio}_{$mesInicio}.pdf");

    }

    public function verificarFerias(Request $request) {
        $anoExercicio = $request->input('ano_exercicio');
        $anoInicio = $request->input('ano');
        $mesInicio = $request->input('mes');

        $temDados = Servidor::whereHas('ferias', function ($q) use ($anoExercicio) {
            if ($anoExercicio) {
                $q->where('ano_exercicio', $anoExercicio);
            }
            $q->whereHas('periodos', fn($p) => $p->where('ativo', 1));
        })->whereHas('ferias.periodos', function ($q) use ($anoInicio, $mesInicio) {
            $q->where('ativo', 1)
            ->when($anoInicio, fn($query) => $query->whereYear('inicio', $anoInicio))
            ->when($mesInicio, fn($query) => $query->whereMonth('inicio', $mesInicio));
        })->exists();

        flash()->warning('Nenhum servidor com ferias ativas');

        return response()->json(['tem_dados' => $temDados]);
    }
}
