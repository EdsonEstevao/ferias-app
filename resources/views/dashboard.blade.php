<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Cards de Estatísticas -->
                    <div class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-3">
                        <div class="p-4 bg-white rounded shadow">
                            <h3 class="text-sm font-semibold text-gray-600">Servidores com férias lançadas</h3>
                            <p class="text-2xl font-bold text-blue-600">{{ $totalComFerias }}</p>
                            <p class="text-xs text-gray-500">Ano {{ date('Y') }}</p>
                        </div>
                        <div class="p-4 bg-white rounded shadow">
                            <h3 class="text-sm font-semibold text-gray-600">Férias interrompidas</h3>
                            <p class="text-2xl font-bold text-red-600">{{ $totalInterrompidas }}</p>
                            <p class="text-xs text-gray-500">Períodos ativos</p>
                        </div>
                        <div class="p-4 bg-white rounded shadow">
                            <h3 class="text-sm font-semibold text-gray-600">Férias Remarcadas</h3>
                            <p class="text-2xl font-bold text-yellow-600">{{ $totalRemarcacoes }}</p>
                            <p class="text-xs text-gray-500">Ativos</p>
                        </div>
                    </div>

                    <!-- Gráficos -->
                    <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-2">
                        <div class="p-6 bg-white rounded shadow">
                            <h3 class="mb-4 text-lg font-bold">📊 Distribuição de Férias por Mês - {{ date('Y') }}
                            </h3>
                            <canvas id="graficoFeriasPorMes" height="200"></canvas>
                        </div>

                        <div class="p-6 bg-white rounded shadow">
                            <h3 class="mb-4 text-lg font-bold">📈 Situação dos Períodos</h3>
                            <canvas id="graficoSituacoes" height="200"></canvas>
                        </div>
                    </div>

                    <!-- Últimos lançamentos -->
                    <div class="p-6 bg-white rounded shadow">
                        <h3 class="mb-4 text-lg font-bold">🕒 Últimos Lançamentos</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm bg-white rounded shadow">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-2 text-left">Servidor</th>
                                        <th class="px-4 py-2 text-left">Ano</th>
                                        <th class="px-4 py-2 text-left">Períodos</th>
                                        <th class="px-4 py-2 text-left">Situação</th>
                                        <th class="px-4 py-2 text-left">Períodos</th>
                                        <th class="px-4 py-2 text-left">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($ultimos as $ferias)
                                        <tr class="border-t hover:bg-gray-50">
                                            <td class="px-4 py-2">
                                                <div class="font-medium">{{ $ferias->servidor->nome }}</div>
                                                <div class="text-xs text-gray-500">{{ $ferias->servidor->matricula }}
                                                </div>
                                            </td>
                                            <td class="px-4 py-2">
                                                <span class="px-2 py-1 text-xs bg-blue-100 rounded-full">
                                                    {{ $ferias->ano_exercicio }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-2">
                                                <span
                                                    class="font-semibold">{{ $ferias->periodos->where('ativo', true)->count() }}</span>
                                            </td>
                                            <td class="px-4 py-2">
                                                @foreach ($ferias->periodos->where('ativo', true)->take(2) as $periodo)
                                                    <span
                                                        class="inline-block px-2 py-1 mb-1 text-xs rounded-full
                                                        {{ $periodo->situacao == 'Planejado'
                                                            ? 'bg-green-100 text-green-800'
                                                            : ($periodo->situacao == 'Interrompido'
                                                                ? 'bg-red-100 text-red-800'
                                                                : 'bg-yellow-100 text-yellow-800') }}">
                                                        {{ $periodo->situacao }}
                                                    </span>
                                                    @if (!$loop->last)
                                                        <br>
                                                    @endif
                                                @endforeach
                                                @if ($ferias->periodos->where('ativo', true)->count() > 2)
                                                    <span
                                                        class="text-xs text-gray-500">+{{ $ferias->periodos->where('ativo', true)->count() - 2 }}
                                                        mais</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-2">
                                                <div class="flex flex-col gap-2">
                                                    @foreach ($ferias->periodos->where('ativo', true)->take(2) as $periodo)
                                                        <div
                                                            class="text-xs {{ $periodo->tipo === 'Abono' ? 'bg-red-200 px-2 py-1 rounded-md w-max' : '' }}">
                                                            {{ date('d/m/Y', strtotime($periodo->inicio)) }} -
                                                            {{ date('d/m/Y', strtotime($periodo->fim)) }}
                                                            ({{ $periodo->dias }} dias - {{ $periodo->tipo }})
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="px-4 py-2">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('ferias.show', $ferias->id) }}"
                                                        class="px-3 py-1 text-xs text-blue-600 bg-blue-100 rounded hover:bg-blue-200 text-nowrap">
                                                        👁️ Ver
                                                    </a>
                                                    @if ($ferias->periodos->where('ativo', true)->whereIn('situacao', ['Planejado', 'Interrompido', 'Remarcado'])->count() > 0)
                                                        <button
                                                            class="px-3 py-1 text-xs text-green-600 bg-green-100 rounded hover:bg-green-200 text-nowrap">
                                                            🔁 Remarcar
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                                Nenhum lançamento de férias encontrado.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- CORREÇÃO: Agora $ultimos é um Paginator e tem hasPages() -->
                        @if ($ultimos->hasPages())
                            <div class="mt-4">
                                {{ $ultimos->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Gráfico de Férias por Mês
        const ctxMes = document.getElementById('graficoFeriasPorMes').getContext('2d');
        new Chart(ctxMes, {
            type: 'bar',
            data: {
                labels: {{ Js::from($meses) }},
                datasets: [{
                    label: 'Períodos de Férias',
                    data: {{ Js::from($dadosGrafico) }},
                    backgroundColor: 'rgba(59, 130, 246, 0.6)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Quantidade de Períodos'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Meses'
                        }
                    }
                }
            }
        });

        // Gráfico de Situações (opcional - você pode implementar depois)
        const ctxSituacao = document.getElementById('graficoSituacoes').getContext('2d');

        // Dados de exemplo para o gráfico de situações
        console.log('Total com férias:', {{ Js::from($totalComFerias) }}, 'total interrompidas:',
            {{ Js::from($totalInterrompidas) }}, 'total remarcacoes:', {{ Js::from($totalRemarcacoes) }});
        const dadosSituacoes = {

            labels: ['Planejado', 'Interrompido', 'Remarcado', 'Outros'],
            datasets: [{
                data: [{{ $totalFeriasPlanejadas - $totalRemarcacoes }},
                    // data: [{{ $totalComFerias - $totalInterrompidas - $totalRemarcacoes }},
                    // data: [{{ $totalComFerias }},
                    {{ $totalInterrompidas }}, {{ $totalRemarcacoes }}, 0
                ],
                backgroundColor: [
                    'rgba(34, 197, 94, 0.6)', // Verde - Planejado
                    'rgba(239, 68, 68, 0.6)', // Vermelho - Interrompido
                    'rgba(245, 158, 11, 0.6)', // Amarelo - Remarcado
                    'rgba(156, 163, 175, 0.6)' // Cinza - Outros
                ],
                borderWidth: 1
            }]
        };

        new Chart(ctxSituacao, {
            type: 'doughnut',
            data: dadosSituacoes,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>

</x-app-layout>
