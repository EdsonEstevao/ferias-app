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
    public function index()
    {
        // Últimos lançamentos de férias
        $ultimos = $this->getUltimosLancamentos();

        // Dados para os gráficos
        $meses = $this->getMeses();
        $dadosGrafico = $this->getFeriasPorMes();

        // Estatísticas principais
        $totalComFerias = $this->getTotalServidoresComFerias();
        $totalInterrompidas = $this->getTotalFeriasInterrompidas();
        $totalRemarcacoes = $this->getTotalRemarcacoesPendentes();
        $totalFeriasPlanejadas = $this->getTotalPlanejadas();

        // Dados iniciais para o calendário
        $calendarioInicial = $this->getCalendarioInicial();

        $data = [
            'ultimos' => $ultimos,
            'meses' => $meses,
            'dadosGrafico' => $dadosGrafico,
            'totalComFerias' => $totalComFerias,
            'totalInterrompidas' => $totalInterrompidas,
            'totalRemarcacoes' => $totalRemarcacoes,
            'totalFeriasPlanejadas' => $totalFeriasPlanejadas,
            'calendarioInicial' => $calendarioInicial
        ];

        return view('dashboard', $data);
    }

    /**
     * Total de servidores com férias lançadas no ano atual
     */
    private function getTotalServidoresComFerias()
    {
        return Ferias::where('ano_exercicio', date('Y'))
            ->distinct('servidor_id')
            ->count('servidor_id');
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
        return FeriasPeriodos::whereNotNull('periodo_origem_id')
            ->where('situacao', 'Remarcado')
            ->where('ativo', true)
            ->count();
    }

    /**
     * Total de férias planejadas
     */
    private function getTotalPlanejadas()
    {
        return FeriasPeriodos::whereNull('periodo_origem_id')
            ->where('situacao', 'Planejado')
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
            ->paginate(10)->withQueryString();
    }

    /**
     * Dados iniciais para o calendário
     */
    private function getCalendarioInicial()
    {
        $anoAtual = date('Y');
        $mesAtual = date('n');

        return [
            'ano' => $anoAtual,
            'mes' => $mesAtual,
            'mes_nome' => $this->getMeses()[$mesAtual - 1],
            'periodos' => $this->getPeriodosMes($mesAtual, $anoAtual)
        ];
    }

    /**
     * Busca períodos para um mês específico
     */
    private function getPeriodosMes($mes, $ano)
    {
        return FeriasPeriodos::with(['ferias.servidor'])
            ->whereYear('inicio', $ano)
            ->whereMonth('inicio', $mes)
            ->where('ativo', true)
            ->get()
            ->map(function($periodo) {
                // Passe a situação como segundo parâmetro
                $corConfig = $this->getCorPeriodo($periodo->tipo, $periodo->situacao);

                return [
                    'id' => $periodo->id,
                    'servidor' => $periodo->ferias->servidor->nome,
                    'matricula' => $periodo->ferias->servidor->matricula,
                    'inicio' => $periodo->inicio,
                    'fim' => $periodo->fim,
                    'dias' => $periodo->dias,
                    'tipo' => $periodo->tipo,
                    'situacao' => $periodo->situacao,
                    'cor' => $corConfig,
                    'descricao' => $this->getDescricaoPeriodo($periodo)
                ];
            })->toArray();
    }

    /**
     * API para dados do calendário (usada pelo Alpine.js)
     */
    public function calendarioData(Request $request)
    {
        try {
            $mes = $request->get('mes', date('m'));
            $ano = $request->get('ano', date('Y'));

            // Validar mês e ano
            if (!is_numeric($mes) || $mes < 1 || $mes > 12) {
                $mes = date('m');
            }
            if (!is_numeric($ano) || $ano < 2020 || $ano > 2030) {
                $ano = date('Y');
            }

            $periodos = FeriasPeriodos::with(['ferias.servidor'])
                ->whereYear('inicio', $ano)
                ->whereMonth('inicio', $mes)
                ->where('ativo', true)
                ->get()
                ->map(function($periodo) {
                    $corConfig = $this->getCorPeriodo($periodo->tipo, $periodo->situacao);

                    return [
                        'id' => $periodo->id,
                        'servidor' => $periodo->ferias->servidor->nome,
                        'matricula' => $periodo->ferias->servidor->matricula,
                        'inicio' => date('Y-m-d', strtotime($periodo->inicio)),  //$periodo->inicio->format('Y-m-d'),
                        'fim' => date('Y-m-d', strtotime($periodo->fim)),        //$periodo->fim->format('Y-m-d'),
                        'dias' => $periodo->dias,
                        'tipo' => $periodo->tipo,
                        'situacao' => $periodo->situacao,
                        'cor' => $corConfig,
                        'descricao' => $this->getDescricaoPeriodo($periodo)
                    ];
                })->toArray();

            // return response()->json([
            //     'success' => true,
            //     'periodos' => $periodos,
            //     'mes_nome' => Carbon::create()->month($mes)->translatedFormat('F'),
            //     'ano' => (int)$ano,
            //     'mes' => (int)$mes
            // ]);

            return response()->json(
                $periodos,
            );

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Erro ao carregar dados do calendário: ' . $e->getMessage(),
                'periodos' => [],
                'mes_nome' => Carbon::create()->month($request->get('mes', date('m')))->translatedFormat('F'),
                'ano' => (int)$request->get('ano', date('Y')),
                'mes' => (int)$request->get('mes', date('m'))
            ], 500);
        }
    }

    /**
     * Define cor para cada tipo de período
     */
    /**
     * Define cor para cada tipo de período baseado na situação
     */
    private function getCorPeriodo($tipo, $situacao)
    {
        // Se for interrompido, sempre usa vermelho independente do tipo
        if ($situacao === 'Interrompido') {
            return [
                'bg' => 'bg-red-100 dark:bg-red-900',
                'border' => 'border-l-red-500',
                'text' => 'text-red-800 dark:text-red-200',
                'badge' => 'bg-red-500'
            ];
        }

        // Cores normais por tipo
        $cores = [
            'Férias' => [
                'bg' => 'bg-green-100 dark:bg-green-900',
                'border' => 'border-l-green-500',
                'text' => 'text-green-800 dark:text-green-200',
                'badge' => 'bg-green-500'
            ],
            'Abono' => [
                'bg' => 'bg-blue-100 dark:bg-blue-900',
                'border' => 'border-l-blue-500',
                'text' => 'text-blue-800 dark:text-blue-200',
                'badge' => 'bg-blue-500'
            ],
            'Licença' => [
                'bg' => 'bg-purple-100 dark:bg-purple-900',
                'border' => 'border-l-purple-500',
                'text' => 'text-purple-800 dark:text-purple-200',
                'badge' => 'bg-purple-500'
            ]
        ];

        return $cores[$tipo] ?? [
            'bg' => 'bg-gray-100 dark:bg-gray-900',
            'border' => 'border-l-gray-500',
            'text' => 'text-gray-800 dark:text-gray-200',
            'badge' => 'bg-gray-500'
        ];
    }

    /**
     * Gera descrição para o período
     */
    private function getDescricaoPeriodo($periodo)
    {
        $tipos = [
            'Férias' => 'Período de Férias',
            'Abono' => 'Abono Pecuniário',
            'Interrompido' => 'Férias Interrompidas'
        ];

        return ($tipos[$periodo->tipo] ?? $periodo->tipo) . ' - ' . $periodo->situacao;
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

    /**
     * Buscar períodos por servidor (para filtros futuros)
     */
    public function periodosPorServidor($servidorId)
    {
        try {
            $periodos = FeriasPeriodos::with(['ferias.servidor'])
                ->whereHas('ferias', function($query) use ($servidorId) {
                    $query->where('servidor_id', $servidorId);
                })
                ->where('ativo', true)
                ->orderBy('inicio')
                ->get()
                ->map(function($periodo) {
                    $corConfig = $this->getCorPeriodo($periodo->tipo, $periodo->situacao);

                    return [
                        'id' => $periodo->id,
                        'servidor' => $periodo->ferias->servidor->nome,
                        'matricula' => $periodo->ferias->servidor->matricula,
                        'inicio' => date('Y-m-d', strtotime($periodo->inicio)),  //$periodo->inicio->format('Y-m-d'),
                        'fim' => date('Y-m-d', strtotime($periodo->fim)),        //$periodo->fim->format('Y-m-d'),
                        'dias' => $periodo->dias,
                        'tipo' => $periodo->tipo,
                        'situacao' => $periodo->situacao,
                        'cor' => $corConfig
                    ];
                });

            return response()->json([
                'success' => true,
                'periodos' => $periodos
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Erro ao buscar períodos: ' . $e->getMessage()
            ], 500);
        }
    }
}