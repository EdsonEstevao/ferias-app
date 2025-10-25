<?php

namespace App\Http\Controllers;

use App\Models\Ferias;
use App\Models\FeriasPeriodos;
use App\Models\Servidor;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    //
    public function index()
    {
        // dd(getOnlineUsersCount());
        // Últimos lançamentos de férias (ordenados por data de criação)
        $ultimosLancamentos = Ferias::with('servidor', 'periodos')
        ->orderByDesc('created_at')
        ->take(10)
        ->paginate()
        ->withQueryString();


        $meses = collect(range(1, 12))->map(function ($mes) {
            return Carbon::create()->month($mes)->translatedFormat('F');
        });

        // dd($meses);

        $dadosPorMes = collect(range(1, 12))->map(function ($mes) {
            return FeriasPeriodos::whereMonth('inicio', $mes)->count();
        });

        // Estatísticas principais
        $totalComFerias = $this->getTotalServidoresComFerias();
        $totalInterrompidas = $this->getTotalFeriasInterrompidas();
        $totalRemarcacoes = $this->getTotalRemarcacoesPendentes();


        // Dados para o gráfico
        // $dadosPorMes = $this->getFeriasPorMes();
        // $meses = $this->getMeses();
        // Dados para o gráfico - CORRIGIDO
        $dadosGrafico = $this->getFeriasPorMes();
        $meses = $this->getMeses();

         // Últimos lançamentos
        $ultimos = $this->getUltimosLancamentos();
        $totalFeriasPlanejadas = $this->getTotalPlanejadas();

        $data = [
            'ultimos' => $ultimos, // $ultimosLancamentos,
            'meses' => $meses,
            'dadosGrafico' => $dadosGrafico,
            'totalComFerias' => $totalComFerias,
            'totalInterrompidas' => $totalInterrompidas,
            'totalRemarcacoes' => $totalRemarcacoes,
            'totalFeriasPlanejadas' => $totalFeriasPlanejadas
        ];

        return view('dashboard', $data);
        // return view('dashboard');
    }

    // public function index()
    // {
    //     // Estatísticas principais
    //     $totalComFerias = $this->getTotalServidoresComFerias();
    //     $totalInterrompidas = $this->getTotalFeriasInterrompidas();
    //     $totalRemarcacoes = $this->getTotalRemarcacoesPendentes();

    //     // Dados para o gráfico
    //     $dadosPorMes = $this->getFeriasPorMes();
    //     $meses = $this->getMeses();

    //     // Últimos lançamentos
    //     $ultimos = $this->getUltimosLancamentos();

    //     return view('dashboard', compact(
    //         'totalComFerias',
    //         'totalInterrompidas',
    //         'totalRemarcacoes',
    //         'dadosPorMes',
    //         'meses',
    //         'ultimos'
    //     ));
    // }

    /**
     * Total de servidores com férias lançadas no ano atual
     */
    private function getTotalPlanejadas()
    {


        $ferias = FeriasPeriodos::whereNull('periodo_origem_id')
            ->where('situacao', 'Planejado')
            ->where('ativo', true)
            ->count();

        // dd($ferias);

        return $ferias;

        // Alternativa: contar servidores únicos com férias em qualquer ano
        // return Servidor::has('ferias')->count();
    }
    private function getTotalServidoresComFerias()
    {
        $ferias = Ferias::with('periodos')->where('ano_exercicio', date('Y'))
            ->distinct('servidor_id')
            ->count('servidor_id');

        return $ferias;

        // Alternativa: contar servidores únicos com férias em qualquer ano
        // return Servidor::has('ferias')->count();
    }

    /**
     * Total de férias interrompidas
     */
    private function getTotalFeriasInterrompidas()
    {
        return FeriasPeriodos::where('situacao', 'Interrompido')
            ->where('ativo', true)
            ->count();
    }

    /**
     * Total de remarcações pendentes
     */
    private function getTotalRemarcacoesPendentes()
    {
        // return FeriasPeriodos::whereHas('periodoOrigem')
        //     ->where('situacao', 'Planejado')
        //     ->where('ativo', true)
        //     ->count();

        // Alternativa: contar períodos que são remarcações
        return FeriasPeriodos::whereNotNull('periodo_origem_id')
            ->where('situacao', 'Remarcado')
            ->where('ativo', true)
            ->count();
    }

    /**
     * Distribuição de férias por mês (ano atual)
     */
    private function getFeriasPorMes()
    {
        $anoAtual = date('Y');

        $dados = FeriasPeriodos::select(
                DB::raw('MONTH(inicio) as mes'),
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('inicio', $anoAtual)
            ->where('ativo', true)
            ->groupBy(DB::raw('MONTH(inicio)'))
            ->orderBy('mes')
            ->pluck('total', 'mes')
            ->toArray();
        // dd($dados);

        // Preencher todos os meses com 0 onde não há dados
        $dadosCompletos = [];
        for ($mes = 1; $mes <= 12; $mes++) {
            $dadosCompletos[$mes] = $dados[$mes] ?? 0;
        }

        return array_values($dadosCompletos);
    }

    /**
     * Nomes dos meses para o gráfico
     */
    private function getMeses()
    {
        return [
            'Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun',
            'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'
        ];
    }

    /**
     * Últimos lançamentos de férias
     */
    private function getUltimosLancamentos()
    {
        return Ferias::with(['servidor', 'periodos' => function($query) {
                $query->where('ativo', true)
                      ->orderBy('inicio');
            }])
            ->whereHas('periodos', function($query) {
                $query->where('ativo', true);
            })
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->paginate()->withQueryString();
    }

    /**
     * API para dados do dashboard (se precisar atualizar via AJAX)
     */
    public function dadosDashboard()
    {
        $totalComFerias = $this->getTotalServidoresComFerias();
        $totalInterrompidas = $this->getTotalFeriasInterrompidas();
        $totalRemarcacoes = $this->getTotalRemarcacoesPendentes();
        $dadosPorMes = $this->getFeriasPorMes();

        return response()->json([
            'totalComFerias' => $totalComFerias,
            'totalInterrompidas' => $totalInterrompidas,
            'totalRemarcacoes' => $totalRemarcacoes,
            'dadosPorMes' => $dadosPorMes,
            'meses' => $this->getMeses()
        ]);
    }

    /**
     * Estatísticas detalhadas por situação
     */
    public function estatisticasDetalhadas()
    {
        $situacoes = FeriasPeriodos::select('situacao', DB::raw('COUNT(*) as total'))
            ->where('ativo', true)
            ->groupBy('situacao')
            ->pluck('total', 'situacao')
            ->toArray();

        $feriasPorAno = Ferias::select('ano_exercicio', DB::raw('COUNT(*) as total'))
            ->groupBy('ano_exercicio')
            ->orderBy('ano_exercicio', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'situacoes' => $situacoes,
            'feriasPorAno' => $feriasPorAno
        ]);
    }
}
