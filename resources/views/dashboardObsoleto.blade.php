<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Dashboard') }}
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
                            <p class="text-xs text-gray-500">Períodos interrompidos</p>
                        </div>
                        <div class="p-4 bg-white rounded shadow">
                            <h3 class="text-sm font-semibold text-gray-600">Remarcações pendentes</h3>
                            <p class="text-2xl font-bold text-yellow-600">{{ $totalRemarcacoes }}</p>
                            <p class="text-xs text-gray-500">Aguardando confirmação</p>
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
                                                <span class="font-semibold">{{ $ferias->periodos->count() }}</span>
                                            </td>
                                            <td class="px-4 py-2">
                                                @foreach ($ferias->periodos->take(2) as $periodo)
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
                                            </td>
                                            <td class="px-4 py-2">
                                                <div class="flex space-x-2">
                                                    <a href="/ferias/{{ $ferias->id }}"
                                                        class="px-3 py-1 text-xs text-blue-600 bg-blue-100 rounded hover:bg-blue-200">
                                                        👁️ Ver
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                                Nenhum lançamento de férias encontrado.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

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
        // DEBUG: Verificar se os dados estão chegando
        console.log('Meses:', {!! json_encode($meses) !!});
        console.log('Dados por Mês:', {!! json_encode($dadosGrafico) !!});
        // console.log('Meses:', {{ Js::from($meses) }});
        // console.log('Dados por Mês:', {{ Js::from($dadosGrafico) }});

        // Aguardar o DOM carregar completamente
        document.addEventListener('DOMContentLoaded', function() {
            // Gráfico de Férias por Mês
            const ctxMes = document.getElementById('graficoFeriasPorMes');

            if (ctxMes) {
                new Chart(ctxMes, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($meses) !!},
                        datasets: [{
                            label: 'Períodos de Férias',
                            data: {!! json_encode($dadosGrafico) !!},
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
            } else {
                console.error('Elemento graficoFeriasPorMes não encontrado');
            }

            // Gráfico de Situações
            const ctxSituacao = document.getElementById('graficoSituacoes');

            if (ctxSituacao) {
                // Dados de exemplo para situações - você pode implementar a busca real depois
                const situacoesData = {
                    'Planejado': {{ $totalComFerias - $totalInterrompidas }},
                    'Interrompido': {{ $totalInterrompidas }},
                    'Remarcado': {{ $totalRemarcacoes }}
                };

                new Chart(ctxSituacao, {
                    type: 'doughnut',
                    data: {
                        labels: Object.keys(situacoesData),
                        datasets: [{
                            data: Object.values(situacoesData),
                            backgroundColor: [
                                'rgba(34, 197, 94, 0.6)', // Verde - Planejado
                                'rgba(239, 68, 68, 0.6)', // Vermelho - Interrompido
                                'rgba(245, 158, 11, 0.6)', // Amarelo - Remarcado
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            } else {
                console.error('Elemento graficoSituacoes não encontrado');
            }
        });
    </script>

</x-app-layout>
